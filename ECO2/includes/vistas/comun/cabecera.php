
<?php
function logout() {
	$rutaApp = RUTA_APP;
	$html='';
	if (isset($_SESSION["login"]) && ($_SESSION["login"]===true)) {
		return "<a style= 'text-align: right' href='{$rutaApp}/logout.php'><p>Logout<p></a>";
	}
	return $html;
}
?>
<header class="header">
	<div style="flex-direction: row;">
		<h4><?php echo $tituloCabecera?></h4>
		<?= logout() ?>
	</div>
</header>