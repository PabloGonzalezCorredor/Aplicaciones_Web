<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/Evento.php';

if (!isset($_SESSION["login"])) {
    //Si no esta logueado
	header("Location: login.php");
    exit;
} else if ($_SESSION["esAdmin"]) {
    //Si es promotor
    $id = $_GET['id'];

    $evento = Evento::buscaPorId($id);
    $tituloCabecera = $evento->getNombreEvento();
    $tituloPagina = $evento->getNombreEvento();
    //Formateo de la fecha
    $fecha = $evento->getFecha();
    setlocale(LC_TIME,'es_ES.utf8','es_ES', 'esp');
    $fechaFormateada = ucfirst(strftime("%A, %e %B", strtotime($fecha)));

    $horaIni = $evento->getHoraIni();
    $horaFin = $evento->getHoraFin();
    $localizacion = $evento->getLocalizacion();
    
    $contenidoPrincipal = <<<EOS
    <div style="flex-direction: row;">
        <h3>{$fechaFormateada}<h3>
        <h3>{$horaIni}-{$horaFin}<h3>
    </div>

    <div style="flex-direction: row;">
        <button class="delete-event-button" onclick="location.href='deleteEvent.php?id={$id}'"><h4>Delete Event<h4></button>
        <button class="modify-event-button" onclick="location.href='modifyEvent.php?id={$id}'"><h4>Modify Event<h4></button>
    </div>
    <h2>Entradas</h2>
    <ul class="tarifas">
    EOS;

    $tarifas = $evento->getTarifas();
    if (empty($tarifas)) {
        $contenidoPrincipal .= "<li>No hay tarifas disponibles</li>";
    } else {
        foreach ($tarifas as $tarifa) {
            $contenidoPrincipal .= <<<EOS
                <li>
                    <div class="tarifa">
                        <h3>{$tarifa['consumiciones']} x {$tarifa['precio']}€</h3>
                        <p>{$tarifa['informacion']}</p>
                    </div>
                </li>
            EOS;
        }
    }
    $contenidoPrincipal .= <<<EOS
    </ul>
    EOS;
    
} else if (!$_SESSION["esAdmin"]){
    //Si es usuario

    $id = $_GET['id'];

    $evento = Evento::buscaPorId($id);
    $tituloCabecera = $evento->getNombreEvento();
    $tituloPagina = $evento->getNombreEvento();
    //Formateo de la fecha
    $fecha = $evento->getFecha();
    setlocale(LC_TIME,'es_ES.utf8','es_ES', 'esp');
    $fechaFormateada = ucfirst(strftime("%A, %e %B", strtotime($fecha)));

    $horaIni = $evento->getHoraIni();
    $horaFin = $evento->getHoraFin();
    $localizacion = $evento->getLocalizacion();
    
    $contenidoPrincipal = <<<EOS
    <div style="flex-direction: row;">
        <h3>{$fechaFormateada}<h3>
        <h3>{$horaIni}-{$horaFin}<h3>
    </div>

    <h2>Entradas</h2>
    <ul class="tarifas">
    EOS;

    $tarifas = $evento->getTarifas();
    if (empty($tarifas)) {
        $contenidoPrincipal .= "<li>No hay tarifas disponibles</li>";
    } else {
        foreach ($tarifas as $tarifa) {
            $tarifaId = $tarifa['id']; // ID de la tarifa
            $selectedStyle = isset($_GET['tarifa']) && $_GET['tarifa'] == $tarifaId ? 'background-color: #2A323F;' : '';
            $url = "?id={$id}";
            if (isset($_GET['tarifa']) && $_GET['tarifa'] == $tarifaId) {
                // Si la tarifa ya está seleccionada, eliminamos el parámetro de la URL al hacer clic nuevamente
                $url = "?id={$id}";
            } else {
                // Si la tarifa no está seleccionada, agregamos el parámetro de la URL al hacer clic
                $url .= "&tarifa={$tarifaId}";
            }
            $contenidoPrincipal .= <<<EOS
                <li>
                    <button class="tarifa" style="$selectedStyle" onclick="location.href='$url'">
                        <h3>{$tarifa['consumiciones']} x {$tarifa['precio']}€</h3>
                        <p>{$tarifa['informacion']}</p>
                    </button>
                </li>
            EOS;
        }
    }
    $contenidoPrincipal .= <<<EOS
    </ul>
    EOS;

    if (isset($_GET['tarifa'])) {
        $contenidoPrincipal .= <<<EOS
        <button href="#" class="comprar-button"><h4>Comprar</h4></button>;
        EOS;
    }
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
