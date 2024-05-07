<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/EventoSA.php';
require_once __DIR__.'/SA/UsuarioSA.php';
require_once __DIR__.'/SA/NotificacionSA.php';
require_once __DIR__.'/includes/helpers/usuario.php';
require_once __DIR__.'/includes/helpers/evento.php';

$tituloCabecera = "Profile";
$tituloPagina = "Profile";

$contenidoPrincipal = "<div class='profile-container'>";
if (!isset($_SESSION["login"])) {
    //Si no esta logueado

	header("Location: login.php");
    exit;

} else if (isset($_GET['id']) && !empty($_GET['id'])){
    //Si esta logueado y no es el usuario actual
    $puedoVolver = true;

    $id = $_GET['id'];

    $esPromotor = UsuarioSA::esPromotor($id);
    $esPrivado = UsuarioSA::esPrivado($id);
    $usuario = UsuarioSA::buscaUsuarioPorId($id);
    $estado = (UsuarioSA::comprobarSeguido($_SESSION['id'], $id)) ? 2 : ((NotificacionSA::comprobarSolicitud($id)) ? 1 : 0);
    $numSeguidores = UsuarioSA::contarSeguidores($id);
    $numSeguidos = UsuarioSA::contarSeguidos($id);
    
    if ($esPromotor){
        $eventosSiguientes = EventoSA::obtenerEventosSiguientesPromotor($id);
        $eventosPasados = EventoSA::obtenerEventosPasadosPromotor($id);
    } else {
        $eventosSiguientes = EventoSA::obtenerEventosSiguientesUsuario($id);
        $eventosPasados = EventoSA::obtenerEventosPasadosUsuario($id);
    }

    $contenidoPrincipal .= mostrarPerfil($usuario, $estado, $numSeguidores, $numSeguidos);

    
    if ($estado != 2 && $esPrivado) {
        $contenidoPrincipal .= <<<HTML
            <div class="aviso"><p>Este usuario es privado</p></div>
        HTML;
    } else {
        $contenidoPrincipal .= <<<HTML
        <div class="events-section-container">
            <div class="events-profile-container">
                <h3>Eventos Próximos</h3>
        HTML;

        if (!$eventosSiguientes){
            $contenidoPrincipal .= "<div class='aviso'><p>No tiene eventos próximos<p></div>";
        } else {
            $contenidoPrincipal .= "<ul class='events'>";
            foreach ($eventosSiguientes as $evento) {
                $usuarios = UsuarioSA::obtenerSeguidos($_SESSION['id']);
                $numUsuariosAsistentes = EventoSA::contarAsistentesSeguidos($evento->getId(), $usuarios);

                $contenidoPrincipal .= "<li>" . mostrarEvento($evento, $numUsuariosAsistentes) . "</li>";
            }
            $contenidoPrincipal .= "</ul>";
        }

        $contenidoPrincipal .= <<<HTML
            </div>
            <div class="events-profile-container">
                <h3>Eventos Pasados</h3>
        HTML;
        
        if (!$eventosPasados){
            $contenidoPrincipal .= "<div class='aviso'><p>No ha ido a ningún evento<p></div>";
        } else {
            $contenidoPrincipal .= "<ul class='events'>";
            foreach ($eventosPasados as $evento) {
                $usuarios = UsuarioSA::obtenerSeguidos($_SESSION['id']);
                $numUsuariosAsistentes = EventoSA::contarAsistentesSeguidos($evento->getId(), $usuarios);

                $contenidoPrincipal .= "<li>" . mostrarEvento($evento, $numUsuariosAsistentes) . "</li>";
            }
            $contenidoPrincipal .= "</ul>";
        }
        $contenidoPrincipal .= '</div></div>';
    }

} else {
    //Si está logueado y no es otro perfil
    $id = $_SESSION['id'];
    $puedoVolver = false;

    $esPromotor = UsuarioSA::esPromotor($id);
    $usuario = UsuarioSA::buscaUsuarioPorId($id);
    $numSeguidos = UsuarioSA::contarSeguidos($id);
    $numSeguidores = UsuarioSA::contarSeguidores($id);

    if ($esPromotor){
        $eventosSiguientes = EventoSA::obtenerEventosSiguientesPromotor($id);
        $eventosPasados = EventoSA::obtenerEventosPasadosPromotor($id);
    } else {

        $eventosSiguientes = EventoSA::obtenerEventosSiguientesUsuario($id);
        $eventosPasados = EventoSA::obtenerEventosPasadosUsuario($id);
    }

    $contenidoPrincipal .= mostrarPerfil($usuario, null, $numSeguidores, $numSeguidos);

    $contenidoPrincipal .= <<<HTML
    <div class="events-section-container">
        <div class="events-profile-container">
            <h3>Eventos Próximos</h3>
    HTML;


    if (!$eventosSiguientes){
        $contenidoPrincipal .= "<div class='aviso'><p>No tienes eventos próximos<p></div>";
    } else {
        $contenidoPrincipal .= "<ul class='events'>";
        foreach ($eventosSiguientes as $evento) {
            $usuarios = UsuarioSA::obtenerSeguidos($id);
            $numUsuariosAsistentes = EventoSA::contarAsistentesSeguidos($evento->getId(), $usuarios);
            $entrada = EventoSA::obtenerEntrada($evento->getId());

            $contenidoPrincipal .= "<li>" . mostrarEvento($evento, $numUsuariosAsistentes); 
            if (!$esPromotor) { 
                $contenidoPrincipal .= mostrarEntradaButton($entrada) . "</li>";
                $contenidoPrincipal .= mostrarEntrada($entrada);
            }
        }
        $contenidoPrincipal .= "</ul>";
    }

    $contenidoPrincipal .= <<<HTML
        </div>
        <div class="events-profile-container">
            <h3>Eventos Pasados</h3>
    HTML;
    
    if (!$eventosPasados){
        $contenidoPrincipal .= "<div class='aviso'><p>No tienes eventos pasados<p></div>";
    } else {
        $contenidoPrincipal .= "<ul class='events' style='height: none'>";
        foreach ($eventosPasados as $evento) {
            $usuarios = UsuarioSA::obtenerSeguidos($id);
            $numUsuariosAsistentes = EventoSA::contarAsistentesSeguidos($evento->getId(), $usuarios);

            $contenidoPrincipal .= "<li>" . mostrarEvento($evento, $numUsuariosAsistentes) . "</li>";
        }
        $contenidoPrincipal .= "</ul>";
    }
    $contenidoPrincipal .= '</div></div>';
}
$contenidoPrincipal .= "</div>";

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
