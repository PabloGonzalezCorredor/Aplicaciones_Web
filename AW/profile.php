<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/EventoSA.php';
require_once __DIR__.'/SA/UsuarioSA.php';
require_once __DIR__.'/includes/helpers/usuario.php';
require_once __DIR__.'/includes/helpers/evento.php';

$tituloCabecera = "Profile";
$tituloPagina = "Profile";
$padding = '25%';

if (!isset($_SESSION["login"])) {
    //Si no esta logueado

	header("Location: login.php");
    exit;

} else if (isset($_GET['id']) && !empty($_GET['id'])){
    //Si esta logueado y no es el usuario actual
    $id = $_GET['id'];


    $esPromotor = UsuarioSA::esPromotor($id);
    $usuario = UsuarioSA::buscaUsuarioPorId($id);
    $esSeguido = UsuarioSA::comprobarSeguido($id);
    $numSeguidores = UsuarioSA::contarSeguidores($id);
    $numSeguidos = UsuarioSA::contarSeguidos($id);

    $contenidoPrincipal = mostrarPerfil($usuario, $esSeguido, $numSeguidores, $numSeguidos);

    if ($esPromotor){
        $eventosSiguientes = EventoSA::obtenerEventosSiguientesPromotor($id);
        $eventosPasados = EventoSA::obtenerEventosPasadosPromotor($id);
    } else {
        $eventosSiguientes = EventoSA::obtenerEventosSiguientesUsuario($id);
        $eventosPasados = EventoSA::obtenerEventosPasadosUsuario($id);
    }

    $contenidoPrincipal .= <<<HTML
    <div class="events-section-container">
        <div class="events-container">
            <h2>Eventos Próximos</h2>
    HTML;


    if (!$eventosSiguientes){
        $contenidoPrincipal .= "<div class='aviso'><p>No tiene eventos próximos<p></div>";
    } else {
        $contenidoPrincipal .= "<ul class='events'>";
        foreach ($eventosSiguientes as $evento) {
            $contenidoPrincipal .= "<li>" . mostrarEvento($evento) . "</li>";
        }
        $contenidoPrincipal .= "</ul>";
    }

    $contenidoPrincipal .= <<<HTML
        </div>
        <div class="events-container">
            <h2>Eventos Pasados</h2>
    HTML;
    
    if (!$eventosPasados){
        $contenidoPrincipal .= "<div class='aviso'><p>No ha ido a ningún evento<p></div>";
    } else {
        $contenidoPrincipal .= "<ul class='events'>";
        foreach ($eventosPasados as $evento) {
            $contenidoPrincipal .= "<li>" . mostrarEvento($evento) . "</li>";
        }
        $contenidoPrincipal .= "</ul>";
    }
    $contenidoPrincipal .= '</div></div>';

} else {
    //Si está logueado y no es otro perfil

    $esPromotor = UsuarioSA::esPromotor($_SESSION['id']);
    $usuario = UsuarioSA::buscaUsuarioPorId($_SESSION['id']);
    $numSeguidores = UsuarioSA::contarSeguidores($_SESSION['id']);
    $numSeguidos = UsuarioSA::contarSeguidos($_SESSION['id']);

    $contenidoPrincipal = mostrarPerfilPropio($usuario, $numSeguidores, $numSeguidos);

    if ($esPromotor){
        $eventosSiguientes = EventoSA::obtenerEventosSiguientesPromotor($_SESSION['id']);
        $eventosPasados = EventoSA::obtenerEventosPasadosPromotor($_SESSION['id']);
    } else {

        $eventosSiguientes = EventoSA::obtenerEventosSiguientesUsuario($_SESSION['id']);
        $eventosPasados = EventoSA::obtenerEventosPasadosUsuario($_SESSION['id']);
    }

    $contenidoPrincipal .= <<<HTML
    <div class="events-section-container">
        <div class="events-container">
            <h2>Eventos Próximos</h2>
    HTML;


    if (!$eventosSiguientes){
        $contenidoPrincipal .= "<div class='aviso'><p>No tienes eventos próximos<p></div>";
    } else {
        $contenidoPrincipal .= "<ul class='events' style='height: 233px;'>";
        foreach ($eventosSiguientes as $evento) {
            $entrada = EventoSA::obtenerEntrada($evento->getId());

            $contenidoPrincipal .= "<li style='display: flex; flex-direction: column; gap: 10px'>" . mostrarEvento($evento); 
            if (!$esPromotor) { 
                $contenidoPrincipal .= mostrarEntradaButton($entrada) . "</li>";
                $contenidoPrincipal .= mostrarEntrada($entrada);
            }
        }
        $contenidoPrincipal .= "</ul>";
    }

    $contenidoPrincipal .= <<<HTML
        </div>
        <div class="events-container">
            <h2>Eventos Pasados</h2>
    HTML;
    
    if (!$eventosPasados){
        $contenidoPrincipal .= "<div class='aviso'><p>No has ido a ningún evento<p></div>";
    } else {
        $contenidoPrincipal .= "<ul class='events' style='height: none'>";
        foreach ($eventosPasados as $evento) {
            $contenidoPrincipal .= "<li>" . mostrarEvento($evento) . "</li>";
        }
        $contenidoPrincipal .= "</ul>";
    }
    $contenidoPrincipal .= '</div></div>';
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
