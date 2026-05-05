<h2>Login</h2>

<form method="POST" action="<?= base_url('login') ?>">
    <div>
        <input type="email" name="email" placeholder="Email" required>
    </div>
    <div>
        <input type="password" name="password" placeholder="Contraseña" required>
    </div>
    <button type="submit">Entrar</button>
</form>

<p>¿No tienes cuenta? <a href="<?= base_url('register') ?>">Registrarse</a></p>

<!-- error -->
<?php if (!empty($error)): ?>
    <p style="color: red; font-weight: bold;"><?= $error ?></p>
<?php endif; ?>