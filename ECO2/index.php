<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/Evento.php';

$tituloCabecera = '<img src=MidnightLogo.png height= 50px>';
$tituloPagina = 'Home';

if (!isset($_SESSION["login"])) {
    //Si no esta logueado
	header("Location: login.php");
    exit;

} else if($_SESSION['esAdmin']){
    //Si es promotor
    $eventos = Evento::obtenerEventosSiguientesPromotor();
    
        $contenidoPrincipal = <<<EOS
        <h2>Your Events</h2>
        <ul class="events">
        EOS;

        if (empty($eventos)) {
            $contenidoPrincipal .= "<li><p>No has creado eventos proximos<p></li>";
        } else {
            foreach ($eventos as $evento) {
                setlocale(LC_TIME,'es_ES.utf8','es_ES', 'esp');
                $fechaFormateada = ucfirst(strftime("%A, %e %B", strtotime($evento->getFecha())));
                $recurso = "data:image/jpeg;base64," . $evento->getImagen();
                $contenidoPrincipal .= <<<EOS
                    <li>
                        <a href="event.php?id={$evento->getId()}">
                            <img src={$recurso} alt="Imagen 1" width="200" height="150">
                            <p></p>
                            <h3>{$evento->getNombreEvento()}</h3>
                            <h5>{$fechaFormateada}</h5>
                        </a>
                    </li>
                EOS;
            }
        }
    
    
    $contenidoPrincipal .= <<<EOS
    </ul>
    <ul class="selector">
    EOS;

    // Obtener la fecha de hoy
    setlocale(LC_TIME,'es_ES.utf8', 'es_ES', 'esp');
    $hoy = new DateTime();

    // Generar el selector para los proximos 7 días
    for ($i = 0; $i < 7; $i++) {
        $dia = clone $hoy;
        $dia->modify("+$i day");
        if ($i == 0){
            $nombreDia = "Hoy";
        } else if ($i == 1){
            $nombreDia = "Mañana";
        } else {
            $nombreDia = ucfirst(strftime('%A', $dia->getTimestamp()));
        }
        $fechaURL = $dia->format('Y-m-d');
        $selectedStyle = isset($_GET['dia']) && $_GET['dia'] == $fechaURL ? 'background-color: #8250CA;' : '';
        $enlaceDia = "<a  href=?dia=$fechaURL>$nombreDia</a>";
        $contenidoPrincipal .= "<li style='$selectedStyle'><h4>$enlaceDia</h4></li>";
    }

    $contenidoPrincipal .= <<<EOS
        </ul>
        <ul class="events-by-day">
    EOS;


    if(isset($_GET['dia'])) {
        $diaSeleccionado = $_GET['dia'];
        $hay = false;
        foreach ($eventos as $evento) {
            // Verifica si el evento ocurre en el día seleccionado
            if ($evento->getFecha() == $diaSeleccionado) {
                $hay = true;
                $recurso = "data:image/jpeg;base64," . $evento->getImagen();
                $contenidoPrincipal .= <<<EOS
                    <li>
                        <a href="event.php?id={$evento->getId()}">
                            <img src={$recurso} alt="Imagen 1" width="200" height="150">
                            <h3>{$evento->getNombreEvento()}</h3>
                        </a>
                    </li>
                EOS;
            }
        }
        if ($hay == false){
            $contenidoPrincipal .= "<li><p>No has creado eventos para este dia<p></li>";
        }  
    }

    $contenidoPrincipal .= <<<EOS
    </ul>
    <a href="createEvent.php" class="add-event-button"><h2>Add Event</h2></a>
EOS;

}else if(!$_SESSION['esAdmin']){

    $eventos = Evento::obtenerEventosSiguientes();
    
    $contenidoPrincipal = <<<EOS
    <h2>Related events</h2>
    <ul class="events">
    EOS;

    if (empty($eventos)) {
        $contenidoPrincipal .= "<li>No hay eventos disponibles</li>";
    } else {
        foreach ($eventos as $evento) {
            setlocale(LC_TIME,'es_ES.utf8','es_ES', 'esp');
            $fechaFormateada = ucfirst(strftime("%A, %e %B", strtotime($evento->getFecha())));
            $recurso = "data:image/jpeg;base64," . $evento->getImagen();
            $contenidoPrincipal .= <<<EOS
                <li>
                    <a href="event.php?id={$evento->getId()}">
                        <img src={$recurso} alt="Imagen 1" width="200" height="150">
                        <h3>{$evento->getNombreEvento()}</h3>
                        <h5>{$fechaFormateada}</h5>
                    </a>
                </li>
            EOS;
        }
    }


    $contenidoPrincipal .= <<<EOS
    </ul>
    <ul class="selector">
    EOS;

    // Obtener la fecha de hoy
    setlocale(LC_TIME,'es_ES.utf8', 'es_ES', 'esp');
    $hoy = new DateTime();

    // Generar el selector para los proximos 7 días
    for ($i = 0; $i < 7; $i++) {
        $dia = clone $hoy;
        $dia->modify("+$i day");
        if ($i == 0){
            $nombreDia = "Hoy";
        } else if ($i == 1){
            $nombreDia = "Mañana";
        } else {
            $nombreDia = ucfirst(strftime('%A', $dia->getTimestamp()));
        }
        $fechaURL = $dia->format('Y-m-d');
        $selectedStyle = isset($_GET['dia']) && $_GET['dia'] == $fechaURL ? 'background-color: #8250CA;' : '';
        $enlaceDia = "<a  href=?dia=$fechaURL>$nombreDia</a>";
        $contenidoPrincipal .= "<li style='$selectedStyle'><h4>$enlaceDia</h4></li>";
    }

    $contenidoPrincipal .= <<<EOS
        </ul>
        <ul class="events-by-day">
    EOS;


    if(isset($_GET['dia'])) {
        $diaSeleccionado = $_GET['dia'];
        $hay = false;
        foreach ($eventos as $evento) {
            // Verifica si el evento ocurre en el día seleccionado
            if ($evento->getFecha() == $diaSeleccionado) {
                $hay = true;
                $recurso = "data:image/jpeg;base64," . $evento->getImagen();
                $contenidoPrincipal .= <<<EOS
                    <li>
                        <a href="event.php?id={$evento->getId()}">
                            <img src={$recurso} alt="Imagen 1" width="200" height="150">
                            <h3>{$evento->getNombreEvento()}</h3>
                        </a>
                    </li>
                EOS;
            }
        }
        if ($hay == false){
            $contenidoPrincipal .= "<li><p>No hay eventos disponibles para este dia<p></li>";
        }  
    }


}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
