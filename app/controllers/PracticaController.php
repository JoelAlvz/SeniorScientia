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
                $_SESSION['user_id']
            ]);
            
            header('Location: ' . base_url('practica'));
            exit;
        }
    }

    public function crearSecuencia() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if ($_SESSION['user_tipo'] !== 'tutor') {
            header('Location: ' . base_url('dashboard'));
            exit;
        }

        $db = Database::connect();
        $stmt = $db->query("SELECT id_juego, titulo FROM juegos");
        $juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('practica/crear_secuencia', ['juegosDisponibles' => $juegos]);
    }

    public function guardarSecuencia() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if ($_SESSION['user_tipo'] !== 'tutor') {
            header('Location: ' . base_url('dashboard'));
            exit;
        }

        $db = Database::connect();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("INSERT INTO actividades_personalizadas (tutor_id, titulo, descripcion_general) VALUES (?, ?, ?)");
            $stmt->execute([
                $_SESSION['user_id'],
                $_POST['titulo'],
                'Secuencia personalizada creada por el tutor'
            ]);

            $idActividad = $db->lastInsertId();

            $nombreImagen = null;
            if (isset($_FILES['explicacion_imagen']) && $_FILES['explicacion_imagen']['error'] === 0) {
                $extension = pathinfo($_FILES['explicacion_imagen']['name'], PATHINFO_EXTENSION);
                
                // Le damos un nombre único para evitar que imágenes distintas colisionen
                $nombreImagen = "guia_" . time() . "_" . uniqid() . "." . $extension;
                
                // Definimos la ruta hacia tu directorio público de uploads
                $directorioDestino = __DIR__ . "/../../public/uploads/";
                $rutaCompleta = $directorioDestino . $nombreImagen;
                
                // Si la carpeta de subidas no existe en tu estructura de XAMPP, la creamos de forma automática
                if (!is_dir($directorioDestino)) {
                    mkdir($directorioDestino, 0777, true);
                }
                
                // Movemos el archivo temporal al destino real
                move_uploaded_file($_FILES['explicacion_imagen']['tmp_name'], $rutaCompleta);
            }

            $stmtPaso1 = $db->prepare("INSERT INTO actividad_pasos (id_actividad, orden, tipo, contenido, imagen_guia) VALUES (?, ?, ?, ?, ?)");
            $stmtPaso1->execute([
                $idActividad, 
                1, 
                'explicacion', 
                $_POST['paso1_texto'], 
                $nombreImagen
            ]);

            $stmtPasoJuego = $db->prepare("INSERT INTO actividad_pasos (id_actividad, orden, tipo, id_juego_referencia, puntos_necesarios) VALUES (?, ?, ?, ?, ?)");
            $stmtPasoJuego->execute([
                $idActividad, 
                2, 
                'juego', 
                $_POST['paso2_juego_id'], 
                $_POST['paso2_meta']
            ]);

            $stmtPaso3 = $db->prepare("INSERT INTO actividad_pasos (id_actividad, orden, tipo, contenido) VALUES (?, ?, ?, ?)");
            $stmtPaso3->execute([
                $idActividad, 
                3, 
                'externa', 
                $_POST['paso3_texto']
            ]);

            $db->commit();

            header('Location: ' . base_url('dashboard?success=secuencia_creada'));
            exit;

        } catch (Exception $e) {
            $db->rollBack();
            die("Error al guardar la secuencia: " . $e->getMessage());
        }
    }
}