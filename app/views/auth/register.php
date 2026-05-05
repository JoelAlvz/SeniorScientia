<h2>Registro</h2>

<form action="/proyectoFinCurso/MayoresApp/public/register" method="POST">
    <input type="text" name="nombre" placeholder="Nombre completo" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    
    <label for="tipo">Regístrate como:</label>
    <select name="tipo" id="tipo" required>
        <option value="alumno">Alumno</option>
        <option value="tutor">Tutor</option>
    </select>

    <button type="submit">Crear cuenta</button>
</form>
<a href="<?= base_url('login') ?>">Login</a>
