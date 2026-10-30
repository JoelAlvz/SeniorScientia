<?php

require_once __DIR__ . '/../models/Usuario.php';

class AuthController extends Controller {

    public function login() {
        $this->view('auth/login');
    }

    public function register() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nombre'   => $_POST['nombre'] ?? '',
                'email'    => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'tipo'     => $_POST['tipo'] ?? 'alumno' 
            ];

            if (!empty($data['nombre']) && !empty($data['email']) && !empty($data['password'])) {
                if (Usuario::create($data)) {
                    $config = include __DIR__ . '/../../config/app.php'; 
                    header('Location: ' . $config['base_url'] . '/'); 
                    exit;
                }
            }
        }
        $this->view('auth/register');

    }
    public function verificarEmail() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['existe' => false]);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $email = $input['email'] ?? '';

        if (empty($email)) {
            echo json_encode(['existe' => false]);
            exit;
        }

        $user = Usuario::findByEmail($email);

        if ($user) {
            echo json_encode(['existe' => true]);
        } else {
            echo json_encode(['existe' => false]);
        }
        exit;
    }
    public function store() {
        Usuario::create($_POST);
        $this->redirect('/login');
    }

    public function authenticate() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            return $this->view('auth/login', ['error' => 'Por favor, rellena todos los campos']);
        }

        $user = Usuario::findByEmail($email);

        if ($user && password_verify($password, $user['contraseña'])) {
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_tipo'] = $user['tipo'];
            $config = include __DIR__ . '/../../config/app.php';
            header('Location: ' . $config['base_url'] . '/dashboard');
            exit;
        } else {
            return $this->view('auth/login', ['error' => 'Email o contraseña incorrectos']);
        }
    }

    public function guardarPerfil() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $so = $_POST['so'] ?? 'desconocido';
            $userId = $_SESSION['user_id'];

            if (Usuario::actualizarPerfil($userId, $so)) {
                
                header('Location: ' . base_url('dashboard'));
                exit;
            }
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 

        $_SESSION = [];

        session_destroy();

        header('Location: ' . base_url('login'));
        exit;
    }
}

?>