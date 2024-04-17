<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/EventoSA.php';

// Verificar si se ha enviado un ID de evento y si es válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idEvento = $_GET['id'];

    $evento = EventoSA::borraEvento($idEvento);
} 

header("Location: index.php");

?>

