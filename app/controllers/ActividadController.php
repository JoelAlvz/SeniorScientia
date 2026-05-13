<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';

class ActividadController extends Controller {

    public function ver($id_actividad) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['user_id'];
        $db = Database::connect();

        // 1. Mirar en qué paso va el alumno
        $stmt = $db->prepare("SELECT ultimo_paso_completado FROM progreso_pasos WHERE id_usuario = ? AND id_actividad = ?");
        $stmt->execute([$id_usuario, $id_actividad]);
        $progreso = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si es la primera vez que entra, creamos el registro de progreso
        if (!$progreso) {
            $stmt = $db->prepare("INSERT INTO progreso_pasos (id_usuario, id_actividad, ultimo_paso_completado) VALUES (?, ?, 0)");
            $stmt->execute([$id_usuario, $id_actividad]);
            $paso_actual_orden = 1;
        } else {
            $paso_actual_orden = $progreso['ultimo_paso_completado'] + 1;
        }

        // 2. Traer los datos del paso que le toca
        $stmt = $db->prepare("SELECT * FROM actividad_pasos WHERE id_actividad = ? AND orden = ?");
        $stmt->execute([$id_actividad, $paso_actual_orden]);
        $paso = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si no hay más pasos, la actividad ha terminado
        if (!$paso) {
            $stmt = $db->prepare("UPDATE progreso_pasos SET completada = 1 WHERE id_usuario = ? AND id_actividad = ?");
            $stmt->execute([$id_usuario, $id_actividad]);
            $this->view('actividades/finalizado', ['id_actividad' => $id_actividad]);
            return;
        }

        // 3. Si el paso es un juego, necesitamos saber qué juego es
        $juego = null;
        if ($paso['tipo'] === 'juego') {
            $stmt = $db->prepare("SELECT * FROM juegos WHERE id_juego = ?");
            $stmt->execute([$paso['id_juego_referencia']]);
            $juego = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        if (!$paso) {
            $db = Database::connect();
            
            // 1. Marcamos la actividad como completada (esto ya lo teníamos)
            $stmt = $db->prepare("UPDATE progreso_pasos SET completada = 1 WHERE id_usuario = ? AND id_actividad = ?");
            $stmt->execute([$id_usuario, $id_actividad]);

            // 2. NUEVO: Registramos el logro si no lo tenía ya
            $check = $db->prepare("SELECT id_logro FROM logros_alumnos WHERE id_usuario = ? AND id_actividad = ?");
            $check->execute([$id_usuario, $id_actividad]);
            
            if (!$check->fetch()) {
                $ins = $db->prepare("INSERT INTO logros_alumnos (id_usuario, id_actividad) VALUES (?, ?)");
                $ins->execute([$id_usuario, $id_actividad]);
            }

            $this->view('actividades/finalizado', ['id_actividad' => $id_actividad]);
            return;
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
        
        // Sumamos 1 al progreso del alumno
        $stmt = $db->prepare("UPDATE progreso_pasos SET ultimo_paso_completado = ultimo_paso_completado + 1 WHERE id_usuario = ? AND id_actividad = ?");
        $stmt->execute([$_SESSION['user_id'], $id_actividad]);

        header('Location: ' . base_url('actividad/ver/' . $id_actividad));
    }
}