<?php

function mostrarUsuario($usuario){

    $esPromotor = ($usuario->getRol() == 1) ? true : false;
    if ($esPromotor) $radius = "10px";
    else $radius = "";

    $recurso = "data:image/jpeg;base64," . $usuario->getImagen();
    $enlace = ($usuario->getId() == $_SESSION['id']) ? "profile.php" : "profile.php?id={$usuario->getId()}"; //Si es el actual se redirije al perfil
    $contenido = <<<EOS
    <a href={$enlace}>
        <img src={$recurso} class="profile-image2" style="border-radius: $radius">
        <div style="display: flex; flex-direction: column; margin-left: 20px; width: 100%;">
            <h4>{$usuario->getNombre()}</h4>
            <p>{$usuario->getNombreUsuario()}</p>
        </div>
    </a>
    EOS;

    return $contenido;
}

function mostrarPerfil($usuario, $estado, $numSeguidores, $numSeguidos){
    //estado: 0 seguir, 1 en_espera, 2 seguido
    $id = $usuario->getId();
    $esPromotor = ($usuario->getRol() == 1) ? true : false;
    $esPrivado = ($usuario->getPrivacidad() == 0) ? true : false;

    if ($esPromotor) $radius = "40px";
    else $radius = "";

    $recurso = "data:image/jpeg;base64," . $usuario->getImagen();
    $contenido = <<<HTML
        <div class="profile-info-container">
            <img src={$recurso} class="profile-image" style="border-radius: $radius">
            <div class="profile-info">
                <div class="profile-info2">
                    <div>
                        <h3>{$usuario->getNombre()}<h3>
                        <p>@{$usuario->getNombreUsuario()}<p>
                    </div>
    HTML;

    if ($estado === null){
        $contenido .= mostrarBotonEditar();
    } else {
        $url = ($esPrivado) ? 'requestUser.php?id=' : 'followUser.php?id=';
        switch ($estado){
            case 0: $contenido .= mostrarBotonSeguir($id, $url); break;
            case 1: $contenido .= mostrarBotonSolicitado($id); break;
            case 2: $contenido .= mostrarBotonDejarSeguir($id); break;
        }
    }

    $contenido .= '</div>';

    if($numSeguidores !== null && $numSeguidos !== null){
        $contenido .= <<<HTML
                <div style="display:flex; gap: 50px; width: 20%">
                    <a href="followers.php?id={$id}" style="display:flex; gap: 10px; align-items: center">
                        <h3>{$numSeguidores}</h3>
                        <h4>Followers</h4>
                    </a>
                    <a href="following.php?id={$id}"  style="display:flex; gap: 10px; align-items: center">
                        <h3>{$numSeguidos}</h3>
                        <h4>Following</h4>
                    </a>
                </div>
        HTML;
    }

    $contenido .= <<<HTML
            </div>
    HTML;

    $contenido .= "</div>";

    return $contenido;
}

function mostrarBotonSeguir($idUsuario, $url){
    $contenido = "<button class='follow-button' onclick=\"location.href='{$url}{$idUsuario}'\"><p style='color: white'>Follow</p></button>";
    return $contenido;
}
function mostrarBotonDejarSeguir($idUsuario){
    $contenido = "<button class='unfollow-button' onclick=\"location.href='unfollowUser.php?id=$idUsuario'\"><p style='color: white'>Unfollow</p></button>";
    return $contenido;
}
function mostrarBotonSolicitado($idUsuario){
    $contenido = "<button class='unfollow-button' onclick=\"location.href='unrequestUser.php?id=$idUsuario'\"><p style='color: white'>Solicitado</p></button>";
    return $contenido;
}
function mostrarBotonEditar(){
    $contenido = '<a href="modifyProfile.php">
        <svg fill="none" width="32" height="32" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="m13.1105 5.01689-9.41498 11.77501c-.171.214-.234.49-.171.755l.681 2.885 3.039-.038c.289-.003.556-.132.733-.352 3.21698-4.025 9.34998-11.69901 9.52398-11.92401.164-.266.228-.642.142-1.004-.088-.371-.319-.686-.652-.887-.071-.049-1.756-1.357-1.808-1.398-.634-.508-1.559-.42-2.073.188zm-9.49698 16.92301c-.347 0-.649-.238-.73-.577l-.819-3.471c-.169-.719-.001-1.461.46-2.037l9.41998-11.78201c.004-.004.007-.009.011-.013 1.033-1.235 2.901-1.417 4.161-.406.05.039 1.723 1.339 1.723 1.339.608.362 1.083 1.009 1.263 1.775.179.758.049 1.54-.368 2.201-.031.049-.058.091-9.58598 12.01101-.459.572-1.147.905-1.886.914l-3.639.046z" fill="white"/><path fill-rule="evenodd" clip-rule="evenodd" d="m16.2234 11.6849c-.16 0-.32-.051-.457-.155l-5.452-4.18803c-.32798-.252-.38998-.722-.138-1.052.253-.328.723-.389 1.052-.137l5.453 4.18703c.328.252.39.723.137 1.052-.147.192-.37.293-.595.293z" fill="white"/></svg>
    </a>';
    return $contenido;
}

?>
