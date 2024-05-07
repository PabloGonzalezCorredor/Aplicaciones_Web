
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
$border = (strpos($tituloCabecera, "img")) ? "" : "1px solid rgba(151, 156, 164, 0.1);";

$back = <<<HTML
<a href="#" onclick="history.back();">
	<svg width="60" height="45" viewBox="0 0 60 45" fill="none">
	<path
		d="M28.664 10.8401C29.0713 11.2524 29.1083 11.8976 28.7751 12.3522L28.664 12.4824L18.7698 22.5L28.664 32.5176C29.0713 32.9298 29.1083 33.575 28.7751 34.0296L28.664 34.1599C28.2568 34.5721 27.6196 34.6096 27.1705 34.2723L27.0418 34.1599L16.336 23.3212C15.9287 22.9089 15.8917 22.2637 16.2249 21.8091L16.336 21.6788L27.0418 10.8401C27.4898 10.3866 28.2161 10.3866 28.664 10.8401Z"
		fill="white"
	/>
	</svg>
</a>
HTML;

$back = ($puedoVolver) ? $back : '';

?>
<header style="background-color:<?php echo $color?>; border-bottom: <?php echo $border?>">
	<?php echo $back?>
	<h1 style="width:100%; text-align: <?php echo $align?>"><?php echo $tituloCabecera?></h1>
	<?= logout() ?>
</header>