<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/NotificacionSA.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idUsuario = $_GET['id'];
   
    NotificacionSA::desnotificarSolicitud($idUsuario);
} 

header("Location: profile.php?id=$idUsuario");

?>

