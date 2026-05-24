<?php

require_once __DIR__ . '/../models/Curso.php';

class CursoController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }

        $userId = $_SESSION['user_id'];
        $modulos = Curso::obtenerCursosConSecuencias();
        $secuenciasCompletadas = Curso::obtenerCompletadasPorUsuario($userId);

        $this->view('cursos/index', [
            'modulos' => $modulos,
            'completadas' => $secuenciasCompletadas
        ]);
    }

    public function verLeccionCompleta($idSecuencia) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }

        $db = Database::connect();

        $stmt = $db->prepare("SELECT * FROM secuencias_curso WHERE id_secuencia = ?");
        $stmt->execute([$idSecuencia]); 
        $secuencia = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$secuencia) {
            echo "La lección no existe.";
            exit;
        }

        $stmtSiguiente = $db->prepare("SELECT id_secuencia FROM secuencias_curso WHERE modulo_id = ? AND orden > ? ORDER BY orden ASC LIMIT 1");
        $stmtSiguiente->execute([$secuencia['modulo_id'], $secuencia['orden']]);
        $siguiente = $stmtSiguiente->fetch(PDO::FETCH_ASSOC);

        $this->view('cursos/contenedor_leccion_guiada', [
            'secuencia' => $secuencia,
            'siguiente' => $siguiente
        ]);
    }

    public function guardarProgresoClase() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401); 
            echo json_encode(['success' => false, 'error' => 'Sesión expirada o no válida']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $idSecuencia = $_POST['id_secuencia'] ?? null;

        if ($idSecuencia) {
            $guardadoOk = Curso::registrarProgreso($userId, $idSecuencia);
            
            if ($guardadoOk) {
                http_response_code(200); 
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500); 
                echo json_encode(['success' => false, 'error' => 'No se pudo insertar en la base de datos']);
            }
        } else {
            http_response_code(400); 
            echo json_encode(['success' => false, 'error' => 'Falta el parámetro id_secuencia']);
        }
        
        exit; 
    }
}