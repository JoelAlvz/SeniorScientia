
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

        $this->view('cursos/index');
    }

    public function leccion1() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
        $this->view('cursos/leccion1');
    }

    public function create() {
        if ($_SESSION['user_tipo'] !== 'tutor' && $_SESSION['user_tipo'] !== 'admin') {
            header('Location: ' . base_url('dashboard'));
            exit;
        }
        $this->view('cursos/create');
    }
    public function store() {
        $this->role(['admin', 'tutor']);

        Curso::create($_POST);
        $this->redirect('/cursos');
    }

    public function show() {
        $this->auth();

        $curso = Curso::find($_GET['id']);
        $this->view('cursos/show', compact('curso'));
    }
}

?>