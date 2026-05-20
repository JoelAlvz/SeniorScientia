<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';

class ActividadController extends Controller {

    public function ver($id_actividad) {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . base_url('login'));
        exit;
    }
    
    $db = Database::connect();

    if ($_SESSION['user_tipo'] === 'alumno') {
        $stmt = $db->prepare("
            SELECT COUNT(*) 
            FROM actividades_personalizadas a
            JOIN vinculaciones v ON v.tutor_id = a.tutor_id
            WHERE a.id_actividad = ? AND v.id_alumno = ?
        ");
        $stmt->execute([$id_actividad, $_SESSION['user_id']]);
        $pertenece = $stmt->fetchColumn();

        if (!$pertenece) {
            $_SESSION['flash_error'] = "⚠️ No tienes permiso para acceder a esa lección.";
            header('Location: ' . base_url('dashboard'));
            exit;
        }
    }

        $stmt = $db->prepare("SELECT ultimo_paso_completado FROM progreso_pasos WHERE id_usuario = ? AND id_actividad = ?");
        $stmt->execute([$_SESSION['user_id'], $id_actividad]);
        $progreso = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$progreso) {
            $stmt = $db->prepare("INSERT INTO progreso_pasos (id_usuario, id_actividad, ultimo_paso_completado) VALUES (?, ?, 0)");
            $stmt->execute([$_SESSION['user_id'], $id_actividad]);
            $paso_actual_orden = 1;
        } else {
            $paso_actual_orden = $progreso['ultimo_paso_completado'] + 1;
        }

        $stmt = $db->prepare("SELECT * FROM actividad_pasos WHERE id_actividad = ? AND orden = ?");
        $stmt->execute([$id_actividad, $paso_actual_orden]);
        $paso = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$paso) {
            $stmt = $db->prepare("UPDATE progreso_pasos SET completada = 1, fecha_actualizacion = NOW() WHERE id_usuario = ? AND id_actividad = ?");
            $stmt->execute([$_SESSION['user_id'], $id_actividad]);

            $check = $db->prepare("SELECT id_logro FROM logros_alumnos WHERE id_usuario = ? AND id_actividad = ?");
            $check->execute([$_SESSION['user_id'], $id_actividad]);
            
            if (!$check->fetch()) {
                $ins = $db->prepare("INSERT INTO logros_alumnos (id_usuario, id_actividad) VALUES (?, ?)");
                $ins->execute([$_SESSION['user_id'], $id_actividad]);
            }

            $this->view('actividades/finalizado', ['id_actividad' => $id_actividad]);
            return;
        }

        $juego = null;
        if ($paso['tipo'] === 'juego') {
            $stmt = $db->prepare("SELECT * FROM juegos WHERE id_juego = ?");
            $stmt->execute([$paso['id_juego_referencia']]);
            $juego = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        $this->view('actividades/visor', [
            'paso' => $paso,
            'juego' => $juego,
            'id_actividad' => $id_actividad
        ]);
    }

    public function avanzar($id_actividad) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $db = Database::connect();
        
        // Sumamos 1 al progreso del alumno y actualizamos la fecha de su último avance
        $stmt = $db->prepare("UPDATE progreso_pasos SET ultimo_paso_completado = ultimo_paso_completado + 1, fecha_actualizacion = NOW() WHERE id_usuario = ? AND id_actividad = ?");
        $stmt->execute([$_SESSION['user_id'], $id_actividad]);

        header('Location: ' . base_url('actividad/ver/' . $id_actividad));
        exit;
    }
}