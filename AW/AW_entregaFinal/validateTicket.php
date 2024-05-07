<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/EventoSA.php';

if (isset($_GET['id'])) {
    $idAsistencia = $_GET['id'];
   
    EventoSA::validarEntrada($idAsistencia);
} 

header('Location: ' . $_SERVER['HTTP_REFERER']);

?>

