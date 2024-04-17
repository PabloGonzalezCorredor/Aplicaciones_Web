<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/UsuarioSA.php';
require_once __DIR__.'/includes/helpers/usuario.php';

if(isset($_GET['q']) && !empty($_GET['q'])) {
    $busqueda = $_GET['q'];
    $usuarios = UsuarioSA::buscaUsuarios($busqueda);
    echo "<ul class='user'>";
    foreach ($usuarios as $usuario) {
        echo "<li>" . mostrarUsuario($usuario) . "</li>"; 
    }
    echo "</ul>";
}
?>
