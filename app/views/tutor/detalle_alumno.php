<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/detalle_alumno.css') ?>">

<div class="detalle-alumno-container">
    
    
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alerta-exito-flash">
            ✅ <?= $_SESSION['flash_success']; ?>
        </div>
        <?php 
        unset($_SESSION['flash_success']); 
        ?>
    <?php endif; ?>

    <div class="perfil-alumno-cabecera">
        <div class="perfil-avatar">👤</div>
        <div class="perfil-info">
            <h2><?= htmlspecialchars($alumno['nombre']) ?></h2>
            <p><?= htmlspecialchars($alumno['email']) ?></p>
        </div>
        <a href="<?= base_url('dashboard') ?>" class="btn-volver-panel">Volver al Panel</a>
    </div>

    <div class="card-nota-aliento-envio">
        <h3>✍️ Enviar Nota de Aliento</h3>
        <p>Escribe un mensaje motivador que verá el alumno en grande cuando entre a su panel de control.</p>
        
        <form action="<?= base_url('tutor/guardar-mensaje') ?>" method="POST" class="form-nota-aliento">
            <input type="hidden" name="id_alumno" value="<?= $alumno['id_usuario'] ?>">
            
            <textarea name="mensaje_nota" rows="3" placeholder="Ejemplo: ¡Hola Juan! He visto que has ganado dos medallas nuevas hoy. ¡Eres un campeón, sigue así! 🌟" required class="textarea-nota-aliento"></textarea>
            
            <button type="submit" class="btn-enviar-nota">
                🚀 ENVIAR NOTA AL ALUMNO
            </button>
        </form>
    </div>
        
    <hr class="separador-seccion">
    
    <h3>Lecciones Guiadas</h3>
    <?php if (empty($lecciones)): ?>
        <p class="texto-lecciones-vacio">No hay lecciones personalizadas iniciadas.</p>
    <?php else: ?>
        <div class="grid-lecciones-progreso">
            <?php foreach ($lecciones as $l): ?>
                <div class="item-leccion-progreso">
                    <div class="leccion-info-izq">
                        <strong><?= htmlspecialchars($l['titulo']) ?></strong>
                        <small>Último avance: <?= date('d/m H:i', strtotime($l['fecha_actualizacion'])) ?></small>
                    </div>
                    <div class="leccion-progreso-der">
                        <div class="estado-porcentaje-texto">
                            <?= $l['completada'] ? '¡COMPLETADA! ✅' : 'En progreso (' . $l['ultimo_paso_completado'] . '/' . $l['total_pasos'] . ')' ?>
                        </div>
                        <div class="barra-progreso-fondo">
                            <?php $porcentaje = $l['total_pasos'] > 0 ? ($l['ultimo_paso_completado'] / $l['total_pasos']) * 100 : 0; ?>
                            <div class="barra-progreso-relleno" style="width: <?= $porcentaje ?>%;"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>