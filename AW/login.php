<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioLogin.php';

$form = new FormularioLogin();
$htmlFormLogin = $form->gestiona();

$tituloCabecera = '<img src="' . RUTA_IMGS . '/MidnightLogo.png" height= "50px">';
$tituloPagina = 'Login';
$padding = '30%';

$contenidoPrincipal = <<<EOS
<div class="switch-log">
    <button onclick="location.href='login.php'" class="log-button" style="background-color: #8250CA"><h4>Login</h4></button>
    <button onclick="location.href='registro.php'" class="log-button"><h4 style="color: #979ca4">Signup</h4></button>
</div>
$htmlFormLogin
EOS;

require __DIR__.'/includes/vistas/plantillas/plantillaLog.php';
