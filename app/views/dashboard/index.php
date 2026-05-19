<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<script src="<?= base_url('js/accesibilidad.js') ?>"></script>

<h2>Panel de Control</h2>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div style="background: #f8d7da; color: #721c24; border: 2px solid #f5c6cb; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: bold;">
        <?= $_SESSION['flash_error']; ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<p>Hola, <strong><?= $_SESSION['user_nombre'] ?></strong>. ¿Qué quieres hacer hoy?</p>

<?php 
        $db = Database::connect();
        // Buscamos el último mensaje que el tutor asociado le haya dejado a este alumno
        $stmt = $db->prepare("
            SELECT m.*, u.nombre as nombre_tutor 
            FROM mensajes_tutores m
            JOIN usuarios u ON m.id_tutor = u.id_usuario
            WHERE m.id_alumno = ?
            ORDER BY m.fecha_envio DESC 
            LIMIT 1
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $nota_tutor = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>

        <?php if ($nota_tutor): ?>
            <div style="margin-top: 20px; background: #fffdf0; padding: 25px; border-radius: 20px; border-left: 10px solid #ffc107; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; gap: 20px; align-items: center;">
                <div style="font-size: 3.5rem;">✉️</div>
                <div style="flex: 1;">
                    <h4 style="margin: 0 0 5px 0; color: #856404; font-size: 1.1rem;">
                        Nota de aliento de tu tutor(a) <strong><?= htmlspecialchars($nota_tutor['nombre_tutor']) ?></strong>:
                    </h4>
                    <p style="margin: 0; font-size: 1.3rem; font-style: italic; color: #333; font-weight: 500;">
                        "<?= htmlspecialchars($nota_tutor['mensaje']) ?>"
                    </p>
                    <small style="display: block; margin-top: 10px; color: #777;">
                        Recibido el <?= date('d/m/Y a las H:i', strtotime($nota_tutor['fecha_envio'])) ?>
                    </small>
                </div>
            </div>
 <?php endif; ?>

<div class="menu-container">

    <?php if ($_SESSION['user_tipo'] === 'alumno'): ?>
        <div class="card alumno">
            <h3>Mis Clases</h3>
            <p>Aprende a usar tu ordenador paso a paso.</p>
            <a href="<?= base_url('cursos') ?>" class="btn-grande">EMPEZAR A APRENDER</a>
        </div>

        <div class="card alumno">
            <h3>Practicar</h3>
            <p>Juegos para mejorar con el ratón y el teclado.</p>
            <a href="<?= base_url('practica') ?>" class="btn-grande">PRACTICAR AHORA</a>
        </div>
        
        <div style="margin-top: 30px; background: #fff; padding: 20px; border-radius: 20px; border: 2px solid #FFD700;">
            <h3 style="margin-top: 0; color: #b8860b;">🏅 Mis Medallas de Oro</h3>
            
            <?php 
            $db = Database::connect();
            $stmt = $db->prepare("
                SELECT a.titulo 
                FROM logros_alumnos l 
                JOIN actividades_personalizadas a ON l.id_actividad = a.id_actividad 
                WHERE l.id_usuario = ?
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $medallas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                <?php if ($medallas): ?>
                    <?php foreach ($medallas as $m): ?>
                        <div style="text-align: center; width: 100px;">
                            <div style="font-size: 3rem; background: #fff9e6; border-radius: 50%; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; margin: 0 auto; border: 3px solid #FFD700; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                🥇
                            </div>
                            <p style="font-size: 0.8rem; font-weight: bold; margin-top: 10px; color: #555;">
                                <?= htmlspecialchars($m['titulo']) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: #999; font-style: italic;">¡Completa tu primera lección para ganar una medalla!</p>
                <?php endif; ?>
            </div>
        </div>
        <div style="margin-top: 30px;">
            <h3>Tareas de mi Tutor</h3>
            <?php 
            $db = Database::connect();
            
            $stmtCheck = $db->prepare('SELECT tutor_id FROM usuarios WHERE id_usuario = ?');
            $stmtCheck->execute([$_SESSION['user_id']]);
            $perfilAlumno = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            $rutas = [];
            if (!empty($perfilAlumno['tutor_id'])) {
                $stmt = $db->prepare('
                    SELECT a.* FROM actividades_personalizadas a 
                    JOIN usuarios u ON u.tutor_id = a.tutor_id 
                    LEFT JOIN progreso_pasos p ON p.id_actividad = a.id_actividad AND p.id_usuario = u.id_usuario
                    WHERE u.id_usuario = ? 
                      AND (p.completada IS NULL OR p.completada = 0)
                ');
                $stmt->execute([$_SESSION['user_id']]);
                $rutas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            ?>

            <?php if (empty($perfilAlumno['tutor_id'])): ?>
                <div style="background: #e2f0d9; padding: 20px; border-radius: 15px; border-left: 8px solid #385723; color: #385723;">
                    <p style="margin: 0; font-size: 1.1rem; font-weight: bold;">👋 ¡Hola! Para empezar a recibir tareas personalizadas, necesitas conectarte con un tutor.</p>
                    <p style="margin: 10px 0 0 0; font-size: 1rem; color: #595959;">Pídele el código de conexión a tu tutor o profesor e introdúcelo en la sección de vinculación de aquí abajo.</p>
                </div>

            <?php elseif ($rutas): ?>
                <div style="display: grid; gap: 15px;">
                    <?php foreach ($rutas as $ruta): ?>
                        <div style="background: white; padding: 20px; border-radius: 15px; border-left: 10px solid #007bff; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="margin: 0;"><?= htmlspecialchars($ruta['titulo']) ?></h4>
                                <small>Creado el: <?= date('d/m/Y', strtotime($ruta['fecha_creacion'])) ?></small>
                            </div>
                            <a href="<?= base_url('actividad/ver/' . $ruta['id_actividad']) ?>" 
                            style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 10px; font-weight: bold;">
                            EMPEZAR LECCIÓN
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div style="background: #fffdf0; padding: 20px; border-radius: 15px; border-left: 8px solid #ffc107; color: #856404;">
                    <p style="margin: 0; font-size: 1.1rem; font-weight: bold;">🎉 ¡Buen trabajo! No tienes tareas pendientes de tu tutor por ahora.</p>
                    <p style="margin: 5px 0 0 0; font-size: 1rem; color: #666;">Has completado con éxito todo lo que tenías asignado. ¡Sigue así!</p>
                </div>
            <?php endif; ?>
        </div>
        <div style="background: #e7f3ff; padding: 20px; border-radius: 15px; margin-top: 20px; border: 2px solid #b2d7ff;">
            <h3>¿Tienes a alguien que te ayuda?</h3>
            <p>Si tu hijo, nieto o tutor tiene un código, escríbelo aquí para que pueda ver tus progresos:</p>
            
            <form action="<?= base_url('perfil/vincular') ?>" method="POST" style="display: flex; gap: 10px; align-items: center;">
                <input type="number" name="codigo_tutor" placeholder="Ejemplo: 12" required 
                    style="padding: 15px; font-size: 1.2rem; width: auto; border-radius: 10px; border: 1px solid #ccc;">
                <button type="submit" style="padding: 15px 25px; font-size: 1.1rem; background: #007bff; color: white; border: none; border-radius: 10px; cursor: pointer;">
                    CONECTAR CON MI TUTOR
                </button>
            </form>
            
            <?php if (isset($_GET['error']) && $_GET['error'] === 'codigo_invalido'): ?>
                <p style="color: red; font-weight: bold;">Ese código no es válido. Por favor, pídele el número correcto a tu tutor.</p>
            <?php endif; ?>
        </div>

     <button id="btn-accesibilidad" style="position: fixed; bottom: 20px; right: 20px; background: #007bff; color: white; border: none; border-radius: 50%; width: 60px; height: 60px; font-size: 1.8rem; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 9999; display: flex; align-items: center; justify-content: center;">
        👁️
    </button>

    <div id="menu-accesibilidad" style="display: none; position: fixed; bottom: 90px; right: 20px; background: white; border: 2px solid #007bff; padding: 20px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); z-index: 9999; width: 250px; font-family: sans-serif;">
        <h4 style="margin: 0 0 15px 0; color: #333; text-align: center; font-size: 1.2rem;">Opciones de Vista</h4>
        
        <p style="margin: 5px 0; font-weight: bold; color: #555;">Tamaño del texto:</p>
        <div style="display: flex; gap: 5px; margin-bottom: 15px;">
            <button onclick="cambiarTexto('normal')" style="flex: 1; padding: 10px; font-size: 1rem; cursor: pointer; background: #eee; border: 1px solid #ccc; border-radius: 5px;">A</button>
            <button onclick="cambiarTexto('grande')" style="flex: 1; padding: 10px; font-size: 1.3rem; cursor: pointer; background: #eee; border: 1px solid #ccc; border-radius: 5px; font-weight: bold;">A+</button>
            <button onclick="cambiarTexto('gigante')" style="flex: 1; padding: 10px; font-size: 1.6rem; cursor: pointer; background: #eee; border: 1px solid #ccc; border-radius: 5px; font-weight: bold;">A++</button>
        </div>

        <p style="margin: 5px 0; font-weight: bold; color: #555;">Colores de la pantalla:</p>
        <button id="btn-contraste" onclick="alternarContraste()" style="width: 100%; padding: 12px; background: #333; color: #fff; font-weight: bold; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem; margin-bottom: 15px;">
            🌓 Alto Contraste
        </button>

        <p style="margin: 5px 0; font-weight: bold; color: #555;">Ayuda para el ratón:</p>
        <button id="btn-cursor" onclick="alternarCursorEspecial()" style="width: 100%; padding: 12px; background: #ff5722; color: #fff; font-weight: bold; border: none; border-radius: 5px; cursor: pointer; font-size: 1rem;">
            🎯 Resaltar Ratón
        </button>
    </div>


    <?php elseif ($_SESSION['user_tipo'] === 'tutor'): ?>
        <div class="card tutor">
            <h3>Gestión de Alumnos</h3>
            <p>Mira cómo van tus alumnos y ayúdales.</p>
            <a href="<?= base_url('admin/alumnos') ?>" class="btn-grande">VER MIS ALUMNOS</a>
        </div>

        <div class="card tutor">
            <h3>Crear Contenido</h3>
            <p>Diseña lecciones personalizadas con explicaciones y juegos.</p>
            <a href="<?= base_url('practica/crear-secuencia') ?>" class="btn-grande">CREAR LECCIÓN POR PASOS</a>
        </div>

        <div style="width: 100%; margin-top: 20px; background: #f0f7f4; padding: 20px; border-radius: 15px; border: 2px solid #d4edda;">
            <h3>Mis Alumnos Conectados</h3>
            <p>Tu código para que los alumnos te agreguen es: <strong style="font-size: 1.5rem; color: #28a745;"><?= $_SESSION['user_id'] ?></strong></p>
            
            <?php if (!empty($misAlumnos)): ?>
                <ul style="list-style: none; padding: 0;">
                <?php foreach($misAlumnos as $alumno): ?>
                    <li style="background: white; margin-bottom: 10px; padding: 15px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid #ccc;">
                        <strong><?= $alumno['nombre'] ?></strong>
                        <a href="<?= base_url('tutor/alumno/'.$alumno['id_usuario']) ?>" style="background: #28a745; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px;">Ver Progreso Detallado</a>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Todavía no tienes alumnos conectados. Dales tu código para empezar.</p>
            <?php endif; ?>
        </div>


    <?php elseif ($_SESSION['user_tipo'] === 'admin'): ?>
        <div class="card admin">
            <h3>Administración Total</h3>
            <a href="<?= base_url('admin') ?>" class="btn-grande">PANEL DE CONTROL</a>
        </div>
    <?php endif; ?>

</div>

<hr>
<a href="<?= base_url('logout') ?>" class="btn-salir">Cerrar Sesión</a>
