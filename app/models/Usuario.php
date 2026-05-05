<?php

require_once __DIR__ . '/../core/Database.php';

class Usuario {

    public static function findByEmail($email) {
        $db = Database::connect();

        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = Database::connect();

        // Añado 'tipo' a la consulta SQL
        $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, contraseña, tipo) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['tipo'] // Aquí pasamos 'alumno', 'tutor' o 'admin'
        ]);
    }

    public static function actualizarPerfil($id_usuario, $sistema_operativo) {
        $db = Database::connect();
        // Actualizo el SO y marcamos el cuestionario como completado (1)
        $stmt = $db->prepare("UPDATE usuarios SET sistema_operativo = ?, cuestionario_completado = 1 WHERE id_usuario = ?");
        return $stmt->execute([$sistema_operativo, $id_usuario]);
    }

}