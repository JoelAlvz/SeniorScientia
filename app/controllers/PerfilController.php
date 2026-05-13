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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idTutor = $_POST['codigo_tutor'];
            $idAlumno = $_SESSION['user_id'];

            $db = Database::connect();
            
            // 1. Verificamos que el ID introducido sea realmente un 'tutor'
            $check = $db->prepare("SELECT tipo FROM usuarios WHERE id_usuario = ?");
            $check->execute([$idTutor]);
            $user = $check->fetch();

            if ($user && $user['tipo'] === 'tutor') {
                // 2. ACTUALIZACIÓN CLAVE: Guardamos el ID del tutor en la ficha del alumno
                $stmt = $db->prepare("UPDATE usuarios SET tutor_id = ? WHERE id_usuario = ?");
                $stmt->execute([$idTutor, $idAlumno]);


                $stmt2 = $db->prepare("INSERT INTO vinculaciones (tutor_id, id_alumno) VALUES (?, ?) 
                                    ON DUPLICATE KEY UPDATE tutor_id = ?");
                $stmt2->execute([$idTutor, $idAlumno, $idTutor]);
                
                
                header('Location: ' . base_url('dashboard?success=vinculado'));
            } else {
                header('Location: ' . base_url('dashboard?error=codigo_invalido'));
            }
            exit;
        }
    }
}