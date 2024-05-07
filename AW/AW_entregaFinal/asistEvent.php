<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/EventoSA.php';
require_once __DIR__.'/SA/UsuarioSA.php';
require_once __DIR__.'/SA/NotificacionSA.php';
require_once __DIR__.'/TO/NotificacionAsistencia.php';
require_once __DIR__.'/TO/Usuario.php';
require_once __DIR__.'/includes/helpers/redsys.php';
  	  

$miObj = new RedsysAPI;

if (!empty( $_POST ) ) {//URL DE RESP. ONLINE
					
    $version = $_POST["Ds_SignatureVersion"];
    $datos = $_POST["Ds_MerchantParameters"];
    $signatureRecibida = $_POST["Ds_Signature"];
    
    $decodec = $miObj->decodeMerchantParameters($datos);	
    $kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES
    $firma = $miObj->createMerchantSignatureNotif($kc,$datos);	

    if ($firma === $signatureRecibida){
        // Verificar si se ha enviado un ID de evento y si es válido
        if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['tarifa']) && is_numeric($_GET['tarifa'])) {

            $idEvento = $_GET['id'];
            $idTarifa = $_GET['tarifa'];
        
            if (EventoSA::insertaAsistencia($idEvento, $idTarifa)){
                $asistencia = EventoSA::obtenerAsistencia($_SESSION['id'], $idEvento);
                $seguidores = UsuarioSA::obtenerSeguidores($_SESSION['id']);

                $idAsistencia = $asistencia->getId();

                foreach ($seguidores as $seguidor){
                    NotificacionSA::notificarAsistencia($seguidor->getId(), $idAsistencia);
                }
            }
        } 
    } 

} else{
    if (!empty( $_GET ) ) {//URL DE RESP. ONLINE
            
        $version = $_GET["Ds_SignatureVersion"];
        $datos = $_GET["Ds_MerchantParameters"];
        $signatureRecibida = $_GET["Ds_Signature"];
            
    
        $decodec = $miObj->decodeMerchantParameters($datos);
        $kc = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7'; //Clave recuperada de CANALES
        $firma = $miObj->createMerchantSignatureNotif($kc,$datos);
    
        if ($firma === $signatureRecibida){
            // Verificar si se ha enviado un ID de evento y si es válido
            if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['tarifa']) && is_numeric($_GET['tarifa'])) {

                $idEvento = $_GET['id'];
                $idTarifa = $_GET['tarifa'];
            
                if (EventoSA::insertaAsistencia($idEvento, $idTarifa)){
                    $asistencia = EventoSA::obtenerAsistencia($_SESSION['id'], $idEvento);
                    $seguidores = UsuarioSA::obtenerSeguidores($_SESSION['id']);

                    $idAsistencia = $asistencia->getId();

                    foreach ($seguidores as $seguidor){
                        NotificacionSA::notificarAsistencia($seguidor->getId(), $idAsistencia);
                    }
                }
            } 
        } 
    }
}

header("Location: index.php");


?>

