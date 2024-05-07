<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioRegistro.php';

$form = new FormularioRegistro();
$htmlFormRegistro = $form->gestiona();

$tituloCabecera = '<img src="' . RUTA_IMGS . '/MidnightLogo.png" height= "50px">';
$tituloPagina = 'Signup';
$puedoVolver = false;

$contenidoPrincipal = <<<EOS
<div class="log-container">
    <div class="switch-log">
        <button onclick="location.href='login.php'" class="log-button" ><h4 style="color: #979ca4">Login</h4></button>
        <button onclick="location.href='registro.php'" class="log-button" style="background-color: #8250CA"><h4>Signup</h4></button>
    </div>
    $htmlFormRegistro
</div>
EOS;

require __DIR__.'/includes/vistas/plantillas/plantillaLog.php';
