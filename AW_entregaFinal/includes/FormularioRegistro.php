<?php
require_once __DIR__.'/Formulario.php';
require_once __DIR__.'/../SA/UsuarioSA.php';

class FormularioRegistro extends Formulario
{
    public function __construct() {
        parent::__construct('formRegistro', ['urlRedireccion' => 'index.php', 'enctype' => 'multipart/form-data']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $nombreUsuario = $datos['nombreUsuario'] ?? '';
        $nombre = $datos['nombre'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'nombre', 'imagen', 'edad', 'password', 'password2'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<HTML
        $htmlErroresGlobales
        <div class="form-log">
            <div>
                <input id="nombreUsuario" type="text" placeholder="Nombre de Usuario"  name="nombreUsuario" value="$nombreUsuario" />
                {$erroresCampos['nombreUsuario']}
            </div>
            <div>
                <input id="nombre" type="text" placeholder="Nombre"  name="nombre" value="$nombre" />
                {$erroresCampos['nombre']}
            </div>
            <div>
                <input id="imagen" type="file" placeholder="Imagen" name="imagen" />
                {$erroresCampos['imagen']}
            </div>
            <div>
                <select id="tipo" name="tipo" onchange="mostrarDatos(this.value)">
                    <option value="usuario">Usuario</option>
                    <option value="promotor">Promotor</option>
                </select>
            </div>
            <div id="edad" style="display:block;">
                <input id="edad" type="number" placeholder="Edad" name="edad" />
                {$erroresCampos['edad']}
            </div>
            <div id="genero" style="display:block;">
                <select id="genero" name="genero">
                    <option value="hombre">Hombre</option>
                    <option value="mujer">Mujer</option>
                </select>
            </div>
            <div id="privacidad" style="display:block;">
                <select id="privacidad" name="privacidad">
                    <option value="1">Público</option>
                    <option value="0">Privado</option>
                </select>
            </div>
            <div>
                <input id="password" type="password" placeholder="Contraseña"  name="password" />
                {$erroresCampos['password']}
            </div>
            <div>
                <input id="password2" type="password" placeholder="Repite la contraseña"  name="password2" />
                {$erroresCampos['password2']}
            </div>
        </div>
        <div style="justify-content: center; display: flex;">
            <button type="submit" class="button" name="registro"><h4>Registrar</h4></button>
        </div>
        HTML;
        return $html;
    }
    

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreUsuario || mb_strlen($nombreUsuario) < 3) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario tiene que tener una longitud de al menos 3 caracteres.';
        }

        $nombre = trim($datos['nombre'] ?? '');
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombre || mb_strlen($nombre) < 3) {
            $this->errores['nombre'] = 'El nombre tiene que tener una longitud de al menos 3 caracteres.';
        }

        if ($datos['tipo'] === "usuario") {
            $rol = 2;
            $genero = $datos['genero'];
            $edad = trim($datos['edad'] ?? '');
            $edad = filter_var($edad, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ( ! $edad ) {
                $this->errores['edad'] = 'Introduce una edad';
            }
            $privacidad = $datos['privacidad'];
        } else {
            $rol = 1;
            $genero = '';
            $edad = '';
            $privacidad = 1;
        }

        $imagen = $_FILES['imagen'];

        if ( $imagen['error'] !== UPLOAD_ERR_OK) {
            $this->errores['imagen'] = 'Debe introducir una imagen de perfil.';
        } else {
            $rutaTemporal = $imagen['tmp_name'];

            $contenidoImagen = file_get_contents($rutaTemporal);
            $imagenBinaria = base64_encode($contenidoImagen);
        }

        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password || mb_strlen($password) < 5 ) {
            $this->errores['password'] = 'El password tiene que tener una longitud de al menos 5 caracteres.';
        }

        $password2 = trim($datos['password2'] ?? '');
        $password2 = filter_var($password2, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password2 || $password != $password2 ) {
            $this->errores['password2'] = 'Los passwords deben coincidir';
        }

        if (count($this->errores) === 0) {
            $usuario = UsuarioSA::crea($imagenBinaria, $nombreUsuario, $password, ucfirst($nombre), $rol, $privacidad, $edad, $genero);
	
            if ($usuario == NULL) {
                $this->errores[] = "El usuario ya existe";
            } else {
                $_SESSION['login'] = true;
                $_SESSION['id'] = $usuario->getId();
                $_SESSION['user'] = $usuario->getNombreUsuario();
                $_SESSION['name'] = $usuario->getNombre();
                $_SESSION['esAdmin'] = ($usuario->getRol() == 1) ? true : false;
            }
        }
    }
}