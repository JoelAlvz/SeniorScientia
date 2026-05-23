<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/crear_secuencia.css') ?>">

<div class="secuencia-container">
    <div class="secuencia-cabecera-flex">
        <div class="secuencia-titulos">
            <h2>Diseñar Secuencia de Aprendizaje </h2>
            <p>Construye un camino a la medida de tus alumnos combinando las piezas como quieras.</p>
        </div>
        <a href="<?= base_url('dashboard') ?>" class="btn-volver-panel">Volver al Panel</a>
    </div>

    <form action="<?= base_url('practica/guardar-secuencia') ?>" method="POST" enctype="multipart/form-data">
        
        <div class="card-cabecera-secuencia">
            <div class="campo-grupo">
                <label class="label-bold">Título de la Actividad:</label>
                <input type="text" name="titulo" required placeholder="Ej: Curso rápido de manejo del Ratón" maxlength="50" class="input-titulo-secuencia">
            </div>

            <div class="columnas-seleccion-secuencia">
                <div class="campo-grupo">
                    <label class="label-bold">¿Para quién es?</label>
                    <select name="id_alumno" class="select-secuencia">
                        <option value="">🌍 Todos los alumnos (Pública)</option>
                        <?php foreach($misAlumnos as $al): ?>
                            <option value="<?= $al['id_usuario'] ?>">👤 Solo para: <?= htmlspecialchars($al['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="campo-grupo">
                    <label class="label-bold">Área de competencia:</label>
                    <select name="categoria" class="select-secuencia">
                        <option value="raton">🖱️ Destreza con el Ratón</option>
                        <option value="teclado">⌨️ Mecanografía y Teclado</option>
                        <option value="internet">🌐 Navegación General</option>
                    </select>
                </div>
            </div>
        </div>

        <div id="contenedor-pasos"></div>

        <div class="grid-botones-creadores">
            <button type="button" onclick="agregarPaso('texto')" class="btn-crear-paso btn-crear-texto">
                ➕ Añadir Explicación Escrita
            </button>
            <button type="button" onclick="agregarPaso('multimedia')" class="btn-crear-paso btn-crear-multimedia">
                ➕ Añadir Vídeo o Imagen
            </button>
            <button type="button" onclick="agregarPaso('juego')" class="btn-crear-paso btn-crear-juego">
                ➕ Añadir Juego Práctico
            </button>
        </div>

        <button type="submit" class="btn-guardar-secuencia">
            💾 GUARDAR Y ENVIAR RUTA COMPLETA
        </button>
    </form>
</div>
<script>
    const juegosDisponibles = <?= json_encode($juegosDisponibles) ?>;
</script>

<script src="<?= base_url('js/vistas/crear_secuencia.js') ?>"></script>