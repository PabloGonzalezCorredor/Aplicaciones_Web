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
    $puedoVolver = false;

    $contenidoPrincipal = <<<HTML
        <div class="search-container">
            <input type="text" class="search-bar" id="searchInput" placeholder="Buscar">
            <div id="results" class="users-results-container"></div> <!-- Contenedor para la lista de resultados -->
        </div>
    HTML;
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
