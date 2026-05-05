
<h2>Dashboard</h2>

<p>Bienvenido <?= $_SESSION['user_nombre'] ?></p> 
<p>Tipo de usuario: <?= $_SESSION['user_tipo'] ?></p>

<a href="<?= base_url('logout') ?>">Cerrar sesión</a>