<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/UsuarioSA.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idUsuario = $_GET['id'];
   
    $evento = UsuarioSA::dejarDeSeguir($_SESSION['id'], $idUsuario);
} 

header("Location: profile.php?id=$idUsuario");

?>

