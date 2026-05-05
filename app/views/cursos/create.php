<h2>Crear curso</h2>

<form method="POST" action="/cursos/store">
    <input type="text" name="titulo" placeholder="Título" required>
    <textarea name="descripcion" placeholder="Descripción"></textarea>

    <select name="nivel">
        <option value="basico">Básico</option>
        <option value="intermedio">Intermedio</option>
        <option value="avanzado">Avanzado</option>
    </select>

    <button type="submit">Guardar</button>
</form>