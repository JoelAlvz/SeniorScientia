<?php

class AdminController extends Controller {

    public function index() {
        $this->role(['admin']);
        $this->view('admin/index');
    }
}


?>