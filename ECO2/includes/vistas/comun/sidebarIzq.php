
<nav id="sidebarIzq">
	<ul class="bottom-bar">
		<?php if($_SESSION['esAdmin']): ?>
			<li><h4><a href="<?= RUTA_APP ?>/index.php">Home</a></h4></li>
			<li><h4><a href="<?= RUTA_APP ?>/contenido.php">Search</a></h4></li>
			<li><h4><a href="<?= RUTA_APP ?>/admin.php">Statistics</a></h4></li>
		<?php else: ?>
			<li><h4><a href="<?= RUTA_APP ?>/index.php">Home</a></h4></li>
			<li><h4><a href="<?= RUTA_APP ?>/contenido.php">Search</a></h4></li>
			<li><h4><a href="<?= RUTA_APP ?>/activity.php">Activity</a></h4></li>
			<li><h4><a href="<?= RUTA_APP ?>/profile.php">Profile</a></h4></li>
		<?php endif; ?>
	</ul>
</nav>
