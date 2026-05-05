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
                    header('Location: /proyectoFinCurso/MayoresApp/public/'); 
                    exit;
                }
            }
        }
        $this->view('auth/register');

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

            header('Location: /proyectoFinCurso/MayoresApp/public/dashboard');
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

        $_SESSION = [];

        session_destroy();

        header('Location: ' . base_url('login'));
        exit;
    }
}

?>