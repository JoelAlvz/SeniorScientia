<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<h2 style="text-align: center; font-size: 2.5rem; margin-bottom: 5px;">Zona de Juegos y Práctica</h2>
<p style="text-align: center; color: #64748b; margin-bottom: 30px;">Compite con tus compañeros de clase logrando las mejores puntuaciones.</p>

<div class="contenedor-practicas" style="padding: 20px; max-width: 1200px; margin: 0 auto; font-family: sans-serif;">

    <?php if (isset($_GET['record']) && $_GET['record'] == '1'): ?>
    <div id="aviso-felicitacion-record" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 25px; border-radius: 16px; text-align: center; margin: 20px auto; max-width: 600px; box-shadow: 0 8px 20px rgba(40,167,69,0.3); border: 3px solid white; animation: popIn 0.5s ease-out-back;">
        <span style="font-size: 3.5rem; display: block; margin-bottom: 5px;">🎉 🏆 🎉</span>
        <h2 style="margin: 0 0 10px 0; font-size: 2rem; font-weight: bold; text-shadow: 1px 1px 3px rgba(0,0,0,0.2);">
            ¡Enhorabuena!
        </h2>
        <p style="font-size: 1.3rem; margin: 0; opacity: 0.95; line-height: 1.4;">
            Has superado tu marca anterior. ¡Acabas de conseguir un <strong>nuevo récord personal</strong> en el juego! Sigue practicando así de bien.
        </p>
        <button onclick="document.getElementById('aviso-felicitacion-record').style.display='none';" style="margin-top: 15px; background: white; color: #28a745; border: none; padding: 8px 20px; font-weight: bold; border-radius: 8px; cursor: pointer; font-size: 1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            Entendido
        </button>
    </div>

    <style>
        @keyframes popIn {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
    <?php endif; ?>

    <div style="background: #ffffff; border: 2px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 40px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0; color: #1e293b; font-size: 1.4rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
            🏆 Tabla de Clasificación del Aula
        </h3>
        
        <form method="GET" action="" style="margin: 15px 0; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <label for="select-juego" style="font-weight: bold; color: #475569;">Ver posiciones del juego:</label>
            <select id="select-juego" name="juego" onchange="this.form.submit()" style="padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; font-size: 1rem; cursor: pointer; min-width: 250px;">
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
                <div style="overflow-x: auto; margin-top: 15px;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="background: #f1f5f9; color: #475569; font-weight: bold;">
                                <th style="padding: 12px; border-radius: 8px 0 0 8px;">Puesto</th>
                                <th style="padding: 12px;">Alumno</th>
                                <th style="padding: 12px;">Puntuación Máxima</th>
                                <th style="padding: 12px; border-radius: 0 8px 8px 0;">Fecha del Récord</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $medallas = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣'];
                            foreach ($ranking as $index => $fila): 
                            ?>
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.backgroundColor='#f8fafc'" onmouseout="this.style.backgroundColor='transparent'">
                                    <td style="padding: 12px; font-size: 1.2rem; font-weight: bold;">
                                        <?= isset($medallas[$index]) ? $medallas[$index] : ($index + 1) ?>
                                    </td>
                                    <td style="padding: 12px; font-weight: 600; color: #334155;">
                                        <?= htmlspecialchars($fila['nombre']) ?>
                                    </td>
                                    <td style="padding: 12px; color: #16a34a; font-weight: bold; font-size: 1.1rem;">
                                        <?= $fila['puntuacion_maxima'] ?> pts
                                    </td>
                                    <td style="padding: 12px; color: #64748b; font-size: 0.9rem;">
                                        <?= date('d/m/Y H:i', strtotime($fila['fecha_registro'])) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: #64748b; background: #f8fafc; padding: 15px; border-radius: 8px; text-align: center; margin-top: 15px; border: 1px dashed #cbd5e1;">
                    💡 Nadie de tu clase ha registrado puntuaciones en este juego todavía. ¡Sé el primero en liderar la tabla!
                </p>
            <?php endif; ?>
        <?php else: ?>
            <p style="color: #475569; background: #eff6ff; padding: 15px; border-radius: 8px; text-align: center; margin-top: 15px; border: 1px solid #bfdbfe;">
                👆 Por favor, selecciona un juego de la lista desplegable de arriba para cargar el marcador del grupo.
            </p>
        <?php endif; ?>
    </div>


    <?php foreach ($categorias as $nombreCat => $listaJuegos): ?>
        <?php if (!empty($listaJuegos)): ?>
            <section style="margin-bottom: 40px;">
                <h3 style="text-transform: capitalize; border-bottom: 3px solid #007bff; display: inline-block; color: #1e293b; padding-bottom: 5px; margin-bottom: 20px;">
                    Juegos de <?= $nombreCat ?>
                </h3>
                
                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <?php foreach ($listaJuegos as $juego): ?>
                        <div class="card-juego" style="width: 250px; border: 2px solid #e2e8f0; border-radius: 15px; padding: 15px; text-align: center; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <img src="<?= base_url($juego['imagen_url'] ?: 'img/default_juego.png') ?>" style="width: 100%; border-radius: 10px; height: 140px; object-fit: cover;">
                                <h4 style="margin: 15px 0 10px; color: #334155; font-size: 1.1rem;"><?= $juego['titulo'] ?></h4>
                                <p style="font-size: 0.9rem; color: #64748b; line-height: 1.4; margin-bottom: 15px;"><?= $juego['descripcion'] ?></p>
                            </div>
                            <a href="<?= base_url($juego['ruta_url']) ?>?id_juego=<?= $juego['id_juego'] ?>" 
                               style="display: block; background: #28a745; color: white; padding: 10px; text-decoration: none; border-radius: 8px; font-weight: bold; transition: background 0.2s;"
                               onmouseover="this.style.background='#218838'" onmouseout="this.style.background='#28a745'">
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