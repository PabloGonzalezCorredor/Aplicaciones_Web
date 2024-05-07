<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/UsuarioSA.php';
require_once __DIR__.'/includes/helpers/usuario.php';

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $busqueda = $_GET['q'];
    $usuarios = UsuarioSA::buscaUsuarios($busqueda);
    
    $promotores = [];
    $usuarios_normales = [];
    
    // Separar los usuarios en dos grupos según su rol
    foreach ($usuarios as $usuario) {
        if ($usuario->getRol() == 1) {
            $promotores[] = $usuario;
        } else {
            $usuarios_normales[] = $usuario;
        }
    }
    
    // Mostrar los usuarios normales
    if (!empty($usuarios_normales)) {
        echo "<div class='users-container'><h3>Usuarios</h3>";
        foreach ($usuarios_normales as $usuario_normal) {
            echo "<ul class='user'>";
            echo "<li>" . mostrarUsuario($usuario_normal) . "</li>"; 
            echo "</ul></div>";
        }
    }

    // Mostrar los promotores
    if (!empty($promotores)) {
        echo "<div class='users-container'><h3>Promotores</h3>";
        foreach ($promotores as $promotor) {
            echo "<ul class='user'>";
            echo "<li>" . mostrarUsuario($promotor) . "</li>"; 
            echo "</ul></div>";
        }
    }

}

?>
