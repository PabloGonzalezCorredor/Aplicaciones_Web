<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/UsuarioSA.php';
require_once __DIR__.'/SA/NotificacionSA.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idNotificacion = $_GET['id'];
   
    $notificacion = NotificacionSA::obtenerNotificacionSolicitudPorId($idNotificacion);
    UsuarioSA::seguir($notificacion->getIdSeguidor(), $notificacion->getIdUsuario());
    NotificacionSA::eliminarSolicitud($idNotificacion);
} 

header("Location: activity.php");

?>

