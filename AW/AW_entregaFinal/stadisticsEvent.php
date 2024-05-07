<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/EventoSA.php';
require_once __DIR__.'/SA/UsuarioSA.php';
require_once __DIR__.'/TO/Tarifa.php';
require_once __DIR__.'/TO/Evento.php';
require_once __DIR__.'/TO/Asistencia.php';
require_once __DIR__.'/includes/helpers/evento.php';

if (!isset($_SESSION["login"])) {
    //Si no esta logueado
	header("Location: login.php");
    exit;
} else {
    //Si es promotor
    $id = $_GET['id'];

    $eventoYTarifa = EventoSA::obtenerEventoPorId($id);
    $evento = $eventoYTarifa[0];
    $tarifas = $eventoYTarifa[1];

    $tituloCabecera = $evento->getNombreEvento();
    $tituloPagina = $evento->getNombreEvento();
    $puedoVolver = true;

    if ($evento->getIdPromotor() == $_SESSION["id"]) {
        //Si es mio

        $asistencias = EventoSA::obtenerAsistentes($evento->getId());
        $asistentes = array();

        foreach ($asistencias as $asistencia){
            $asistentes[] = UsuarioSA::buscaUsuarioPorId($asistencia->getIdUsuario());
        }
        

        $estadisticas = calcularEstadisticas($evento, $tarifas, $asistencias, $asistentes);


        $contenidoPrincipal = <<<HTML
        <div class="stadistics-container">
            <div class="stadistic-container">
                <h3>Sexo</h3>
                <div class="stadistics-section">
                    <p>Hombres: </p> <h4>{$estadisticas['hombres']}</h4>
                    <p>Mujeres: </p> <h4>{$estadisticas['mujeres']}</h4>
                </div>
            </div>
            <div class="stadistic-container">
                <h3>Edad</h3>
                <div class="stadistics-section">
                    <p>Edad Media: </p> <h4>{$estadisticas['edadMedia']}</h4>
                </div>
            </div>
            <div class="stadistic-container">
                <h3>Ventas</h3>
                <div class="stadistics-section">
                    <p>Sin Validar: </p> <h4>{$estadisticas['totalAsistentesSinValidar']}</h4>
                    <p>Validados: </p> <h4>{$estadisticas['totalAsistentesValidados']}</h4>
                    <p>Recaudado: </p> <h4>{$estadisticas['totalDineroRecaudado']} €</h4>
                    <p>Consumiciones: </p> <h4>{$estadisticas['totalConsumiciones']}</h4>
                    <p>Consumiciones Medias: </p> <h4>{$estadisticas['consumicionesMedias']}</h4>
                    <p>Precio Medio: </p> <h4>{$estadisticas['precioMedio']} €</h4>
                </div>
            </div>
        </div>
    HTML;
    
        
        $contenidoPrincipal .= '</div>';
        

    }

    
} 

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
