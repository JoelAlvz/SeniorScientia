<link rel="stylesheet" href="<?= base_url('css/accesibilidad.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/global.css') ?>">

<script src="<?= base_url('js/accesibilidad.js') ?>"></script>

<header class="dashboard-header">
    
    <div class="header-izq-bloque">
        <img src="<?= base_url('img/logoDash.png') ?>" alt="Logotipo" class="dashboard-logo">
    </div>
    
    <div class="header-izq-info">
        <h2>Panel de Control</h2>
        <p>Hola, <strong><?= htmlspecialchars($_SESSION['user_nombre']) ?></strong>. ¿Qué quieres hacer hoy?</p>
    </div>
    
    <div class="header-der">
        <a href="<?= base_url('logout') ?>" class="btn-salir-top">Cerrar Sesión</a>
    </div>
    
</header>

<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['flash_error']; ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<div class="menu-container">

    <?php if ($_SESSION['user_tipo'] === 'alumno'): ?>
        
        <?php 
        $db = Database::connect();
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
            <div class="card-nota-aliento" style="margin-bottom: 30px;">
                <div class="nota-icono">✉️</div>
                <div class="nota-contenido">
                    <h4>Nota de aliento de tu tutor(a) <strong><?= htmlspecialchars($nota_tutor['nombre_tutor']) ?></strong>:</h4>
                    <p>"<?= htmlspecialchars($nota_tutor['mensaje']) ?>"</p>
                    <small>Recibido el <?= date('d/m/Y a las H:i', strtotime($nota_tutor['fecha_envio'])) ?></small>
                </div>
            </div>
        <?php endif; ?>

        <details class="desplegable-medallas">
            <summary class="summary-medallas">🏅 Mis Medallas de Oro (Pulsar para ver)</summary>
            <div class="cuerpo-medallas">
                <?php 
                $db = Database::connect();
                $stmt = $db->prepare("
                    SELECT s.titulo 
                    FROM logros_alumnos l 
                    JOIN secuencias_aprendizaje s ON l.id_actividad = s.id_secuencia 
                    WHERE l.id_usuario = ?
                ");
                $stmt->execute([$_SESSION['user_id']]);
                $medallas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>

            <div class="flex-medallas">
                <?php if ($medallas): ?>
                    <?php foreach ($medallas as $m): ?>
                        <div class="medalla-item">
                            <div class="medalla-circulo">🥇</div>
                            <p><?= htmlspecialchars($m['titulo']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="medalla-texto-vacio">¡Completa tu primera lección para ganar una medalla!</p>
                <?php endif; ?>
            </div>
            </div>
        </details>

        <div class="grid-aprendizaje">
            <div class="card alumno">
                <div>
                    <h3>Mis Clases</h3>
                    <p>Aprende a usar tu ordenador paso a paso con explicaciones dinámicas.</p>
                </div>
                <a href="<?= base_url('cursos') ?>" class="btn-grande-premium">EMPEZAR A APRENDER</a>
            </div>

            <div class="card alumno">
                <div>
                    <h3>Practicar</h3>
                    <p>Juegos interactivos diseñados para mejorar tu soltura con el ratón y el teclado.</p>
                </div>
                <a href="<?= base_url('practica') ?>" class="btn-grande-premium">PRACTICAR AHORA</a>
            </div>
        </div>
        
      <div class="tareas-contenedor">
            <h3>Tareas de mi Tutor</h3>
            <?php 
            $db = Database::connect();
            $id_alumno_sesion = $_SESSION['user_id'];
            
            $stmtCheck = $db->prepare('SELECT tutor_id FROM vinculaciones WHERE id_alumno = ? LIMIT 1');
            $stmtCheck->execute([$id_alumno_sesion]);
            $vinculacion = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            $rutas = [];
            if ($vinculacion) {
               
                $stmt = $db->prepare('
                    SELECT s.* FROM secuencias_aprendizaje s 
                    WHERE s.tutor_id = ? 
                      AND (s.id_alumno IS NULL OR s.id_alumno = ?)
                    ORDER BY s.fecha_creacion DESC
                ');
                $stmt->execute([$vinculacion['tutor_id'], $id_alumno_sesion]);
                $rutas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            ?>

            <?php if (!$vinculacion): ?>
                <div class="tareas-vacio-tutor">
                    <p>👋 ¡Hola! Para empezar a recibir tareas personalizadas, necesitas conectarte con un tutor.</p>
                    <span>Pídele el código de conexión a tu tutor o profesor e introdúcelo en la sección de vinculación de aquí abajo.</span>
                </div>

            <?php elseif ($rutas): ?>
                <?php 
                $ultima_tarea = $rutas[0];
                $otras_tareas = array_slice($rutas, 1);
                ?>

                <div class="tareas-grid">
                    <div class="tarea-item-card" style="border-left-color: var(--color-exito); box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15); position: relative;">
                        <div>
                            <?php if ($ultima_tarea['id_alumno'] !== null): ?>
                                <span style="background: #007bff; color: white; padding: 2px 8px; border-radius: 5px; font-size: 0.8rem; font-weight: bold; display: inline-block; margin-bottom: 5px;">⭐ SOLO PARA TI</span>
                            <?php else: ?>
                                <span style="background: var(--color-exito); color: white; padding: 2px 8px; border-radius: 5px; font-size: 0.8rem; font-weight: bold; display: inline-block; margin-bottom: 5px;">NUEVA TAREA</span>
                            <?php endif; ?>

                            <h4 style="font-size: 1.3rem; color: var(--texto-principal); margin: 5px 0;"><?= htmlspecialchars($ultima_tarea['titulo']) ?></h4>
                            <small style="color: #64748b;">Área: <strong><?= strtoupper($ultima_tarea['categoria']) ?></strong> | Asignada el: <?= date('d/m/Y', strtotime($ultima_tarea['fecha_creacion'])) ?></small>
                        </div>
                        
                        <a href="<?= base_url('practica/ver-secuencia/' . $ultima_tarea['id_secuencia']) ?>" class="btn-empezar-leccion" style="background: var(--color-exito);">
                            EMPEZAR LECCIÓN
                        </a>
                    </div>
                </div>

                <details class="desplegable-tareas-historial" style="margin-top: 15px;">
                    <summary class="summary-tareas">📁 Ver otras tareas anteriores (<?= count($otras_tareas) ?>)</summary>
                    <div class="cuerpo-tareas-historial">
                        <?php if (!empty($otras_tareas)): ?>
                            <?php foreach ($otras_tareas as $ruta): ?>
                                <div class="tarea-item-card" style="margin-bottom: 10px;">
                                    <div>
                                        <?php if ($ruta['id_alumno'] !== null): ?>
                                            <span style="background: #007bff; color: white; padding: 1px 6px; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">⭐ PRIVADA</span>
                                        <?php endif; ?>
                                        <h4 style="margin: 5px 0;"><?= htmlspecialchars($ruta['titulo']) ?></h4>
                                        <small>Creado el: <?= date('d/m/Y', strtotime($ruta['fecha_creacion'])) ?></small>
                                    </div>
                                    <a href="<?= base_url('practica/ver-secuencia/' . $ruta['id_secuencia']) ?>" class="btn-empezar-leccion">
                                        EMPEZAR LECCIÓN
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="texto-desplegable-vacio">No tienes más tareas antiguas pendientes de realizar.</p>
                        <?php endif; ?>
                    </div>
                </details>

            <?php else: ?>
                <div class="tareas-completadas-alerta">
                    <p>🎉 ¡Buen trabajo! No tienes tareas pendientes de tu tutor por ahora.</p>
                    <span>Has completado con éxito todo lo que tenías asignado. ¡Sigue así!</span>
                </div>
            <?php endif; ?>
        </div>

        <div class="vinculacion-box">
            <h3>¿Tienes a alguien que te ayuda?</h3>
            <p>Si tu hijo, nieto o tutor tiene un código, escríbelo aquí para que pueda ver tus progresos:</p>
            
            <form action="<?= base_url('perfil/vincular') ?>" method="POST" class="form-vinculacion">
                <input type="number" name="codigo_tutor" placeholder="Ejemplo: 12" required class="input-codigo-tutor">
                <button type="submit" class="btn-conectar-tutor">CONECTAR CON MI TUTOR</button>
            </form>
            
            <?php if (isset($_SESSION['flash_vinculacion'])): ?>
                <?php 
                $tipo = $_SESSION['flash_vinculacion']['tipo'];
                $mensaje = $_SESSION['flash_vinculacion']['msg'];
                
                $clase_alerta = 'texto-error-inline'; 
                if ($tipo === 'exito') $clase_alerta = 'texto-exito-inline';
                if ($tipo === 'aviso') $clase_alerta = 'texto-aviso-inline';
                ?>
                
                <p class="<?= $clase_alerta ?>" style="margin-top: 15px; font-weight: bold; padding: 8px; border-radius: 6px;">
                    <?= htmlspecialchars($mensaje) ?>
                </p>
                
                <?php unset($_SESSION['flash_vinculacion']); ?>
            <?php endif; ?>

            <?php if (isset($_GET['error']) && $_GET['error'] === 'codigo_invalido'): ?>
                <p class="texto-error-inline" style="margin-top: 15px;">Ese código no es válido. Por favor, pídele el número correcto a tu tutor.</p>
            <?php endif; ?>
        </div>

        <button id="btn-accesibilidad" class="btn-flotante-accesibilidad">👁️</button>
        <div id="menu-accesibilidad" class="panel-flotante-opciones">
            <h4>Opciones de Vista</h4>
            <p class="panel-seccion-titulo">Tamaño del texto:</p>
            <div class="flex-botones-texto">
                <button onclick="cambiarTexto('normal')" class="btn-control-size-a">A</button>
                <button onclick="cambiarTexto('grande')" class="btn-control-size-a-plus">A+</button>
                <button onclick="cambiarTexto('gigante')" class="btn-control-size-a-plus-plus">A++</button>
            </div>
            <p class="panel-seccion-titulo">Colores de la pantalla:</p>
            <button id="btn-contraste" onclick="alternarContraste()" class="btn-panel-contraste">🌓 Alto Contraste</button>
            <p class="panel-seccion-titulo">Ayuda para el ratón:</p>
            <button id="btn-cursor" onclick="alternarCursorEspecial()" class="btn-panel-cursor">🎯 Resaltar Ratón</button>
            <p class="panel-seccion-titulo">Asistencia por voz:</p>
            <button id="btn-voz" onclick="alternarVozAsistida()" class="btn-panel-voz">🔊 Activar Voz</button>
        </div>


    <?php elseif ($_SESSION['user_tipo'] === 'tutor'): ?>
        
        <div class="grid-aprendizaje">
            <div class="card tutor">
                <h3>Gestión de Alumnos</h3>
                <p>Mira cómo van tus alumnos y ayúdales.</p>
                <a href="<?= base_url('admin/alumnos') ?>" class="btn-grande-premium">VER MIS ALUMNOS</a>
            </div>

            <div class="card tutor">
                <h3>Crear Contenido</h3>
                <p>Diseña lecciones personalizadas con explicaciones y juegos.</p>
                <a href="<?= base_url('practica/crear-secuencia') ?>" class="btn-grande-premium">CREAR LECCIÓN POR PASOS</a>
            </div>
        </div>

        <div class="tutor-alumnos-box">
            <h3>Mis Alumnos Conectados</h3>
            <p>Tu código para que los alumnos te agreguen es: <strong class="tutor-codigo-resaltado"><?= $_SESSION['user_id'] ?></strong></p>
            
            <?php 
            $db = Database::connect();
            $stmtMisAlumnos = $db->prepare("
                SELECT u.id_usuario, u.nombre 
                FROM vinculaciones v
                JOIN usuarios u ON v.id_alumno = u.id_usuario
                WHERE v.tutor_id = ?
                ORDER BY u.nombre ASC
            ");
            $stmtMisAlumnos->execute([$_SESSION['user_id']]);
            $misAlumnosActivos = $stmtMisAlumnos->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if (!empty($misAlumnosActivos)): ?>
                <ul class="lista-alumnos-tutor">
                <?php foreach($misAlumnosActivos as $alumno): ?>
                    <li class="alumno-tutor-li">
                        <strong><?= htmlspecialchars($alumno['nombre']) ?></strong>
                        <a href="<?= base_url('tutor/alumno/'.$alumno['id_usuario']) ?>" class="btn-progreso-detallado">Ver Progreso Detallado</a>
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
            <a href="<?= base_url('admin') ?>" class="btn-grande-premium">PANEL DE CONTROL</a>
        </div>
    <?php endif; ?>

</div>