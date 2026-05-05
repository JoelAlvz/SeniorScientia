<?php

require_once __DIR__ . '/../core/Database.php';

class Curso {

    public static function all() {
        $db = Database::connect();
        return $db->query("SELECT * FROM cursos")->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $db = Database::connect();

        $stmt = $db->prepare("SELECT * FROM cursos WHERE id_curso = ?");
        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::connect();

        $stmt = $db->prepare("
            INSERT INTO cursos (titulo, descripcion, nivel)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([
            $data['titulo'],
            $data['descripcion'],
            $data['nivel']
        ]);
    }
}

?>