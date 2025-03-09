<?php

require_once __DIR__ . '/../../database/conexion.php'; // Requerir el archivo de conexión a la base de datos

class Category // Definir la clase Category
{
    private $database; // Declarar una propiedad privada para la base de datos

    public function __construct($dbConnection) // Constructor de la clase que recibe una conexión a la base de datos
    {
        if ($dbConnection === null) { // Verificar si la conexión es nula
            die("Error: No se pudo conectar a la base de datos."); // Terminar el script si no hay conexión
        }
        $this->database = $dbConnection; // Asignar la conexión a la propiedad de la clase
    }

    public function getAllCategories() // Método para obtener todas las categorías
    {
        $sql = "SELECT * FROM categorias"; // Definir la consulta SQL para seleccionar todas las categorías
        $stmt = $this->database->prepare($sql); // Preparar la consulta
        $stmt->execute(); // Ejecutar la consulta

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retornar las categorías como un arreglo asociativo
    }

    public function create($name) // Método para crear una nueva categoría
    {
        $stmt = $this->database->prepare("INSERT INTO categorias (nombre, fecha_creacion) VALUES (:name, NOW())"); // Preparar la consulta para insertar una nueva categoría
        $stmt->bindParam(':name', $name); // Vincular el parámetro :name con el valor de $name
        return $stmt->execute(); // Ejecutar la consulta y retornar el resultado
    }

    public function update($id, $name) // Método para actualizar una categoría existente
    {
        $stmt = $this->database->prepare("UPDATE categorias SET nombre = :name WHERE id = :id"); // Preparar la consulta para actualizar una categoría
        $stmt->bindParam(':id', $id); // Vincular el parámetro :id con el valor de $id
        $stmt->bindParam(':name', $name); // Vincular el parámetro :name con el valor de $name
        return $stmt->execute(); // Ejecutar la consulta y retornar el resultado
    }

    public function delete($id) // Método para eliminar una categoría
    {
        $stmt = $this->database->prepare("DELETE FROM categorias WHERE id = :id"); // Preparar la consulta para eliminar una categoría
        $stmt->bindParam(':id', $id); // Vincular el parámetro :id con el valor de $id

        if ($stmt->execute()) { // Ejecutar la consulta y verificar si fue exitosa
            return true; // Retornar true si la eliminación fue exitosa
        } else {
            print_r($stmt->errorInfo()); // Imprimir los errores si la eliminación falló
            return false; // Retornar false si la eliminación falló
        }
    }
}