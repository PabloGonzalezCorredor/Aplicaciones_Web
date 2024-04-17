<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title><?= $tituloPagina ?></title>
    <link rel="stylesheet" type="text/css" href="<?= RUTA_CSS ?>/estilo.css" />
</head>
<body>
	<div class="contenedor">
		<?php
		require(RAIZ_APP.'/vistas/comun/sidebarIzq.php');
		?>
		<div class="contenedor2">
			<?php 
				require(RAIZ_APP.'/vistas/comun/cabecera.php');
			?>
			<main style="padding-left: <?= $padding ?>; padding-right: <?= $padding ?> ">
				<?= $contenidoPrincipal ?>
			</main>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js"></script>
	<script src="<?= RUTA_JS ?>/scripts.js"></script>
</body>
</html>
