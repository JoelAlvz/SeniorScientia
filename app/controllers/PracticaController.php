<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';

class PracticaController extends Controller {

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }

        $db = Database::connect();
        $stmt = $db->query("SELECT * FROM juegos ORDER BY categoria, fecha_creacion DESC");
        $juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $juegosAgrupados = ['raton' => [], 'teclado' => [], 'internet' => [], 'otros' => []];

        foreach ($juegos as $juego) {
            $juegosAgrupados[$juego['categoria']][] = $juego;
        }

        $this->view('practica/index', ['categorias' => $juegosAgrupados]);
    }

    public function raton() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }

        $this->view('practica/raton');
    }
    
    public function create() {
    $this->view('practica/create');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = Database::connect();
            $stmt = $db->prepare("INSERT INTO juegos (titulo, descripcion, categoria, ruta_url, id_creador) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['titulo'],
                $_POST['descripcion'],
                $_POST['categoria'],
                $_POST['ruta_url'],
                $_SESSION['user_id'] // Guardamos quién lo creó
            ]);
            
            header('Location: ' . base_url('practica'));
            exit;
        }
    }

    public function crearSecuencia() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Seguridad
        if ($_SESSION['user_tipo'] !== 'tutor') {
            header('Location: ' . base_url('dashboard'));
            exit;
        }

        $db = Database::connect();
        // Traemos los juegos base que tienes en la tabla 'juegos'
        $stmt = $db->query("SELECT id_juego, titulo FROM juegos");
        $juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cargamos la vista pasándole los juegos
        $this->view('practica/crear_secuencia', ['juegosDisponibles' => $juegos]);
    }

    public function guardarSecuencia() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // 1. Seguridad: Solo tutores
        if ($_SESSION['user_tipo'] !== 'tutor') {
            header('Location: ' . base_url('dashboard'));
            exit;
        }

        $db = Database::connect();

        try {
            // Iniciamos una transacción para asegurar que se guarda todo o nada
            $db->beginTransaction();

            // 2. Insertar la cabecera de la actividad
            $stmt = $db->prepare("INSERT INTO actividades_personalizadas (tutor_id, titulo, descripcion_general) VALUES (?, ?, ?)");
            $stmt->execute([
                $_SESSION['user_id'],
                $_POST['titulo'],
                'Secuencia personalizada creada por el tutor'
            ]);

            $idActividad = $db->lastInsertId();

            // 3. Insertar PASO 1: Explicación
            $stmtPaso = $db->prepare("INSERT INTO actividad_pasos (id_actividad, orden, tipo, contenido) VALUES (?, ?, ?, ?)");
            $stmtPaso->execute([$idActividad, 1, 'explicacion', $_POST['paso1_texto']]);

            // 4. Insertar PASO 2: Juego
            // Usamos el id_juego_referencia y los puntos necesarios
            $stmtPasoJuego = $db->prepare("INSERT INTO actividad_pasos (id_actividad, orden, tipo, id_juego_referencia, puntos_necesarios) VALUES (?, ?, ?, ?, ?)");
            $stmtPasoJuego->execute([
                $idActividad, 
                2, 
                'juego', 
                $_POST['paso2_juego_id'], 
                $_POST['paso2_meta']
            ]);

            // 5. Insertar PASO 3: Conclusión o Tarea externa
            $stmtPaso->execute([$idActividad, 3, 'externa', $_POST['paso3_texto']]);

            // Si todo ha ido bien, confirmamos los cambios
            $db->commit();

            header('Location: ' . base_url('practica?success=secuencia_creada'));

        } catch (Exception $e) {
            // Si algo falla (ej: la base de datos se desconecta), deshacemos todo
            $db->rollBack();
            die("Error al guardar la secuencia: " . $e->getMessage());
        }
    }
}