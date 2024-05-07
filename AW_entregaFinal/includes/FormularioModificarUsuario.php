<?php
require_once __DIR__.'/Formulario.php';
require_once __DIR__.'/../TO/Usuario.php';
require_once __DIR__.'/../SA/UsuarioSA.php';

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
        $privacidad = $this->usuario ? $this->usuario->getPrivacidad() : '';
        $edad = $this->usuario ? $this->usuario->getEdad() : '';
        $genero = $this->usuario ? $this->usuario->getGenero() : '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['imagen', 'nombreUsuario', 'nombre', 'edad', 'password'], $this->errores, 'span', array('class' => 'error'));

        // Generar HTML para cada campo del formulario, rellenando los valores si están disponibles
        $html = <<<HTML
        $htmlErroresGlobales
        <div class="form">
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
        HTML;

        if (!$_SESSION['esAdmin']){
            $html .= '<div style="display: flex; align-items: center">';
                $html .= '<label for="edad">Edad:</label>';
                $html .= '<input id="edad" type="number" name="edad" value="' . $edad . '"/>';
                $html .= $erroresCampos['edad']; 
            $html .= '</div>';
            
            $html .= '<div style="display: flex; align-items: center">';
                $html .= '<label for="genero">Género:</label>';
                    $html .= '<select id="genero" name="genero">';
                        $html .= '<option value="hombre"' . ($genero == "hombre" ? ' selected' : '') . '>Hombre</option>';
                        $html .= '<option value="mujer"' . ($genero == "mujer" ? ' selected' : '') . '>Mujer</option>';
                    $html .= '</select>';
            $html .= '</div>';
            
            $html .= '<div style="display: flex; align-items: center">';
                $html .= '<label for="privacidad">Privacidad:</label>';
                $html .= '<select id="privacidad" name="privacidad">';
                    $html .= '<option value="1"' . ($privacidad == 1 ? ' selected' : '') . '>Público</option>';
                    $html .= '<option value="0"' . ($privacidad == 0 ? ' selected' : '') . '>Privado</option>';
                $html .= '</select>';
            $html .= '</div>';
        }

        $html .= <<<HTML
            <div style="display: flex; align-items: center">
                <label for="password">password:</label>
                <input id="password" type="password"  name="password"/>
            </div>
            {$erroresCampos['password']}
            <div style="display: flex; width: 100%; justify-content: center">
                <button type="submit" class="button" name="modificar"><h4>Modificar Usuario</h4></button>
            </div>  
        </div>
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
        if ( ! $nombreUsuario || mb_strlen($nombreUsuario) < 3) {
            $this->errores['nombreUsuario'] = 'El nombre del usuario tiene que tener una longitud de al menos 3 caracteres.';
        }

        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombre || mb_strlen($nombre) < 3) {
            $this->errores['nombre'] = 'El nombre tiene que tener una longitud de al menos 3 caracteres.';
        }

        if (!$_SESSION['esAdmin']) {
            $genero = $datos['genero'];
            $edad = trim($datos['edad'] ?? '');
            $edad = filter_var($edad, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( ! $edad ) {
                $this->errores['edad'] = 'Introduce una edad';
            }
            $privacidad = $datos['privacidad'];
        } else {
            $genero = '';
            $edad = 0;
            $privacidad = 1;
        }

        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password || mb_strlen($password) < 5 ) {
            $this->errores['password'] = 'El password tiene que tener una longitud de al menos 5 caracteres.';
        }

        if (count($this->errores) === 0) {
            UsuarioSA::actualizaUsuario($this->usuario, $imagenBinaria, $nombreUsuario, $nombre, $password, $privacidad, $edad, $genero);
        }
    }

}
?>
