<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Usuario.php';

class PerfilController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    public function vincularTutor() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idTutor = trim($_POST['codigo_tutor']);
            $idAlumno = $_SESSION['user_id'];

            $db = Database::connect();
            
            $check = $db->prepare("SELECT tipo FROM usuarios WHERE id_usuario = ?");
            $check->execute([$idTutor]);
            $user = $check->fetch();

        if ($user && $user['tipo'] === 'tutor') {
            
            $stmtCheckVinc = $db->prepare("SELECT id_vinculacion FROM vinculaciones WHERE tutor_id = ? AND id_alumno = ?");
            $stmtCheckVinc->execute([$idTutor, $idAlumno]);
            if ($stmtCheckVinc->fetch()) {
                $_SESSION['flash_vinculacion'] = ['tipo' => 'error', 'msg' => "Ya estás conectado con este tutor."];
                header('Location: ' . base_url('dashboard'));
                exit;
            }

            $stmtCheckSol = $db->prepare("SELECT id_solicitud FROM solicitudes_vinculacion WHERE id_tutor = ? AND id_alumno = ? AND estado = 'pendiente'");
            $stmtCheckSol->execute([$idTutor, $idAlumno]);
            if ($stmtCheckSol->fetch()) {
                $_SESSION['flash_vinculacion'] = ['tipo' => 'aviso', 'msg' => " Ya tienes una solicitud pendiente con este tutor. Espera a que la acepte."];
                header('Location: ' . base_url('dashboard'));
                exit;
            }

            $stmtIns = $db->prepare("INSERT INTO solicitudes_vinculacion (id_tutor, id_alumno, estado) VALUES (?, ?, 'pendiente')");
            $stmtIns->execute([$idTutor, $idAlumno]);

            $_SESSION['flash_vinculacion'] = ['tipo' => 'exito', 'msg' => "¡Solicitud enviada! Espera a que tu tutor la apruebe."];
            header('Location: ' . base_url('dashboard'));

        } else {
            $_SESSION['flash_vinculacion'] = ['tipo' => 'error', 'msg' => "El código de tutor introducido no es válido."];
            header('Location: ' . base_url('dashboard'));
        }
        exit;
        }
    }
}