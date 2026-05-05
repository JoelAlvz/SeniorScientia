<?php

class Controller {

    protected function view($view, $data = []) {
        extract($data);
        require __DIR__ . "/../views/$view.php";
    }

    protected function redirect($url) {
        header("Location: " . base_url('login'));        
        exit;
    }

    protected function auth() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
    }

    protected function role($roles = []) {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        if (!in_array($_SESSION['user']['tipo'], $roles)) {
            echo "Acceso no autorizado";
            exit;
        }
    }
}

?>