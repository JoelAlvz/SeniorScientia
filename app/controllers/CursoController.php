
<?php

require_once __DIR__ . '/../models/Curso.php';

class CursoController extends Controller {

    public function index() {
        $this->auth();

        $cursos = Curso::all();
        $this->view('cursos/index', compact('cursos'));
    }

    public function create() {
        $this->role(['admin', 'tutor']); // 🔐 solo pueden crear
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