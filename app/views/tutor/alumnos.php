<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/alumnos.css') ?>">

<div class="contenedor-gestion-alumnos">
    
    <div class="cabecera-gestion-alumnos">
        <h2>Gestión de Alumnos asignados</h2>
        <a href="<?= base_url('dashboard') ?>" class="btn-volver-panel">Volver al Panel</a>
    </div>

    <?php 
        $db = Database::connect();
        $id_tutor = $_SESSION['user_id'];

        $stmtSol = $db->prepare("
            SELECT s.id_solicitud, u.nombre, u.email, s.fecha_solicitud 
            FROM solicitudes_vinculacion s
            JOIN usuarios u ON s.id_alumno = u.id_usuario
            WHERE s.id_tutor = ? AND s.estado = 'pendiente'
            ORDER BY s.fecha_solicitud DESC
        ");
        $stmtSol->execute([$id_tutor]);
        $solicitudes = $stmtSol->fetchAll(PDO::FETCH_ASSOC);

        $stmtVinc = $db->prepare("
            SELECT v.id_vinculacion, u.id_usuario, u.nombre, u.email, v.fecha_union 
            FROM vinculaciones v
            JOIN usuarios u ON v.id_alumno = u.id_usuario
            WHERE v.tutor_id = ?
            ORDER BY u.nombre ASC
        ");
        $stmtVinc->execute([$id_tutor]);
        $alumnosAceptados = $stmtVinc->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="contenedor-solicitudes-pendientes">
        <h3 class="titulo-solicitudes-pendientes">⏳ Solicitudes de Vinculación Pendientes</h3>
        <p class="descripcion-solicitudes-pendientes">Alumnos que han introducido tu código y están esperando que los aceptes para ver sus tareas.</p>

        <?php if (!empty($solicitudes)): ?>
            <div class="grid-solicitudes-pendientes">
                <?php foreach ($solicitudes as $sol): ?>
                    <div class="item-solicitud-pendiente">
                        <div>
                            <strong class="nombre-alumno-solicitud"><?= htmlspecialchars($sol['nombre']) ?></strong> <br>
                            <small class="info-alumno-email">Email: <?= htmlspecialchars($sol['email']) ?></small> <br>
                            <small class="info-alumno-fecha">Recibida el: <?= date('d/m/Y H:i', strtotime($sol['fecha_solicitud'])) ?></small>
                        </div>
                        <div class="acciones-solicitud-pendiente">
                            <a href="<?= base_url('tutor/procesar_solicitud?id='.$sol['id_solicitud'].'&accion=aceptar') ?>" 
                               class="btn-aceptar-alumno">
                                ACEPTAR ALUMNO
                            </a>
                            <a href="<?= base_url('tutor/procesar_solicitud?id='.$sol['id_solicitud'].'&accion=rechazar') ?>" 
                               class="btn-rechazar-alumno">
                                RECHAZAR
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="texto-solicitudes-vacio">No tienes ninguna solicitud de alumno pendiente en este momento.</p>
        <?php endif; ?>
    </div>


<div class="contenedor-mis-alumnos-box">
    <h3 class="titulo-mis-alumnos">✅ Mis Alumnos:</h3>
    <p class="descripcion-mis-alumnos">Alumnos vinculados formalmente contigo. Puedes ver su progreso detallado en las lecciones asignadas.</p>

    <?php if (!empty($alumnosAceptados)): ?>
        <div class="grid-mis-alumnos">
            <?php foreach ($alumnosAceptados as $al): ?>
                <div class="item-alumno-vinculado">
                    <div>
                        <strong class="nombre-alumno-vinculado"><?= htmlspecialchars($al['nombre']) ?></strong> <br>
                        <small class="info-alumno-email">Email: <?= htmlspecialchars($al['email']) ?></small> <br>
                        <small class="info-alumno-fecha">Conectados desde: <?= date('d/m/Y', strtotime($al['fecha_union'])) ?></small>
                    </div>
                    <div class="acciones-alumno-vinculado">
                        <a href="<?= base_url('tutor/alumno/'.$al['id_usuario']) ?>" class="btn-progreso-detallado-nuevo">
                            VER PROGRESO DETALLADO
                        </a>
                        <button type="button" class="btn-desasociar-alumno" onclick="confirmarDesasociacion(<?= $al['id_usuario'] ?>, '<?= htmlspecialchars($al['nombre'], ENT_QUOTES) ?>')">
                            DESASOCIAR
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="texto-alumnos-vacio">Aún no tienes alumnos vinculados. Comparte tu código para que te envíen solicitudes.</p>
    <?php endif; ?>
</div>

<div id="modal-confirmar-desasociar" class="modal-personalizado-backdrop" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; justify-content: center; align-items: center;">
    <div class="modal-personalizado-contenido">
        <h3 class="modal-titulo-historial text-danger-modal">⚠️ Confirmar Desasociación</h3>
        <p id="texto-confirmacion-modal" class="texto-confirmacion-modal-body"></p>
        
        <form id="form-desasociar-alumno" action="<?= base_url('tutor/desasociar-alumno') ?>" method="POST">
            <input type="hidden" name="id_alumno" id="modal-id-alumno-input" value="">
            
            <div class="modal-footer-acciones">
                <button type="button" onclick="cerrarModalDesasociar()" class="btn-control-size-a btn-cancelar-modal-link">Cancelar</button>
                <button type="submit" class="btn-cerrar-modal-tareas btn-confirmar-modal-space">Sí, Desasociar</button>
            </div>
        </form>
    </div>
</div>

</div>
<script src="<?= base_url('js/vistas/alumnos.js') ?>"></script>