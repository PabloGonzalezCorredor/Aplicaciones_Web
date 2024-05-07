<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/EventoSA.php';
require_once __DIR__.'/SA/UsuarioSA.php';

require_once __DIR__.'/includes/helpers/evento.php';

//Por defecto se selecciona el dia de HOY
if(empty($_GET)) {
    $hoy = new DateTime();
    $fechaURL = $hoy->format('Y-m-d');
    header("Location: index.php?dia=$fechaURL");
}

$tituloCabecera = '<img src="' . RUTA_IMGS . '/MidnightLogo.png" height= "50px">';
$tituloPagina = 'Home';
$puedoVolver = false;

if (!isset($_SESSION["login"])) {
    //Si no esta logueado
	header("Location: login.php");
    exit;

} else if($_SESSION['esAdmin']){
    //Si es promotor
    $eventos = EventoSA::obtenerEventosSiguientesPromotor($_SESSION['id']);
    
    $contenidoPrincipal = <<<EOS
    <div class="index-container">
        <div class="events-container">
            <h2>Your Events</h2>
    EOS;

    if (empty($eventos)) {
        $contenidoPrincipal .= "<div class='aviso'><p>No has creado eventos proximos<p></div>";
    } else {
        $contenidoPrincipal .= '<ul class="events">';
        foreach ($eventos as $evento) {
            $contenidoPrincipal .= "<li>" . mostrarEvento($evento, 0) . "</li>";
        }
        $contenidoPrincipal .= '</ul>';
    }


    $contenidoPrincipal .= <<<EOS
    </div>
    <div class="events-by-day-container">
        <ul class="selector">
    EOS;

    // Mostrar el selector para los proximos 7 días
    $contenidoPrincipal .= mostrarSelector();

    $contenidoPrincipal .= <<<EOS
        </ul>
    EOS;


    $diaSeleccionado = $_GET['dia'];
    $eventosFiltrados = array();

    foreach ($eventos as $evento) {
        if ($evento->getFecha() == $diaSeleccionado) {
            $eventosFiltrados[] = $evento;
        }
    }

    if ($eventosFiltrados){
        $contenidoPrincipal .= '<ul class="events-by-day">';
        foreach($eventosFiltrados as $evento){
            $contenidoPrincipal .= "<li>" . mostrarEvento($evento, 0) . "</li>";
        }
        $contenidoPrincipal .= '</ul>';
    } else {
        $contenidoPrincipal .= "<div class='aviso'><p>No hay eventos disponibles para este dia<p></div>";
    }  

    $contenidoPrincipal .= <<<EOS
            <a href="createEvent.php" class="add-event-button">+</a>
        </div>
    </div>
    EOS;

} else if(!$_SESSION['esAdmin']){
    //Si es usuario
    $eventos = EventoSA::obtenerEventosSiguientes();
    
    $contenidoPrincipal = <<<EOS
    <div class="index-container">
        <div class="events-container">
            <h2>Todos los Eventos</h2>
    EOS;

    if (empty($eventos)) {
        $contenidoPrincipal .= "<div class='aviso'><p>No hay eventos disponibles</p></div>";
    } else {
        $contenidoPrincipal .= '<ul class="events">';
        foreach ($eventos as $evento) {
            $usuarios = UsuarioSA::obtenerSeguidos($_SESSION['id']);
            $numUsuariosAsistentes = EventoSA::contarAsistentesSeguidos($evento->getId(), $usuarios);

            $contenidoPrincipal .= "<li>" . mostrarEvento($evento, $numUsuariosAsistentes) . "</li>";
        }
        $contenidoPrincipal .= '</ul>';
    }


    $contenidoPrincipal .= <<<EOS
    </div>
    <div class="events-by-day-container">
        <ul class="selector">
    EOS;

    $contenidoPrincipal .= mostrarSelector();

    $contenidoPrincipal .= <<<EOS
        </ul>
    EOS;


    $diaSeleccionado = $_GET['dia'];
    $eventosFiltrados = array();

    foreach ($eventos as $evento) {
        if ($evento->getFecha() == $diaSeleccionado) {
            $eventosFiltrados[] = $evento;
        }
    }

    if ($eventosFiltrados){
        $contenidoPrincipal .= '<ul class="events-by-day">';
        foreach($eventosFiltrados as $evento){
            $usuarios = UsuarioSA::obtenerSeguidos($_SESSION['id']);
            $numUsuariosAsistentes = EventoSA::contarAsistentesSeguidos($evento->getId(), $usuarios);

            $contenidoPrincipal .= "<li>" . mostrarEvento($evento, $numUsuariosAsistentes) . "</li>";
        }
        $contenidoPrincipal .= '</ul>';
    } else {
        $contenidoPrincipal .= "<div class='aviso'><p>No hay eventos disponibles para este dia<p></div>";
    }  

    $contenidoPrincipal .= "</div></div>";

}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
