<div style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <h2>Diseñar Nueva Secuencia de Aprendizaje</h2>
    <p>Crea un camino personalizado para tus alumnos.</p>

    <form action="<?= base_url('practica/guardar-secuencia') ?>" method="POST" enctype="multipart/form-data">
        <div style="background: #eee; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
            <label>Título de la Actividad:</label><br>
            <input type="text" name="titulo" placeholder="Ej: Aprendiendo a usar el correo" style="width: 100%; padding: 10px; font-size: 1.2rem;">
        </div>

        <div style="border: 2px solid #007bff; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
            <span style="background: #007bff; color: white; padding: 5px 10px; border-radius: 5px;">PASO 1: Tu Explicación</span>
            <p>Escribe aquí lo que el alumno leerá antes de empezar.</p>
            <textarea name="paso1_texto" rows="5" style="width: 100%;" placeholder="Hola Paco, hoy vamos a practicar..."></textarea>
            
            <div style="margin-top: 15px; border: 2px dashed #007bff; padding: 15px; background: white; border-radius: 10px;">
            <label><strong>📷 Añadir una imagen de apoyo:</strong></label><br>
            <input type="file" name="explicacion_imagen" accept="image/*" style="margin-top: 10px;">
            <p style="font-size: 0.8rem; color: #666;">(Opcional: puedes subir una foto del ratón, teclado, etc.)</p>
        </div>
        </div>

        <div style="border: 2px solid #28a745; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
            <span style="background: #28a745; color: white; padding: 5px 10px; border-radius: 5px;">PASO 2: Práctica con Juego</span>
            <p>Selecciona qué juego base quieres que usen para practicar lo anterior:</p>
            <select name="paso2_juego_id" style="width: 100%; padding: 10px;">
                <?php foreach($juegosDisponibles as $juego): ?>
                    <option value="<?= $juego['id_juego'] ?>"><?= $juego['titulo'] ?></option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label>Puntos necesarios para pasar al siguiente paso:</label>
            <input type="number" name="paso2_meta" value="10" style="width: 60px;">
        </div>

        <div style="border: 2px solid #ffc107; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
            <span style="background: #ffc107; color: black; padding: 5px 10px; border-radius: 5px;">PASO 3: Cierre o Tarea Real</span>
            <p>¿Qué debe hacer el alumno al terminar? (Ej: "Ahora apaga la pantalla")</p>
            <textarea name="paso3_texto" rows="3" style="width: 100%;"></textarea>
        </div>

        <button type="submit" style="background: #007bff; color: white; padding: 20px; width: 100%; border: none; border-radius: 10px; font-size: 1.2rem; cursor: pointer;">
            CREAR ESTA RUTA DE APRENDIZAJE
        </button>
    </form>
</div>