<?php

require_once __DIR__ . '/../models/CategoryModel.php'; // Requiere el archivo del modelo de categoría
require_once __DIR__ . '/../../database/conexion.php'; // Requiere el archivo de conexión a la base de datos

class CategoryController
{
    private $categoryModel; // Propiedad privada para el modelo de categoría

    public function __construct()
    {
        $dbConnection = Conexion::getInstance()->getConnection(); // Obtiene la instancia de la conexión a la base de datos
        $this->categoryModel = new Category($dbConnection); // Crea una nueva instancia del modelo de categoría
    }

    public function viewCategories()
    {
        $categories = $this->categoryModel->getAllCategories(); // Obtiene todas las categorías
        return $categories; // Retorna las categorías
    }

    public function addCategory($name)
    {
        if ($this->categoryModel->create($name)) { // Si la categoría se crea correctamente
            header('Location: ?route=categories'); // Redirige a la ruta de categorías
            exit;
        } else {
            die("Error al agregar categoría."); // Muestra un mensaje de error si no se puede agregar la categoría
        }
    }

    public function updateCategory($id, $name)
    {
        $this->categoryModel->update($id, $name); // Actualiza la categoría con el ID y nombre proporcionados
        header('Location: ?route=categories'); // Redirige a la ruta de categorías
        exit;
    }

    public function deleteCategory($id)
    {
        try {
            if ($this->categoryModel->delete($id)) { // Si la categoría se elimina correctamente
                $this->redirect('categories'); // Redirige a la ruta de categorías
            } else {
                throw new Exception("Error al eliminar la categoría."); // Lanza una excepción si no se puede eliminar la categoría
            }
        } catch (Exception $e) {
            error_log("Error en deleteCategory: " . $e->getMessage()); // Registra el error en el log
            $this->redirect('categories', ['error' => $e->getMessage()]); // Redirige a la ruta de categorías con un mensaje de error
        }
    }

    private function redirect($route, $params = [])
    {
        $queryString = http_build_query($params); // Construye la cadena de consulta con los parámetros
        header("Location: ?route=$route" . (!empty($queryString) ? "&$queryString" : "")); // Redirige a la ruta con los parámetros
        exit;
    }
}