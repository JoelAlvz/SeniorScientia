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

        // 1. Obtener datos básicos del alumno
        $stmt = $db->prepare("SELECT id_usuario, nombre, email FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([$idAlumno]);
        $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Obtener el progreso de las LECCIONES POR PASOS (Las que diseña el tutor)
        // Buscamos el título de la actividad y cuántos pasos tiene en total para la barra de progreso
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

        // 3. Obtener el progreso de JUEGOS LIBRES 
        $stmt = $db->prepare("SELECT * FROM progreso_actividades WHERE id_usuario = ? ORDER BY fecha DESC");
        $stmt->execute([$idAlumno]);
        $progresosLibres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 4. Enviamos todo a la vista
        $this->view('tutor/detalle_alumno', [
            'alumno' => $alumno,
            'lecciones' => $leccionesPasos, // Nueva variable
            'progresos' => $progresosLibres  // Tu variable de siempre
        ]);
    }
}