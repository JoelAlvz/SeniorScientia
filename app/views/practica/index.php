<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/zona_juegos.css') ?>">

<div class="zona-juegos-top-bar">
    <div class="zona-juegos-title-container">
        <h2>Zona de Juegos y Práctica</h2>
        <p>Compite con tus compañeros de clase logrando las mejores puntuaciones.</p>
    </div>
    
    <a href="<?= base_url('dashboard') ?>" class="btn-volver-dashboard">
       VOLVER AL PANEL
    </a>
</div>

<div class="contenedor-practicas">

    <?php if (isset($_GET['record']) && $_GET['record'] == '1'): ?>
    <div id="aviso-felicitacion-record" class="banner-record">
        <span class="banner-record-emojis">🎉 🏆 🎉</span>
        <h2>¡Enhorabuena!</h2>
        <p>
            Has superado tu marca anterior. ¡Acabas de conseguir un <strong>nuevo récord personal</strong> en el juego! Sigue practicando así de bien.
        </p>
        <button onclick="document.getElementById('aviso-felicitacion-record').style.display='none';" class="btn-cerrar-banner">
            Entendido
        </button>
    </div>
    <?php endif; ?>

    <div class="bloque-clasificacion">
        <h3>🏆 Tabla de Clasificación del Aula</h3>
        
        <form method="GET" action="" class="filtro-ranking-form">
            <label for="select-juego">Ver posiciones del juego:</label>
            <select id="select-juego" name="juego" onchange="this.form.submit()">
                <option value="">-- Selecciona un juego para ver el ranking --</option>
                <?php foreach ($categorias as $nombreCat => $listaJuegos): ?>
                    <?php foreach ($listaJuegos as $juego): ?>
                        <option value="<?= $juego['id_juego'] ?>" <?= (isset($juego_seleccionado) && $juego_seleccionado == $juego['id_juego']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($juego['titulo']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if (!empty($juego_seleccionado)): ?>
            <?php if (!empty($ranking)): ?>
                <div class="tabla-responsiva-wrapper">
                    <table class="tabla-clasificacion">
                        <thead>
                            <tr>
                                <th>Puesto</th>
                                <th>Alumno</th>
                                <th>Puntuación Máxima</th>
                                <th>Fecha del Récord</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $medallas = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣'];
                            foreach ($ranking as $index => $fila): 
                            ?>
                                <tr>
                                    <td class="col-puesto">
                                        <?= isset($medallas[$index]) ? $medallas[$index] : ($index + 1) ?>
                                    </td>
                                    <td class="col-alumno">
                                        <?= htmlspecialchars($fila['nombre']) ?>
                                    </td>
                                    <td class="col-puntos">
                                        <?= $fila['puntuacion_maxima'] ?> pts
                                    </td>
                                    <td class="col-fecha">
                                        <?= date('d/m/Y H:i', strtotime($fila['fecha_registro'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="msg-tabla-vacia">
                    💡 Nadie de tu clase ha registrado puntuaciones en este juego todavía. ¡Sé el primero en liderar la tabla!
                </p>
            <?php endif; ?>
        <?php else: ?>
            <p class="msg-tabla-espera">
                👆 Por favor, selecciona un juego de la lista desplegable de arriba para cargar el marcador del grupo.
            </p>
        <?php endif; ?>
    </div>

    <?php foreach ($categorias as $nombreCat => $listaJuegos): ?>
        <?php if (!empty($listaJuegos)): ?>
            <section class="seccion-categoria-juegos">
                <h3>Juegos de <?= $nombreCat ?></h3>
                
                <div class="grid-juegos-categoria">
                    <?php foreach ($listaJuegos as $juego): ?>
                        <div class="card-juego">
                            <div>
                                <img src="<?= base_url($juego['imagen_url'] ? 'img/' . $juego['imagen_url'] : 'img/default_juego.png') ?>" class="card-juego-img">
                                <h4><?= htmlspecialchars($juego['titulo']) ?></h4>
                                <p class="card-juego-desc"><?= htmlspecialchars($juego['descripcion']) ?></p>
                            </div>
                            <a href="<?= base_url($juego['ruta_url']) ?>?id_juego=<?= $juego['id_juego'] ?>" class="btn-jugar-juego">
                                ¡JUGAR!
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<script src="<?= base_url('js/accesibilidad.js') ?>"></script>