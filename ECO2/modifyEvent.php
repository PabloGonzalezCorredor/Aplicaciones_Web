<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioModificarEvento.php';
require_once __DIR__.'/includes/Evento.php';

// Verificar si se ha enviado un ID de evento y si es válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idEvento = $_GET['id'];

    $evento = Evento::buscaPorId($idEvento);

    $form = new FormularioModificarEvento($evento);
    $htmlFormModificarEvento = $form->gestiona();

} else {
    // Manejar el caso en el que no se proporcionó un ID de evento válido
    $htmlFormModificarEvento = "<p>No se proporcionó un ID de evento válido.</p>";
}

$tituloPagina = 'Modify Event';
$tituloCabecera = 'Modify Event';

$contenidoPrincipal = <<<EOS
$htmlFormModificarEvento
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
