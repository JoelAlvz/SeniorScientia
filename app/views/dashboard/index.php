<!-- MayoresApp/app/views/dashboard/index.php -->
<h2>Panel de Control</h2>
<p>Hola, <strong><?= $_SESSION['user_nombre'] ?></strong>. ¿Qué quieres hacer hoy?</p>

<div class="menu-container">

    <?php if ($_SESSION['user_tipo'] === 'alumno'): ?>
        <!-- SECCIÓN PARA EL ALUMNO -->
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
        <!-- SECCIÓN DE TAREAS PERSONALIZADAS -->
        <div style="margin-top: 30px;">
            <h3>Tareas de mi Tutor</h3>
            <?php 
            $db = Database::connect();
            // Buscamos actividades creadas por el tutor que tiene vinculado este alumno
            // (Asumiendo que tienes guardado el id_tutor en la tabla usuarios del alumno)
            $stmt = $db->prepare("SELECT a.* FROM actividades_personalizadas a 
                                JOIN usuarios u ON u.tutor_id = a.tutor_id 
                                WHERE u.id_usuario = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $rutas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if ($rutas): ?>
                <div style="display: grid; gap: 15px;">
                    <?php foreach ($rutas as $ruta): ?>
                        <div style="background: white; padding: 20px; border-radius: 15px; border-left: 10px solid #007bff; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="margin: 0;"><?= $ruta['titulo'] ?></h4>
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
                <p style="color: #666;">Aún no tienes lecciones personalizadas de tu tutor.</p>
            <?php endif; ?>
        </div>

        <!-- Sección de Vinculación dentro del bloque de alumno -->
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



    <?php elseif ($_SESSION['user_tipo'] === 'tutor'): ?>
        <!-- SECCIÓN PARA EL TUTOR -->
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

        <!-- LISTADO DE ALUMNOS VINCULADOS -->
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
        <!-- SECCIÓN PARA EL ADMINISTRADOR -->
        <div class="card admin">
            <h3>Administración Total</h3>
            <a href="<?= base_url('admin') ?>" class="btn-grande">PANEL DE CONTROL</a>
        </div>
    <?php endif; ?>

</div>

<hr>
<a href="<?= base_url('logout') ?>" class="btn-salir">Cerrar Sesión</a>