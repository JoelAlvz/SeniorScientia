<div style="max-width: 900px; margin: 0 auto; padding: 20px; font-family: sans-serif;">
    <a href="<?= base_url('dashboard') ?>" style="text-decoration: none; color: #007bff;">← Volver al Panel</a>
    
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div style="background: #d4edda; color: #155724; border: 2px solid #c3e6cb; padding: 15px 20px; border-radius: 10px; margin-top: 15px; font-weight: bold; display: flex; align-items: center; gap: 10px;">
            ✅ <?= $_SESSION['flash_success']; ?>
        </div>
        <?php 
        unset($_SESSION['flash_success']); 
        ?>
    <?php endif; ?>

    <div style="display: flex; align-items: center; gap: 20px; margin-top: 20px;">
        <div style="background: #ddd; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">👤</div>
        <div>
            <h2 style="margin: 0;"><?= $alumno['nombre'] ?></h2>
            <p style="margin: 5px 0; color: #666;"><?= $alumno['email'] ?></p>
        </div>
    </div>

    <div style="background: white; padding: 25px; border-radius: 15px; border: 2px solid #ced4da; margin-top: 30px;">
        <h3>✍️ Enviar Nota de Aliento</h3>
        <p>Escribe un mensaje motivador que verá el alumno en grande cuando entre a su panel de control.</p>
        
        <form action="<?= base_url('tutor/guardar-mensaje') ?>" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
            <input type="hidden" name="id_alumno" value="<?= $alumno['id_usuario'] ?>">
            
            <textarea name="mensaje_nota" rows="3" placeholder="Ejemplo: ¡Hola Juan! He visto que has ganado dos medallas nuevas hoy. ¡Eres un campeón, sigue así! 🌟" required 
                style="width: 100%; padding: 15px; font-size: 1.1rem; border-radius: 10px; border: 1px solid #ccc; font-family: sans-serif; resize: vertical;"></textarea>
            
            <button type="submit" style="background: #ffc107; color: #212529; padding: 12px 25px; font-size: 1.1rem; font-weight: bold; border: none; border-radius: 10px; cursor: pointer; align-self: flex-start; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                🚀 ENVIAR NOTA AL ALUMNO
            </button>
        </form>
    </div>
        
    <hr style="margin: 30px 0; border: 1px solid #eee;">
    <h3>Lecciones Guiadas</h3>
    <?php if (empty($lecciones)): ?>
        <p style="color: #999; font-style: italic;">No hay lecciones personalizadas iniciadas.</p>
    <?php else: ?>
        <div style="display: grid; gap: 10px; margin-bottom: 30px;">
            <?php foreach ($lecciones as $l): ?>
                <div style="background: #fdfdfd; border-left: 5px solid #28a745; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <strong style="font-size: 1.1rem;"><?= htmlspecialchars($l['titulo']) ?></strong><br>
                        <small style="color: #666;">Último avance: <?= date('d/m H:i', strtotime($l['fecha_actualizacion'])) ?></small>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-weight: bold; color: #28a745;">
                            <?= $l['completada'] ? '¡COMPLETADA! ✅' : 'En progreso (' . $l['ultimo_paso_completado'] . '/' . $l['total_pasos'] . ')' ?>
                        </div>
                        <div style="width: 150px; background: #eee; height: 8px; border-radius: 4px; margin-top: 5px; overflow: hidden;">
                            <?php $porcentaje = $l['total_pasos'] > 0 ? ($l['ultimo_paso_completado'] / $l['total_pasos']) * 100 : 0; ?>
                            <div style="width: <?= $porcentaje ?>%; background: #28a745; height: 100%;"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 40px 0;">
    <h3>Historial de Actividades</h3>

    <?php if (empty($progresos)): ?>
        <p style="background: #fff3cd; padding: 20px; border-radius: 10px; color: #856404;">
            Este alumno aún no ha realizado ninguna práctica.
        </p>
    <?php else: ?>
        <div style="display: grid; gap: 15px;">
            <?php foreach ($progresos as $p): ?>
                <?php 
                    $extra = json_decode($p['datos_extra'], true); 
                ?>
                <div style="background: white; border: 1px solid #ddd; padding: 20px; border-radius: 15px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <div>
                        <span style="background: #e7f3ff; color: #007bff; padding: 5px 10px; border-radius: 5px; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">
                            <?= str_replace('-', ' ', $p['actividad_slug']) ?>
                        </span>
                        <h4 style="margin: 10px 0 5px;"><?= $p['puntos'] ?> Puntos conseguidos</h4>
                        <small style="color: #999;"><?= date('d/m/Y H:i', strtotime($p['fecha'])) ?></small>
                    </div>

                    <div style="text-align: right; min-width: 200px; border-left: 1px solid #eee; padding-left: 20px;">
                        <p style="margin: 0; font-size: 0.9rem; color: #444;"><strong>Detalles técnicos:</strong></p>
                        <?php if ($extra): ?>
                            <ul style="margin: 5px 0; padding: 0; list-style: none; font-size: 0.85rem; color: #666;">
                                <?php foreach ($extra as $key => $val): ?>
                                    <li><?= ucfirst($key) ?>: <?= $val ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p style="font-size: 0.8rem; color: #bbb;">Sin datos extra</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>