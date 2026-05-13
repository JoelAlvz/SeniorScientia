<div style="max-width: 600px; margin: 0 auto; padding: 20px; background: white; border-radius: 15px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <h2>Añadir Nuevo Juego o Práctica</h2>
    <form action="<?= base_url('practica/store') ?>" method="POST">
        <div style="margin-bottom: 15px;">
            <label>Título del Juego:</label><br>
            <input type="text" name="titulo" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label>Categoría:</label><br>
            <select name="categoria" style="width: 100%; padding: 10px; border-radius: 5px;">
                <option value="raton">Ratón</option>
                <option value="teclado">Teclado</option>
                <option value="internet">Internet</option>
                <option value="otros">Otros</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label>Descripción corta:</label><br>
            <textarea name="descripcion" style="width: 100%; padding: 10px; border-radius: 5px;" rows="3"></textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <label>URL o Ruta del juego:</label><br>
            <input type="text" name="ruta_url" placeholder="ejemplo: practica/teclado-letras" required style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
        </div>

        <button type="submit" style="background: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 10px; cursor: pointer; width: 100%; font-size: 1.1rem;">
            GUARDAR Y PUBLICAR
        </button>
    </form>
</div>