<?php
require_once __DIR__.'/includes/config.php';

//Doble seguridad: unset + destroy
unset($_SESSION['login']);
unset($_SESSION['esAdmin']);
unset($_SESSION['name']);
unset($_SESSION['id']);
unset($_SESSION['user']);


session_destroy();

header("Location: index.php");
?>
