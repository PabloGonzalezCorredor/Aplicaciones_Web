<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/EventoSA.php';
require_once __DIR__.'/TO/Tarifa.php';
require_once __DIR__.'/includes/helpers/evento.php';
require_once __DIR__.'/includes/helpers/redsys.php';



if (!isset($_SESSION["login"])) {
    //Si no esta logueado
	header("Location: login.php");
    exit;
} else {

    $id = $_GET['id'];
    $puedoComprar = !$_SESSION['esAdmin'] && ((EventoSA::obtenerAsistencia($_SESSION['id'], $id) == false) ? true : false);
    $eventoYTarifa = EventoSA::obtenerEventoPorId($id);
    $evento = $eventoYTarifa[0];
    $tarifas = $eventoYTarifa[1];

    $tituloCabecera = $evento->getNombreEvento();
    $tituloPagina = $evento->getNombreEvento();
    $puedoVolver = true;

    $contenidoPrincipal = '<div class="event-container">';

    if ($evento->getIdPromotor() == $_SESSION["id"]) {
        //Si es mio
        $contenidoPrincipal .= mostrarInformacion($evento);

        $contenidoPrincipal .= '<div style=" display: flex; gap: 10px; flex-direction: row">';
        $contenidoPrincipal .= mostrarBorrarButton($id);
        $contenidoPrincipal .= mostrarModificarButton($id);
        $contenidoPrincipal .= mostrarEstadisticasButton($id);
        $contenidoPrincipal .= '</div>';

        $contenidoPrincipal .= <<<HTML
        <div class="entradas-container">
            <h2>Entradas</h2>
                <ul class="tarifas">
        HTML;

        if (empty($tarifas)) {
            $contenidoPrincipal .= "<li>No hay tarifas disponibles</li>";
        } else {
            foreach ($tarifas as $tarifa) {
                $contenidoPrincipal .= '<li>' . mostrarTarifa($tarifa, $id, false) . '</li>';
            }
        }
        $contenidoPrincipal .= '</ul></div>';
        
    } else {
        //Si no es mio
        $contenidoPrincipal .= mostrarInformacion($evento);

        $contenidoPrincipal .= <<<HTML
        <div class="entradas-container">
            <h2>Entradas</h2>
            <div class="tarifas-container">
                <ul class="tarifas">
        HTML;

        if (empty($tarifas)) {
            $contenidoPrincipal .= "<li>No hay tarifas disponibles</li>";
        } else {
            foreach ($tarifas as $tarifa) {
                $contenidoPrincipal .= '<li>' . mostrarTarifa($tarifa, $id, $puedoComprar) . '</li>';
            }
        }
        $contenidoPrincipal .= "</ul>";

        if (isset($_GET['tarifa'])) {

            $tarifa = EventoSA::obtenerTarifaPorId($_GET['tarifa']);

            $miObj = new RedsysAPI;

            /*
            Numeración: 4918019160034602	
            Caducidad: 12/34;
            CVV: 123
            */

            $fuc="999008881";
            $terminal="1";
            $moneda="978";
            $trans="0";
            $url="";
            $urlOKKO="http://localhost/AW/asistEvent.php?id={$id}&tarifa={$tarifa->getId()}";
            $id=substr( str_shuffle( str_repeat( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 10 ) ), 0, 12 );
            $amount = $tarifa->getPrecio() * 100;

            $miObj->setParameter("DS_MERCHANT_AMOUNT",$amount);
            $miObj->setParameter("DS_MERCHANT_ORDER",$id);
            $miObj->setParameter("DS_MERCHANT_MERCHANTCODE",$fuc);
            $miObj->setParameter("DS_MERCHANT_CURRENCY",$moneda);
            $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE",$trans);
            $miObj->setParameter("DS_MERCHANT_TERMINAL",$terminal);
            $miObj->setParameter("DS_MERCHANT_MERCHANTURL",$url);
            $miObj->setParameter("DS_MERCHANT_URLOK",$urlOKKO);
            $miObj->setParameter("DS_MERCHANT_URLKO",$urlOKKO);

            //Datos de configuración
            $version="HMAC_SHA256_V1";
            $kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
            $request = "";
            $params = $miObj->createMerchantParameters();
            $signature = $miObj->createMerchantSignature($kc);

            $contenidoPrincipal .= <<<HTML
                <form name="form" class="button" style="display: flex; justify-content: center" action="https://sis-t.redsys.es:25443/sis/realizarPago" method="POST" target="_blank">
                    <input type="hidden" name="Ds_SignatureVersion" value="HMAC_SHA256_V1">
                    <input type="hidden" name="Ds_MerchantParameters" value="{$params}">
                    <input type="hidden" name="Ds_Signature" value="{$signature}">
                    <button type="submit" style="background-color: transparent; width: 100%; height: 100%"><h4>Comprar</h4></button>
                </form>
            </div>
            HTML;
        }
    }
    $contenidoPrincipal .= "</div></div>";
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
