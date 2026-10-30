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
        $id_alumno = $_SESSION['user_id'];

        $stmtTutor = $db->prepare("SELECT tutor_id FROM vinculaciones WHERE id_alumno = ? LIMIT 1");
        $stmtTutor->execute([$id_alumno]);
        $vinculacion = $stmtTutor->fetch(PDO::FETCH_ASSOC);
        $id_tutor = $vinculacion ? $vinculacion['tutor_id'] : null;

        $id_juego_seleccionado = isset($_GET['juego']) ? (int)$_GET['juego'] : null;
        $ranking = [];

        if ($id_juego_seleccionado && $id_tutor) {
            $stmtRanking = $db->prepare("
                SELECT u.nombre, r.puntuacion_maxima, r.fecha_registro
                FROM ranking_juegos r
                JOIN usuarios u ON r.id_usuario = u.id_usuario
                WHERE r.id_juego = ? AND r.id_tutor = ?
                ORDER BY r.puntuacion_maxima DESC
                LIMIT 5
            ");
            $stmtRanking->execute([$id_juego_seleccionado, $id_tutor]);
            $ranking = $stmtRanking->fetchAll(PDO::FETCH_ASSOC);
        }

        $stmt = $db->query("SELECT * FROM juegos ORDER BY categoria, fecha_creacion DESC");
        $juegos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $juegosAgrupados = ['raton' => [], 'teclado' => [], 'internet' => [], 'otros' => []];
        foreach ($juegos as $juego) {
            $juegosAgrupados[$juego['categoria']][] = $juego;
        }

        $this->view('practica/index', [
            'categorias' => $juegosAgrupados,
            'ranking' => $ranking,
            'juego_seleccionado' => $id_juego_seleccionado
        ]);
    }

public function guardarPuntuacion() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            if (!headers_sent()) header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
            exit;
        }

        $id_alumno = (int)$_SESSION['user_id'];
        $id_juego = isset($_POST['id_juego']) ? (int)$_POST['id_juego'] : 1;
        $puntos = isset($_POST['puntos']) ? (int)$_POST['puntos'] : 0;

        $db = Database::connect();

        $stmtTutor = $db->prepare("SELECT tutor_id FROM vinculaciones WHERE id_alumno = ? LIMIT 1");
        $stmtTutor->execute([$id_alumno]);
        $vinculacion = $stmtTutor->fetch(PDO::FETCH_ASSOC);
        
       
        $id_tutor = $vinculacion ? (int)$vinculacion['tutor_id'] : 0;

        $stmtCheck = $db->prepare("SELECT id_ranking, puntuacion_maxima FROM ranking_juegos WHERE id_usuario = ? AND id_juego = ?");
        $stmtCheck->execute([$id_alumno, $id_juego]);
        $registroExistente = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (ob_get_length()) ob_clean(); 
        if (!headers_sent()) header('Content-Type: application/json');

        if ($registroExistente) {
            if ($puntos > (int)$registroExistente['puntuacion_maxima']) {
                $stmtUpdate = $db->prepare("UPDATE ranking_juegos SET puntuacion_maxima = ?, fecha_registro = NOW() WHERE id_ranking = ?");
                $stmtUpdate->execute([$puntos, $registroExistente['id_ranking']]);
                echo json_encode(['status' => 'success', 'message' => '¡Récord personal actualizado!', 'nuevo_record' => true]);
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Puntuación guardada', 'nuevo_record' => false]);
            }
        } else {
            // Si es la primera vez que juega, técnicamente también es su primer récord
            $stmtInsert = $db->prepare("INSERT INTO ranking_juegos (id_usuario, id_juego, id_tutor, puntuacion_maxima, fecha_registro) VALUES (?, ?, ?, ?, NOW())");
            $stmtInsert->execute([$id_alumno, $id_juego, $id_tutor, $puntos]);
            echo json_encode(['status' => 'success', 'message' => 'Primer registro guardado', 'nuevo_record' => true]);
        }
        exit;
    }
    public function raton() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }

        $this->view('practica/raton');
    }
    public function teclado() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }

        $this->view('practica/teclado'); 
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
        
        $stmtJuegos = $db->query("SELECT id_juego, titulo FROM juegos");
        $juegos = $stmtJuegos->fetchAll(PDO::FETCH_ASSOC);

        $stmtAlumnos = $db->prepare("
            SELECT u.id_usuario, u.nombre 
            FROM vinculaciones v
            JOIN usuarios u ON v.id_alumno = u.id_usuario
            WHERE v.tutor_id = ?
            ORDER BY u.nombre ASC
        ");
        $stmtAlumnos->execute([$_SESSION['user_id']]);
        $alumnos = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);

        $this->view('practica/crear_secuencia', [
            'juegosDisponibles' => $juegos,
            'misAlumnos' => $alumnos
        ]);
    }

public function guardarSecuencia() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if ($_SESSION['user_tipo'] !== 'tutor') {
            header('Location: ' . base_url('dashboard'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . base_url('dashboard'));
            exit;
        }

        $db = Database::connect();

        $titulo = trim($_POST['titulo']);
        $id_alumno = !empty($_POST['id_alumno']) ? (int)$_POST['id_alumno'] : null;
        $categoria = trim($_POST['categoria']);

        try {
            $db->beginTransaction();

            $stmtSeq = $db->prepare("
                INSERT INTO secuencias_aprendizaje (titulo, tutor_id, id_alumno, categoria) 
                VALUES (?, ?, ?, ?)
            ");
            $stmtSeq->execute([$titulo, $_SESSION['user_id'], $id_alumno, $categoria]);
            
            $idSecuencia = $db->lastInsertId();

            $stmtActPersonalizada = $db->prepare("
                INSERT INTO actividades_personalizadas (id_actividad, tutor_id, id_alumno, titulo, descripcion_general, fecha_creacion, estado) 
                VALUES (?, ?, ?, ?, ?, NOW(), ?)
            ");
            $stmtActPersonalizada->execute([
                $idSecuencia, 
                $_SESSION['user_id'], 
                $id_alumno, 
                $titulo,
                'Secuencia de aprendizaje dinámica creada por el tutor.', 
                'activo'                                                 
            ]);

            if (!empty($_POST['pasos']) && is_array($_POST['pasos'])) {
                $orden = 1;

                foreach ($_POST['pasos'] as $indexJS => $datosPaso) {
                    $tipo = $datosPaso['tipo']; 
                    
                    $texto_instrucciones = null;
                    $imagen_url = null;
                    $video_url = null;
                    $id_juego = null;
                    $tipo_meta = null;
                    $meta_valor = null;

                    if ($tipo === 'texto') {
                        $texto_instrucciones = trim($datosPaso['texto']);
                    } 
                    elseif ($tipo === 'multimedia') {
                        $texto_instrucciones = trim($datosPaso['texto']);
                        
                        if (!empty($datosPaso['video_url'])) {
                            $urlOriginal = trim($datosPaso['video_url']);
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $urlOriginal, $match)) {
                                $video_url = "https://www.youtube.com/embed/" . $match[1];
                            }
                        }

                        $inputName = "imagen_paso_" . $indexJS;
                        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {
                            $extension = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
                            $imagen_url = "paso_" . time() . "_" . uniqid() . "." . $extension;
                            $directorioDestino = __DIR__ . "/../../public/uploads/";
                            
                            if (!is_dir($directorioDestino)) {
                                mkdir($directorioDestino, 0777, true);
                            }
                            
                            move_uploaded_file($_FILES[$inputName]['tmp_name'], $directorioDestino . $imagen_url);
                        }
                    } 
                    elseif ($tipo === 'juego') {
                        $id_juego = !empty($datosPaso['id_juego']) ? (int)$datosPaso['id_juego'] : null;
                        $tipo_meta = $datosPaso['tipo_meta'];
                        $meta_valor = !empty($datosPaso['meta_valor']) ? (int)$datosPaso['meta_valor'] : 10;
                    }

                    $stmtPaso = $db->prepare("
                        INSERT INTO secuencia_pasos 
                        (id_secuencia, orden, tipo_contenido, texto_instrucciones, imagen_url, video_url, id_juego, tipo_meta, meta_valor) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmtPaso->execute([
                        $idSecuencia,
                        $orden,
                        $tipo,
                        $texto_instrucciones,
                        $imagen_url,
                        $video_url,
                        $id_juego,
                        $tipo_meta,
                        $meta_valor
                    ]);

                    $orden++;
                }
            }

            $db->commit();

            header('Location: ' . base_url('dashboard?success=secuencia_creada'));
            exit;

        } catch (Exception $e) {
            $db->rollBack();
            die("Error crítico al procesar la secuencia dinámica: " . $e->getMessage());
        }
    }

    public function verSecuencia($idSecuencia) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }

        $db = Database::connect();
        $id_alumno = (int)$_SESSION['user_id'];
        $idSecuencia = (int)$idSecuencia;

        $stmtSeq = $db->prepare("SELECT * FROM secuencias_aprendizaje WHERE id_secuencia = ?");
        $stmtSeq->execute([$idSecuencia]);
        $secuencia = $stmtSeq->fetch(PDO::FETCH_ASSOC);

        if (!$secuencia) {
            die("La secuencia de aprendizaje no existe.");
        }

        $pasoActual = isset($_GET['paso']) ? (int)$_GET['paso'] : 1;

        $stmtProgreso = $db->prepare("SELECT id_progreso, ultimo_paso_completado FROM progreso_pasos WHERE id_usuario = ? AND id_actividad = ?");
        $stmtProgreso->execute([$id_alumno, $idSecuencia]);
        $progresoExistente = $stmtProgreso->fetch(PDO::FETCH_ASSOC);

        if (!$progresoExistente) {
            $stmtInsertProg = $db->prepare("INSERT INTO progreso_pasos (id_usuario, id_actividad, ultimo_paso_completado, completada, fecha_actualizacion) VALUES (?, ?, 0, 0, NOW())");
            $stmtInsertProg->execute([$id_alumno, $idSecuencia]);
        } else {

            $pasoPrevioNecesario = $pasoActual - 1;
            if ($pasoPrevioNecesario > (int)$progresoExistente['ultimo_paso_completado']) {
                $stmtUpProg = $db->prepare("UPDATE progreso_pasos SET ultimo_paso_completado = ?, fecha_actualizacion = NOW() WHERE id_usuario = ? AND id_actividad = ?");
                $stmtUpProg->execute([$pasoPrevioNecesario, $id_alumno, $idSecuencia]);
            }
        }
        // -------------------------------------------------

        $stmtTotal = $db->prepare("SELECT COUNT(*) as total FROM secuencia_pasos WHERE id_secuencia = ?");
        $stmtTotal->execute([$idSecuencia]);
        $totalPasos = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

        if ($pasoActual > $totalPasos) {
            $stmtMarcarCompleta = $db->prepare("UPDATE progreso_pasos SET ultimo_paso_completado = ?, completada = 1, fecha_actualizacion = NOW() WHERE id_usuario = ? AND id_actividad = ?");
            $stmtMarcarCompleta->execute([$totalPasos, $id_alumno, $idSecuencia]);
            
            $stmtCheckLogro = $db->prepare("SELECT COUNT(*) FROM logros_alumnos WHERE id_usuario = ? AND id_actividad = ?");
            $stmtCheckLogro->execute([$id_alumno, $idSecuencia]);
            $yaTieneMedalla = $stmtCheckLogro->fetchColumn() > 0;

            if (!$yaTieneMedalla) {
                $stmtLogro = $db->prepare("INSERT INTO logros_alumnos (id_usuario, id_actividad, fecha_logro) VALUES (?, ?, NOW())");
                $stmtLogro->execute([$id_alumno, $idSecuencia]);
            }
            
            $this->view('actividades/finalizado', ['id_actividad' => $idSecuencia]);
            return;
        }

        $stmtPaso = $db->prepare("SELECT * FROM secuencia_pasos WHERE id_secuencia = ? AND orden = ?");
        $stmtPaso->execute([$idSecuencia, $pasoActual]);
        $contenidoPaso = $stmtPaso->fetch(PDO::FETCH_ASSOC);

        $stmtSiguiente = $db->prepare("SELECT id_paso FROM secuencia_pasos WHERE id_secuencia = ? AND orden = ?");
        $stmtSiguiente->execute([$idSecuencia, $pasoActual + 1]);
        $tieneSiguiente = $stmtSiguiente->fetch() ? true : false;

        $juegoDatos = null;
        if ($contenidoPaso && $contenidoPaso['tipo_contenido'] === 'juego') {
            $stmtJ = $db->prepare("SELECT * FROM juegos WHERE id_juego = ?");
            $stmtJ->execute([$contenidoPaso['id_juego']]);
            $juegoDatos = $stmtJ->fetch(PDO::FETCH_ASSOC);
        }

        $this->view('practica/ver_secuencia', [
            'secuencia' => $secuencia,
            'pasoNum' => $pasoActual,
            'paso' => $contenidoPaso,
            'tieneSiguiente' => $tieneSiguiente,
            'juego' => $juegoDatos
        ]);
    }public function clicks() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
        $this->view('practica/juego_clicks');
    }

    public function dobleclick() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
        $this->view('practica/juego_dobleclick');
    }

    public function arrastrar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
        $this->view('practica/juego_arrastrar');
    }

    public function juego_seleccion() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
        $this->view('practica/juego_seleccion');
    }
    
    public function frases() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
        $this->view('practica/juego_teclado_frases');
    }
    public function juego_rueda() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
        $this->view('practica/juego_rueda');
    }
}