<?php
require_once __DIR__ . '/../models/Usuario.php';

class DashboardController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Protege a todos los métodos de esta clase
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }

        $db = Database::connect();
        $misAlumnos = []; 

        // Si es TUTOR, buscamos sus alumnos vinculados
        if ($_SESSION['user_tipo'] === 'tutor') {
            $stmt = $db->prepare("
                SELECT u.id_usuario, u.nombre 
                FROM usuarios u
                JOIN vinculaciones v ON u.id_usuario = v.id_alumno
                WHERE v.tutor_id = ?
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $misAlumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


        $this->view('dashboard/index', ['misAlumnos' => $misAlumnos]);
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        $this->redirect('/login');
    }
}
?>