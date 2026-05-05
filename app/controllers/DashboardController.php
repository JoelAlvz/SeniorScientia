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
        $email = $_SESSION['user_email'] ?? null;
        
        if (!$email) {
            header('Location: ' . base_url('login'));
            exit;
        }

        $user = Usuario::findByEmail($email);

        if ($user && isset($user['cuestionario_completado']) && $user['cuestionario_completado'] == 0) {
            $this->view('auth/bienvenida_fotos'); 
            return;
        }

        $this->view('dashboard/index');
    }
    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
}

?>