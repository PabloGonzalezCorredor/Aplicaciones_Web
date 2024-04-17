<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioModificarUsuario.php';
require_once __DIR__.'/SA/UsuarioSA.php';

if (!isset($_SESSION["login"])) {
    //Si no esta logueado
	header("Location: login.php");
    exit;
} else {

    $usuario = UsuarioSA::buscaUsuarioPorId($_SESSION['id']);

    $form = new FormularioModificarUsuario($usuario); 
    $htmlFormModificarUsuario = $form->gestiona();

    $tituloPagina = 'Modify Profile';
    $tituloCabecera = 'Modify Profile';
    $padding = '20%';

    $contenidoPrincipal = <<<EOS
    $htmlFormModificarUsuario
    EOS;
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
