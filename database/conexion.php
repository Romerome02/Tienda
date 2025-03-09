<?php
require_once __DIR__ . '/../config/config.php'; // Incluir el archivo de configuración

class Conexion {
    private static $instance = null; // Propiedad para almacenar la instancia única de la conexión
    private $connection; // Propiedad para almacenar la conexión a la base de datos

    private function __construct() { // Constructor privado para evitar la creación directa de instancias
        try {
            $dsn = "mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";charset=utf8mb4"; // Definir el DSN para la conexión
            $this->connection = new PDO($dsn, Config::DB_USER, Config::DB_PASS); // Crear una nueva instancia de PDO
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Establecer el modo de error de PDO a excepción
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage()); // Mostrar mensaje de error en caso de excepción
        }
    }

    public static function getInstance() { // Método para obtener la instancia única de la conexión
        if (self::$instance === null) { // Verificar si la instancia no ha sido creada
            self::$instance = new Conexion(); // Crear una nueva instancia de la conexión
        }
        return self::$instance; // Devolver la instancia única de la conexión
    }

    public function getConnection() { // Método para obtener la conexión a la base de datos
        return $this->connection; // Devolver la conexión a la base de datos
    }
}
?>
