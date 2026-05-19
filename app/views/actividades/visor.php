<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">

<div style="max-width: 800px; margin: 40px auto; padding: 30px; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center;">

    <?php if ($paso['tipo'] === 'explicacion'): ?>
        <h2 style="color: #007bff;">Antes de empezar...</h2>
        
        <?php if (!empty($paso['imagen_guia'])): ?>
            <div style="margin: 25px auto; max-width: 90%;">
                <img src="<?= base_url('uploads/' . $paso['imagen_guia']) ?>" 
                     alt="Imagen de apoyo para la explicación" 
                     style="max-width: 100%; height: auto; border-radius: 15px; border: 4px solid #f0f0f0; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            </div>
        <?php endif; ?>

        <div style="font-size: 1.5rem; line-height: 1.6; margin: 30px 0; color: #333;">
            <?= nl2br($paso['contenido']) ?>
        </div>
        
        <a href="<?= base_url('actividad/avanzar/'.$id_actividad) ?>" 
           style="display: inline-block; background: #28a745; color: white; padding: 20px 40px; font-size: 1.3rem; border-radius: 15px; text-decoration: none; font-weight: bold;">
            LO HE LEÍDO, ¡VAMOS!
        </a>

    <?php elseif ($paso['tipo'] === 'juego'): ?>
        <h2 style="color: #28a745;">¡Hora de practicar!</h2>
        <p style="font-size: 1.2rem;">Debes conseguir al menos <strong><?= $paso['puntos_necesarios'] ?> puntos</strong>.</p>
        
        <div style="border: 5px solid #eee; border-radius: 15px; overflow: hidden; margin: 20px 0;">
            <p>Juego seleccionado: <strong><?= $juego['titulo'] ?></strong></p>
            <a href="<?= base_url($juego['ruta_url'] . '?actividad=' . $id_actividad . '&meta=' . $paso['puntos_necesarios']) ?>" 
               style="display: inline-block; background: #007bff; color: white; padding: 15px 30px; border-radius: 10px; text-decoration: none;">
                PULSA AQUÍ PARA EMPEZAR EL JUEGO
            </a>
        </div>

    <?php elseif ($paso['tipo'] === 'externa'): ?>
        <h2 style="color: #ffc107;">Tarea finalizada</h2>
        <div style="font-size: 1.4rem; margin: 30px 0;">
            <p>¡Excelente trabajo! Por último, tu tutor te pide esto:</p>
            <div style="background: #fff3cd; padding: 20px; border-radius: 10px; border: 1px dashed #e67e22;">
                <?= nl2br($paso['contenido']) ?>
            </div>
        </div>
        <a href="<?= base_url('actividad/avanzar/'.$id_actividad) ?>" 
           style="display: inline-block; background: #6c757d; color: white; padding: 20px 40px; border-radius: 15px; text-decoration: none;">
            TERMINAR TODO
        </a>
    <?php endif; ?>

</div>

<script src="<?= base_url('js/accesibilidad.js') ?>"></script>