<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/TO/Evento.php';

if (!isset($_SESSION["login"])) {
    //Si no esta logueado

	header("Location: login.php");
    exit;

}   else {
    //Si está logueado

    $tituloCabecera = "Search";
    $tituloPagina = "Search";
    $padding = '25%';

    $contenidoPrincipal = <<<HTML
        <input type="text" class="search-bar" id="searchInput" placeholder="Buscar">
        <div id="results" style="margin-top: 20px;"></div> <!-- Contenedor para la lista de resultados -->
    HTML;
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
