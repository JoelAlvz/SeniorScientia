<?php
require_once __DIR__ . '/../models/Usuario.php';
class DashboardController extends Controller {

    public function __construct() {
        //  protege a todos los métodos de esta clase
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

        $user = Usuario::findByEmail($_SESSION['user_email']); 
        
        if ($user && $user['cuestionario_completado'] == 0 && $_SESSION['user_tipo'] === 'alumno') {
            $this->view('auth/bienvenida_fotos'); 
            return;
        }

        // Pasamos 'misAlumnos' a la vista
        $this->view('dashboard/index', ['misAlumnos' => $misAlumnos]);
    }
    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
}

?>