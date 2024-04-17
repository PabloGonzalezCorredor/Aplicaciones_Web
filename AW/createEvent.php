<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/FormularioCrearEvento.php';

$form = new FormularioCrearEvento();
$htmlFormCrearEvento = $form->gestiona();

$tituloPagina = 'Create Event';
$tituloCabecera = 'Create Event';
$padding = '300px';

$contenidoPrincipal = <<<EOS
$htmlFormCrearEvento
EOS;

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
