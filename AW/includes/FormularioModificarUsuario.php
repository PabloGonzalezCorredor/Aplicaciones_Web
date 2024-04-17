<?php
require_once __DIR__.'/Formulario.php';
require_once __DIR__.'/../TO/Usuario.php';
require_once __DIR__.'/../SA/usuarioSA.php';

class FormularioModificarUsuario extends Formulario {
    private $usuario;

    public function __construct($usuario = null) {
        parent::__construct('formModificarUsuario', ['urlRedireccion' => 'profile.php', 'enctype' => 'multipart/form-data']);
        $this->usuario = $usuario;
    }

    protected function generaCamposFormulario(&$datos) {
        // Obtener los datos del usuario, si está disponible
        $nombreUsuario = $this->usuario ? $this->usuario->getnombreUsuario() : '';
        $nombre = $this->usuario ? $this->usuario->getNombre() : '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['imagen', 'nombreUsuario', 'nombre', 'password'], $this->errores, 'span', array('class' => 'error'));

        // Generar HTML para cada campo del formulario, rellenando los valores si están disponibles
        $html = <<<HTML
        $htmlErroresGlobales
        <fieldset class="form">
            <div style="display: flex; align-items: center;">
                <label for="imagen">Imagen:</label>
                <input id="imagen" type="file" name="imagen"/>
            </div>
            {$erroresCampos['imagen']}
            <div style="display: flex; align-items: center">
                <label for="nombreUsuario">Usuario:</label>
                <input id="nombreUsuario" type="text" placeholder="Nombre del usuario"  name="nombreUsuario" value="$nombreUsuario"/>
            </div>
            {$erroresCampos['nombreUsuario']}
            <div style="display: flex; align-items: center">
                <label for="nombre">Nombre:</label>
                <input id="nombre" type="text"  name="nombre" value="$nombre"/>
            </div>
            {$erroresCampos['nombre']}
            <div style="display: flex; align-items: center">
                <label for="password">password:</label>
                <input id="password" type="password"  name="password"/>
            </div>
            {$erroresCampos['password']}
            <div style="display: flex; width: 100%; justify-content: center">
                <button type="submit" class="next-button" name="modificar"><h4>Modificar Usuario</h4></button>
            </div>  
        </fieldset>
        HTML;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        $imagen = $_FILES['imagen'];

        if ($imagen['error'] !== UPLOAD_ERR_OK) {
            $imagenBinaria = $this->usuario->getImagen();
        } else {
            $rutaTemporal = $imagen['tmp_name'];

            $contenidoImagen = file_get_contents($rutaTemporal);
            $imagenBinaria = base64_encode($contenidoImagen);
        }

        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreUsuario || mb_strlen($nombreUsuario) < 5) {
            $this->errores['nombreUsuario'] = 'El nombre del usuario tiene que tener una longitud de al menos 5 caracteres.';
        }

        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombre || mb_strlen($nombre) < 5) {
            $this->errores['nombre'] = 'El nombre tiene que tener una longitud de al menos 5 caracteres.';
        }

        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password ) {
            $this->errores['password'] = 'Introduce una contraseña';
        }

        if (count($this->errores) === 0) {
            UsuarioSA::actualizaUsuario($this->usuario, $imagenBinaria, $nombreUsuario, $nombre, $password);
        }
    }

}
?>
