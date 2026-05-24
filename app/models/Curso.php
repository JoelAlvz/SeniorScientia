<?php

require_once __DIR__ . '/../core/Database.php';

class Curso {

    public static function obtenerCursosConSecuencias() {
        $db = Database::connect();
        
        $stmtCursos = $db->query("SELECT * FROM cursos ORDER BY orden ASC");
        $cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);

        foreach ($cursos as &$curso) {
            $stmtSecuencias = $db->prepare("SELECT * FROM secuencias_curso WHERE modulo_id = ? ORDER BY orden ASC");
            $stmtSecuencias->execute([$curso['id_modulo']]);
            $curso['secuencias'] = $stmtSecuencias->fetchAll(PDO::FETCH_ASSOC);
        }

        return $cursos;
    }

    public static function obtenerCompletadasPorUsuario($userId) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT id_secuencia FROM progreso_alumnos_cursos WHERE id_usuario = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public static function registrarProgreso($userId, $idSecuencia) {
        $db = Database::connect();
        $stmt = $db->prepare("INSERT IGNORE INTO progreso_alumnos_cursos (id_usuario, id_secuencia) VALUES (?, ?)");
        return $stmt->execute([$userId, $idSecuencia]);
    }

    public static function all() {
        $db = Database::connect();
        return $db->query("SELECT * FROM cursos")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $db = Database::connect();
        $stmt = $db->prepare("SELECT * FROM cursos WHERE id_modulo = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}