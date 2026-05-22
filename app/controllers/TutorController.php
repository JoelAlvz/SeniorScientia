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
                s.titulo, 
                p.ultimo_paso_completado, 
                p.completada, 
                p.fecha_actualizacion,
                (SELECT COUNT(*) FROM secuencia_pasos WHERE id_secuencia = s.id_secuencia) as total_pasos
            FROM progreso_pasos p
            JOIN secuencias_aprendizaje s ON p.id_actividad = s.id_secuencia
            WHERE p.id_usuario = ?
            ORDER BY p.fecha_actualizacion DESC
        ");
        $stmt->execute([$idAlumno]); 
        $leccionesPasos = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $this->view('tutor/detalle_alumno', [
            'alumno' => $alumno,
            'lecciones' => $leccionesPasos, 
            'progresos' => [] 
        ]);
    }

    public function adminAlumnos() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'tutor') {
            header('Location: ' . base_url('dashboard'));
            exit;
        }

        require_once __DIR__ . '/../views/tutor/alumnos.php';
    }

    public function procesarSolicitud() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Verificación de seguridad: solo los tutores pueden acceder
        if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'tutor') {
            header('Location: ' . base_url('login'));
            exit;
        }

        $id_solicitud = $_GET['id'] ?? null;
        $accion = $_GET['accion'] ?? null;
        
        if (!$id_solicitud || !$accion) {
            header('Location: ' . base_url('admin/alumnos'));
            exit;
        }
        
        $db = Database::connect();
        
        // 1. Obtener la solicitud para extraer el id_tutor y el id_alumno
        $stmt = $db->prepare("SELECT id_tutor, id_alumno FROM solicitudes_vinculacion WHERE id_solicitud = ? AND estado = 'pendiente'");
        $stmt->execute([$id_solicitud]);
        $solicitud = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($solicitud) {
            if ($accion === 'aceptar') {
                // A) Actualizar el estado de la solicitud a 'aceptada'
                $upSol = $db->prepare("UPDATE solicitudes_vinculacion SET estado = 'aceptada' WHERE id_solicitud = ?");
                $upSol->execute([$id_solicitud]);
                
                // B) REGISTRO EN TU TABLA INTERMEDIA DEFINITIVA: vinculaciones
                $insVinc = $db->prepare("INSERT INTO vinculaciones (tutor_id, id_alumno, fecha_union) VALUES (?, ?, NOW())");
                $insVinc->execute([$solicitud['id_tutor'], $solicitud['id_alumno']]);
                
                $_SESSION['flash_success'] = "✅ El alumno ha sido aceptado y vinculado con éxito.";
                
            } else if ($accion === 'rechazar') {
                // Actualizar el estado de la solicitud a 'rechazada'
                $upSol = $db->prepare("UPDATE solicitudes_vinculacion SET estado = 'rechazada' WHERE id_solicitud = ?");
                $upSol->execute([$id_solicitud]);
                
                $_SESSION['flash_error'] = "❌ Solicitud de vinculación rechazada.";
            }
        }
        
        // Redirigir de vuelta a la vista de administración de alumnos
        header('Location: ' . base_url('admin/alumnos'));
        exit;
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