
<?php
function logout() {
	$rutaApp = RUTA_APP;
	$html='';
	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
		return "<a style= 'text-align: right;' href='{$rutaApp}/logout.php'><p>Logout<p></a>";
	}
	return $html;
}

$align = (strpos($tituloCabecera, "img")) ? "center" : "left"; //Si es el logo alineado en el centro sino izquierda
$color = (strpos($tituloCabecera, "img")) ? "#30394a" : "#1e2630"; //Si es el logo fondo grisaceo sino azul como el fondo
?>
<header class="header" style="background-color:<?php echo $color?>; padding-right: <?php echo $padding?>; padding-left: <?php echo $padding?>;">
	<h1 style="width:100%; text-align: <?php echo $align?>"><?php echo $tituloCabecera?></h1>
	<?= logout() ?>
</header>