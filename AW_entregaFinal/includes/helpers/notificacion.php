<?php

function mostrarNotificacionAsistencia($notificacion, $usuario, $evento){
    $imagenUsuario = "data:image/jpeg;base64," . $usuario->getImagen();
    $imagenEvento = "data:image/jpeg;base64," . $evento->getImagen();

    $tiempo_pasado_formato = formatearFecha($notificacion->getFecha());

    $idUsuario = $usuario->getId();
    $idEvento = $evento->getId();

    $contenido = <<<HTML
    <div class="notificacion">
        <a href="profile.php?id={$idUsuario}" style="display: flex; align-items: center; gap: 20px">
            <img src={$imagenUsuario} class="profile-image3">
        </a>
        <div class="info-notification">
            <p style="color: white"><b>{$usuario->getNombreUsuario()}</b>  va a ir a un evento. </p>
            <p style="min-width:fit-content">{$tiempo_pasado_formato}</P>
        </div>
        <a href="event.php?id={$idEvento}">
            <img src={$imagenEvento} class="evento-imagen2">
        </a>
    </div>
    HTML;

    return $contenido;
}
function mostrarNotificacionEvento($notificacion, $usuario, $evento){
    $imagenUsuario = "data:image/jpeg;base64," . $usuario->getImagen();
    $imagenEvento = "data:image/jpeg;base64," . $evento->getImagen();

    $tiempo_pasado_formato = formatearFecha($notificacion->getFecha());

    $idUsuario = $usuario->getId();
    $idEvento = $evento->getId();

    $contenido = <<<HTML
    <div class="notificacion">
        <a href="profile.php?id={$idUsuario}" style="display: flex; align-items: center; gap: 20px">
            <img src={$imagenUsuario} class="profile-image3" style="border-radius: 15px">
        </a>
        <div class="info-notification">
            <p style="color: white"><b>{$usuario->getNombreUsuario()}</b> ha publicado un evento. </p>
            <p style="min-width:fit-content">{$tiempo_pasado_formato}</P>
        </div>
        <a href="event.php?id={$idEvento}">
            <img src={$imagenEvento} class="evento-imagen2">
        </a>
    </div>
    HTML;

    return $contenido;
}
function mostrarNotificacionSolicitud($notificacion, $usuario){
    $imagenUsuario = "data:image/jpeg;base64," . $usuario->getImagen();

    $tiempo_pasado_formato = formatearFecha($notificacion->getFecha());

    $idUsuario = $usuario->getId();
    $idNotificacion = $notificacion->getId();

    $contenido = <<<HTML
    <div class="notificacion">
        <a href="profile.php?id={$idUsuario}" style="display: flex; align-items: center; gap: 20px">
            <img src={$imagenUsuario} class="profile-image3" style="border-radius: 15px">
        </a>
        <div class="info-notification">
            <p style="color: white"><b>{$usuario->getNombreUsuario()}</b>  quiere seguirte. </p>
            <p style="min-width:fit-content">{$tiempo_pasado_formato}</p>
        </div>
        <div class="solicitud-buttons-container">
            <a class="aceptar-button" href="acceptRequest.php?id={$idNotificacion}">
                <p style="color: white">Aceptar</p>
            </a>
            <a class="rechazar-button" href="rejectRequest.php?id={$idNotificacion}">
                <p style="color: white">Rechazar</p>
            </a>
        </div>
    </div>
    HTML;

    return $contenido;
}

function formatearFecha($fecha){
    $timestamp_fecha_dada = strtotime($fecha);

    $tiempo_pasado = time() - $timestamp_fecha_dada;

    $tiempo_pasado_formato = "";
    if ($tiempo_pasado < 60) {
        $tiempo_pasado_formato = "$tiempo_pasado s";
    } elseif ($tiempo_pasado < 3600) {
        $minutos = floor($tiempo_pasado / 60);
        $tiempo_pasado_formato = "$minutos m";
    } elseif ($tiempo_pasado < 86400) {
        $horas = floor($tiempo_pasado / 3600);
        $tiempo_pasado_formato = "$horas h";
    } elseif ($tiempo_pasado < 2628000) {
        $dias = floor($tiempo_pasado / 86400);
        $tiempo_pasado_formato = "$dias d";
    } elseif ($tiempo_pasado < 31536000) {
        $meses = floor($tiempo_pasado / 2628000);
        $tiempo_pasado_formato = "$meses M";
    } else {
        $años = floor($tiempo_pasado / 31536000);
        $tiempo_pasado_formato = "$años A";
    }

    return $tiempo_pasado_formato;
}

?>
