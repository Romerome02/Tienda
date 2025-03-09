<?php
require_once __DIR__ . '/../../database/conexion.php'; // Incluir el archivo de conexión a la base de datos

class UserModel {
    private $dbConnection; // Propiedad para la conexión a la base de datos

    public function __construct() {
        $this->dbConnection = Conexion::getInstance()->getConnection(); // Obtener la instancia de la conexión a la base de datos
    }

    public function registerUser($nombre, $email, $password, $rol, $fechaCreacion) {
        $stmt = $this->dbConnection->prepare("INSERT INTO usuarios (nombre, email, contrasena, rol, fecha_creacion) VALUES (?, ?, ?, ?, ?)"); // Preparar la consulta SQL para insertar un nuevo usuario
        return $stmt->execute([$nombre, $email, $password, $rol, $fechaCreacion]); // Ejecutar la consulta con los valores proporcionados
    }

    public function emailExists($email) {
        $stmt = $this->dbConnection->prepare("SELECT id FROM usuarios WHERE email = ?"); // Preparar la consulta SQL para verificar si el correo electrónico ya existe
        $stmt->execute([$email]); // Ejecutar la consulta con el correo electrónico proporcionado
        return $stmt->fetch() ? true : false; // Devolver true si se encuentra un resultado, de lo contrario false
    }
}
?>
