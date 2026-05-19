<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';

class TutorController extends Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Seguridad: solo tutores
        if ($_SESSION['user_tipo'] !== 'tutor') {
            header('Location: ' . base_url('dashboard'));
            exit;
        }
    }

    public function verAlumno($idAlumno) {
        $db = Database::connect();

        $stmt = $db->prepare("SELECT id_usuario, nombre, email FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$idAlumno]);
        $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("
            SELECT 
                a.titulo, 
                p.ultimo_paso_completado, 
                p.completada, 
                p.fecha_actualizacion,
                (SELECT COUNT(*) FROM actividad_pasos WHERE id_actividad = a.id_actividad) as total_pasos
            FROM progreso_pasos p
            JOIN actividades_personalizadas a ON p.id_actividad = a.id_actividad
            WHERE p.id_usuario = ?
            ORDER BY p.fecha_actualizacion DESC
        ");
        $stmt->execute([$idAlumno]);
        $leccionesPasos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT * FROM progreso_actividades WHERE id_usuario = ? ORDER BY fecha DESC");
        $stmt->execute([$idAlumno]);
        $progresosLibres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 4. Enviamos todo a la vista
        $this->view('tutor/detalle_alumno', [
            'alumno' => $alumno,
            'lecciones' => $leccionesPasos, 
            'progresos' => $progresosLibres 
        ]);
    }

    public function guardarmensaje() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'tutor') {
            header('Location: ' . base_url('login'));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_tutor = $_SESSION['user_id'];
            $id_alumno = filter_input(INPUT_POST, 'id_alumno', FILTER_SANITIZE_NUMBER_INT);
            $mensaje = trim(filter_input(INPUT_POST, 'mensaje_nota', FILTER_SANITIZE_SPECIAL_CHARS));

            if (!empty($id_alumno) && !empty($mensaje)) {
                $db = Database::connect();
                $stmt = $db->prepare("INSERT INTO mensajes_tutores (id_tutor, id_alumno, mensaje) VALUES (?, ?, ?)");
                $stmt->execute([$id_tutor, $id_alumno, $mensaje]);

                //  GUARDAMOS EL MENSAJE FLASH DE ÉXITO
               
                $_SESSION['flash_success'] = "¡Nota de aliento enviada correctamente al alumno!";
            }
            
            header('Location: ' . base_url('tutor/alumno/' . $id_alumno));
            exit;
        }
    }
}