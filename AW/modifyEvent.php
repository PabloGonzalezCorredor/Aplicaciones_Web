<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioModificarEvento.php';
require_once __DIR__.'/SA/EventoSA.php';

// Verificar si se ha enviado un ID de evento y si es válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idEvento = $_GET['id'];

    $eventoYTarifas = EventoSA::obtenerEvento($idEvento);

    $form = new FormularioModificarEvento($eventoYTarifas[0]); //Solo le pasamos el evento porque las tarifas no se pueden modificar
    $htmlFormModificarEvento = $form->gestiona();

} else {
    // Manejar el caso en el que no se proporcionó un ID de evento válido
    $htmlFormModificarEvento = "<p>No se proporcionó un ID de evento válido.</p>";
}

$tituloPagina = 'Modify Event';
$tituloCabecera = 'Modify Event';
$padding = '20%';

$contenidoPrincipal = <<<EOS
$htmlFormModificarEvento
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
