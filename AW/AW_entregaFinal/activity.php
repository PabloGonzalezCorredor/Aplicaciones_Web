<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/helpers/notificacion.php';
require_once __DIR__.'/SA/NotificacionSA.php';
require_once __DIR__.'/SA/UsuarioSA.php';
require_once __DIR__.'/SA/EventoSA.php';
require_once __DIR__.'/TO/Usuario.php';
require_once __DIR__.'/TO/Evento.php';

$tituloCabecera = "Activity";
$tituloPagina = "Activity";
$puedoVolver = false;

if (!isset($_SESSION["login"])) {
    //Si no esta logueado

	header("Location: login.php");
    exit;

}   else if (!$_SESSION['esAdmin']) {
    //Si no es admin

    $notificaciones = NotificacionSA::obtenerNotificaciones();
    $contenidoPrincipal = '<div class="activity-container">';

    if ($notificaciones){
        $notificaciones_nuevas = [];
        $notificaciones_pasadas = [];
    
        // Separar las notificaciones en dos grupos
        foreach ($notificaciones as $notificacion) {
            if ($notificacion->getVista() == 1) {
                $notificaciones_pasadas[] = $notificacion;
            } else {
                $notificaciones_nuevas[] = $notificacion;
            }
        }

        // Definir una función de comparación para ordenar por fecha
        function compararPorFecha($a, $b) {
            return strtotime($b->getFecha()) - strtotime($a->getFecha());
        }

        // Ordenar las notificaciones por fecha en orden descendente
        usort($notificaciones_nuevas, 'compararPorFecha');
        usort($notificaciones_pasadas, 'compararPorFecha');
    
        $contenidoPrincipal .= "<ul class='notificaciones'>";
    
        // Mostrar las notificaciones nuevas
        if (!empty($notificaciones_nuevas)) {
            $contenidoPrincipal .= "<h3>New</h3>";
            foreach ($notificaciones_nuevas as $notificacion) {
                if ($notificacion instanceof NotificacionAsistencia){
                    $asistencia = EventoSA::obtenerAsistenciaPorId($notificacion->getIdAsistencia());
                    $evento = EventoSA::obtenerEventoPorId($asistencia->getIdEvento());
                    $usuario = UsuarioSA::buscaUsuarioPorId($asistencia->getIdUsuario());
                    $contenidoPrincipal .= "<li>" . mostrarNotificacionAsistencia($notificacion, $usuario, $evento[0]) . "</li>";
                } else if ($notificacion instanceof NotificacionEvento) {
                    $evento = EventoSA::obtenerEventoPorId($notificacion->getIdEvento());
                    $usuario = UsuarioSA::buscaUsuarioPorId($evento[0]->getIdPromotor());
                    $contenidoPrincipal .= "<li>" . mostrarNotificacionEvento($notificacion, $usuario, $evento[0]) . "</li>";
                } else if ($notificacion instanceof NotificacionSolicitud){
                    $usuario = UsuarioSA::buscaUsuarioPorId($notificacion->getIdSeguidor());
                    $contenidoPrincipal .= "<li>" . mostrarNotificacionSolicitud($notificacion, $usuario) . "</li>";
                }

                NotificacionSA::marcarVista($notificacion);
            }
        }
    
        // Mostrar las notificaciones pasadas
        if (!empty($notificaciones_pasadas)) {
            $contenidoPrincipal .= "<h3>Past</h3>";
            foreach ($notificaciones_pasadas as $notificacion) {
                if ($notificacion instanceof NotificacionAsistencia){
                    $asistencia = EventoSA::obtenerAsistenciaPorId($notificacion->getIdAsistencia());
                    $evento = EventoSA::obtenerEventoPorId($asistencia->getIdEvento());
                    $usuario = UsuarioSA::buscaUsuarioPorId($asistencia->getIdUsuario());
                    $contenidoPrincipal .= "<li>" . mostrarNotificacionAsistencia($notificacion, $usuario, $evento[0]) . "</li>";
                } else if ($notificacion instanceof NotificacionEvento) {
                    $evento = EventoSA::obtenerEventoPorId($notificacion->getIdEvento());
                    $usuario = UsuarioSA::buscaUsuarioPorId($evento[0]->getIdPromotor());
                    $contenidoPrincipal .= "<li>" . mostrarNotificacionEvento($notificacion, $usuario, $evento[0]) . "</li>";
                } else if ($notificacion instanceof NotificacionSolicitud){
                    $usuario = UsuarioSA::buscaUsuarioPorId($notificacion->getIdSeguidor());
                    $contenidoPrincipal .= "<li>" . mostrarNotificacionSolicitud($notificacion, $usuario) . "</li>";
                }
                NotificacionSA::marcarVista($notificacion);
            }
        }
    
        $contenidoPrincipal .= "</ul>";
    } else {
        $contenidoPrincipal .= "<div class='aviso'><p>No tienes ninguna notificación</p></div>";
    }

    $contenidoPrincipal .= "</div>";
    

}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
