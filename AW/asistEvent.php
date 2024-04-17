<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/EventoSA.php';

// Verificar si se ha enviado un ID de evento y si es válido
if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['tarifa']) && is_numeric($_GET['tarifa'])) {
    $idEvento = $_GET['id'];
    $idTarifa = $_GET['tarifa'];
   
    $evento = EventoSA::insertaAsistencia($idEvento, $idTarifa);
} 

header("Location: index.php");

?>

