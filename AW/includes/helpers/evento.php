<?php

function mostrarEvento($evento){
    setlocale(LC_TIME,'es_ES.utf8','es_ES', 'esp');
    $fechaFormateada = ucfirst(strftime("%A, %e %B", strtotime($evento->getFecha())));
    $recurso = "data:image/jpeg;base64," . $evento->getImagen();
    $contenido = <<<EOS
    <a class="evento" style="background:url($recurso) center; background-size: cover;" href="event.php?id={$evento->getId()}">
        <div class="evento-gradient">
            <h3 class="evento-name">{$evento->getNombreEvento()}</h3>
            <h5 class="evento-date">{$fechaFormateada}</h5>
        </div>
    </a>
    EOS;

    return $contenido;
}

function mostrarEntradaButton($entrada){
    return '<a onclick="mostrarPantallaEntrada(\'' . $entrada . '\')" class="entrada-button"><h5>Ver entrada</h5></a>';
}

function mostrarEntrada($entrada){
    $contenido = <<<HTML
    <a id="pantalla-entrada" onclick="cerrarPantallaEntrada()">
        <div class="entrada">
            <div id="codigo-qr" style="width: 200px; height: 200px;"></div>
        </div>  
    </a>
    HTML;

    return $contenido;
}

function mostrarSelector(){
    $hoy = new DateTime();
    $contenido = '';
    for ($i = 0; $i < 7; $i++) {
        $dia = clone $hoy;
        $dia->modify("+$i day");
        if ($i == 0){
            $nombreDia = "Hoy";
        } else if ($i == 1){
            $nombreDia = "Mañana";
        } else {
            $nombreDia = ucfirst(strftime('%A', $dia->getTimestamp()));
        }
        $fechaURL = $dia->format('Y-m-d');
        $selectedStyle = isset($_GET['dia']) && $_GET['dia'] == $fechaURL ? 'background-color: #8250CA;' : '';
        $enlaceDia = "<a  href=?dia=$fechaURL>$nombreDia</a>";
        $contenido .= "<li style='$selectedStyle'><h4>$enlaceDia</h4></li>";
    }
    return $contenido;
}

function mostrarTarifa($tarifa){
    $contenido = <<<EOS
        <button class="tarifa">
        <h3>{$tarifa->getConsumiciones()} x {$tarifa->getPrecio()}€</h3>
        <p>{$tarifa->getInformacion()}</p>
        </button>
    EOS;

    return $contenido;
}
?>
