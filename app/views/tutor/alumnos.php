<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">

<div style="max-width: 1000px; margin: 0 auto; padding: 20px; font-family: sans-serif;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h2>Gestión de Alumnos asignados</h2>
        <a href="<?= base_url('dashboard') ?>" style="background: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold;">Volver al Panel</a>
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

    <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px; border-left: 8px solid #ffc107;">
        <h3 style="margin-top: 0; color: #856404;">⏳ Solicitudes de Vinculación Pendientes</h3>
        <p style="color: #666;">Alumnos que han introducido tu código y están esperando que los aceptes para ver sus tareas.</p>

        <?php if (!empty($solicitudes)): ?>
            <div style="display: grid; gap: 15px; margin-top: 20px;">
                <?php foreach ($solicitudes as $sol): ?>
                    <div style="background: #fffdf0; padding: 20px; border-radius: 10px; border: 1px solid #ffeeba; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="font-size: 1.1rem; color: #333;"><?= htmlspecialchars($sol['nombre']) ?></strong> <br>
                            <small style="color: #666;">Email: <?= htmlspecialchars($sol['email']) ?></small> <br>
                            <small style="color: #999;">Recibida el: <?= date('d/m/Y H:i', strtotime($sol['fecha_solicitud'])) ?></small>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <a href="<?= base_url('tutor/procesar_solicitud?id='.$sol['id_solicitud'].'&accion=aceptar') ?>" 
                               style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 0.9rem;">
                                ACEPTAR ALUMNO
                            </a>
                            <a href="<?= base_url('tutor/procesar_solicitud?id='.$sol['id_solicitud'].'&accion=rechazar') ?>" 
                               style="background: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 0.9rem;">
                                RECHAZAR
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: #888; font-style: italic; margin-top: 15px;">No tienes ninguna solicitud de alumno pendiente en este momento.</p>
        <?php endif; ?>
    </div>


    <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 8px solid #28a745;">
        <h3 style="margin-top: 0; color: #1e7e34;">✅ Mis Alumnos Conectados</h3>
        <p style="color: #666;">Alumnos vinculados formalmente contigo. Puedes ver su progreso detallado en las lecciones asignadas.</p>

        <?php if (!empty($alumnosAceptados)): ?>
            <div style="display: grid; gap: 15px; margin-top: 20px;">
                <?php foreach ($alumnosAceptados as $al): ?>
                    <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; border: 1px solid #e9ecef; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="font-size: 1.1rem; color: #333;"><?= htmlspecialchars($al['nombre']) ?></strong> <br>
                            <small style="color: #666;">Email: <?= htmlspecialchars($al['email']) ?></small> <br>
                            <small style="color: #999;">Conectados desde: <?= date('d/m/Y', strtotime($al['fecha_union'])) ?></small>
                        </div>
                        <a href="<?= base_url('tutor/alumno/'.$al['id_usuario']) ?>" 
                           style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 0.9rem;">
                            VER PROGRESO DETALLADO
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p style="color: #888; font-style: italic; margin-top: 15px;">Aún no tienes alumnos vinculados. Comparte tu código para que te envíen solicitudes.</p>
        <?php endif; ?>
    </div>

</div>