<?php
require_once __DIR__.'/Formulario.php';
require_once __DIR__.'/../SA/UsuarioSA.php';

class FormularioLogin extends Formulario
{
    public function __construct() {
        parent::__construct('formLogin', ['urlRedireccion' => 'index.php']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        // Se reutiliza el nombre de usuario introducido previamente o se deja en blanco
        $nombreUsuario = $datos['nombreUsuario'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombreUsuario', 'password'], $this->errores, 'span', array('class' => 'error'));

        // Se genera el HTML asociado a los campos del formulario y los mensajes de error.
        $html = <<<HTML
        $htmlErroresGlobales
        <fieldset style="text-align: center">
            <div class="form-log">
                <div>
                    <input id="nombreUsuario" type="text" placeholder="Nombre de Usuario"  name="nombreUsuario" value="$nombreUsuario" />
                    {$erroresCampos['nombreUsuario']}
                </div>
                <div>
                    <input id="password" type="password" placeholder="Contraseña"  name="password" />
                    {$erroresCampos['password']}
                </div>
            </div>
            <button type="submit" class="next-button" name="login"><h4>Entrar<h4></button>
        </fieldset>
        HTML;
        return $html;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];
        $nombreUsuario = trim($datos['nombreUsuario'] ?? '');
        $nombreUsuario = filter_var($nombreUsuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreUsuario || empty($nombreUsuario) ) {
            $this->errores['nombreUsuario'] = 'El nombre de usuario no puede estar vacío';
        }
        
        $password = trim($datos['password'] ?? '');
        $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $password || empty($password) ) {
            $this->errores['password'] = 'El password no puede estar vacío.';
        }
        
        if (count($this->errores) === 0) {
            $usuario = UsuarioSA::login($nombreUsuario, $password);
        
            if (!$usuario) {
                $this->errores[] = "El usuario o el password no coinciden";
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
