<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioRegistro.php';

$form = new FormularioRegistro();
$htmlFormRegistro = $form->gestiona();

$tituloCabecera = '<img src=MidnightLogo.png height= 50px>';
$tituloPagina = 'Signup';

$contenidoPrincipal = <<<EOS
<div style='flex-direction: row'>
    <button onclick="location.href='login.php'"><h4>Login</h4></button>
    <button onclick="location.href='registro.php'"><h4>Signup</h4></button>
</div>
$htmlFormRegistro
EOS;

require __DIR__.'/includes/vistas/plantillas/plantillaLog.php';
