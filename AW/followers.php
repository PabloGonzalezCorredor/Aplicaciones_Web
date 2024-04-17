<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/UsuarioSA.php';
require_once __DIR__.'/includes/helpers/usuario.php';

if (!isset($_SESSION["login"])) {
    //Si no esta logueado

	header("Location: login.php");
    exit;

}  else if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];

    $tituloCabecera = "Followers";
    $tituloPagina = "Followers";
    $padding = '25%';

    $usuarios = UsuarioSA::obtenerSeguidores($id);

    if ($usuarios){
        $contenidoPrincipal = "<ul class='user'>";
        foreach ($usuarios as $usuario) {
            $contenidoPrincipal .= "<li>" . mostrarUsuario($usuario) . "</li>"; 
        }
        $contenidoPrincipal .= "</ul>";
    } else {
        $contenidoPrincipal = "<div class='aviso'><p>No te sigue ningún usuario</p></div>";
    }
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
