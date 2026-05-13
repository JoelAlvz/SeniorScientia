<?php
class Progreso {
    public static function guardar($id_usuario, $actividad, $puntos, $datos = []) {
        $db = Database::connect();
        $json_datos = json_encode($datos); 
        
        $stmt = $db->prepare("INSERT INTO progreso_actividades (id_usuario, actividad_slug, puntos, datos_extra) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$id_usuario, $actividad, $puntos, $json_datos]);
    }

    public static function obtenerPorAlumno($id_alumno) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM progreso_actividades WHERE id_usuario = ? ORDER BY fecha DESC");
        $stmt->execute([$id_alumno]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}