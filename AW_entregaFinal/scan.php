<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/helpers/notificacion.php';
require_once __DIR__.'/SA/NotificacionSA.php';
require_once __DIR__.'/SA/UsuarioSA.php';
require_once __DIR__.'/SA/EventoSA.php';
require_once __DIR__.'/TO/Usuario.php';
require_once __DIR__.'/TO/Evento.php';

$tituloCabecera = "Scan";
$tituloPagina = "Scan";
$puedoVolver = false;

if (!isset($_SESSION["login"])) {
    //Si no esta logueado

	header("Location: login.php");
    exit;

} else if ($_SESSION['esAdmin']) {
    $eventos = EventoSA::obtenerEventosSiguientesPromotor($_SESSION['id']);

    $id = '';
    $contenidoPrincipal = <<<HTML
    <div class="scan-container">
        <div style="display: flex; width: 100%; gap: 10px">
            <select class="search-bar" id="event-select">
    HTML;

    if (isset($_GET['id'])){
        $id = $_GET['id'];
    }

    foreach ($eventos as $evento) {
        $idEvento = $evento->getId();
        $nombreEvento = $evento->getNombreEvento();
        $fecha = $evento->getFecha();

        if ($idEvento == $id){
            $selected = " selected";
        } else {
            $selected = "";
        }

        $contenidoPrincipal .= "<option value='{$idEvento}'{$selected}>{$nombreEvento} - {$fecha}</option>";
    }
    $contenidoPrincipal .= <<<HTML
            </select>
            <button class="button" id="scan-button" onclick="escanearQR()"><h4>Escanear</h4></button>
        </div>
        <div class="camara" id="camara"></div>
    HTML;
    
    if(isset($_GET['codigo']) && isset($_GET['id'])) {
        $codigoQR = $_GET['codigo'];
        $idEvento = $_GET['id'];
        $entrada = EventoSA::comprobarEntrada($codigoQR, $idEvento);
        if ($entrada != false){
            if ($entrada->getValidada() == 0){
                $idAsistencia = $entrada->getId();
                $contenidoPrincipal .= <<<HTML
                    <div class="valida-entrada-buttons">
                        <div class="entrada-valida"><h4>Entrada válida</h4></div>
                        <a href="validateTicket.php?id={$idAsistencia}" class="validar-button"><h4>Validar</h4></a>
                    </div>
                HTML;
            } else {
                $contenidoPrincipal .= <<<HTML
                    <div class="entrada-erronea"><h4>Entrada ya usada</h4></div>
                HTML;
            }
        } else {
            $contenidoPrincipal .= <<<HTML
                <div class="entrada-erronea"><h4>Entrada erronea</h4></div>
            HTML;
        }
    }
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
