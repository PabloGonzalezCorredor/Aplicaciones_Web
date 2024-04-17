<?php

require_once __DIR__.'/includes/config.php';

if (!isset($_SESSION["login"])) {
    //Si no esta logueado

	header("Location: login.php");
    exit;

}   else {
    //Si está logueado

    $tituloCabecera = "Activity";
    $tituloPagina = "Activity";
    $padding = '25%';
    $contenidoPrincipal = <<<HTML
    <p>Proximamente</p>
    HTML;

}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
