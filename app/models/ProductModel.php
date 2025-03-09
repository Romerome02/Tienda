<?php

require_once __DIR__ . '/../../database/conexion.php'; // Requiere el archivo de conexión a la base de datos

class ProductoModel // Define la clase ProductoModel
{
    private $db; // Propiedad para almacenar la conexión a la base de datos

    public function __construct($dbConnection) // Constructor de la clase ProductoModel
    {
        $this->db = $dbConnection; // Asigna la conexión a la base de datos a la propiedad $db
    }

    public function getAllProducts($publicados = false) // Método para obtener todos los productos, opcionalmente solo los publicados
    {
        $query = "SELECT productos.*, categorias.nombre AS categoria 
                  FROM productos 
                  LEFT JOIN categorias ON productos.categorias_id = categorias.id"; // Consulta SQL para obtener productos y sus categorías

        if ($publicados) { // Si se solicita solo los productos publicados
            $query .= " WHERE productos.estado = 'publicado'"; // Filtra solo los productos publicados
        }

        $stmt = $this->db->prepare($query); // Prepara la consulta SQL
        $stmt->execute(); // Ejecuta la consulta

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve todos los resultados como un array asociativo
    }

    public function getProductById($id) // Método para obtener un producto por su ID
    {
        try {
            $id = (int) $id; // Convierte el ID a entero para mayor seguridad

            $query = "SELECT * FROM productos WHERE id = :id"; // Prepara la consulta SQL
            $stmt = $this->db->prepare($query); // Prepara la consulta

            $stmt->bindValue(':id', $id, PDO::PARAM_INT); // Vincula el parámetro ID como entero

            $stmt->execute(); // Ejecuta la consulta

            return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el resultado como un array asociativo
        } catch (PDOException $e) { // Captura cualquier excepción PDO
            error_log("Error al obtener producto: " . $e->getMessage()); // Registra el error en el log
            return false; // Devuelve false en caso de error
        }
    }

    public function insertProduct($data) // Método para insertar un nuevo producto
    {
        try {
            // Verificar si la categoría existe
            $query_categoria = "SELECT id FROM categorias WHERE id = :categorias_id"; // Consulta para verificar la existencia de la categoría
            $stmt_categoria = $this->db->prepare($query_categoria); // Prepara la consulta
            $stmt_categoria->execute([':categorias_id' => $data['categorias_id']]); // Ejecuta la consulta con el ID de la categoría

            if (!$stmt_categoria->fetch(PDO::FETCH_ASSOC)) { // Si no se encuentra la categoría
                throw new Exception("Error: La categoría con ID " . $data['categorias_id'] . " no existe."); // Lanza una excepción
            }

            $id = strtoupper(bin2hex(random_bytes(5))); // Genera un ID único para el producto
            $query = "INSERT INTO productos (id, nombre, descripcion, precio, imagen, estado, categorias_id) 
                      VALUES (:id, :nombre, :descripcion, :precio, :imagen, :estado, :categorias_id)"; // Consulta SQL para insertar un nuevo producto
            $stmt = $this->db->prepare($query); // Prepara la consulta

            return $stmt->execute([
                ':id' => $id, // Vincula el ID del producto
                ':nombre' => $data['nombre'], // Vincula el nombre del producto
                ':descripcion' => $data['descripcion'], // Vincula la descripción del producto
                ':precio' => $data['precio'], // Vincula el precio del producto
                ':imagen' => $data['imagen'], // Vincula la imagen del producto
                ':estado' => $data['estado'], // Vincula el estado del producto
                ':categorias_id' => $data['categorias_id'] // Vincula el ID de la categoría del producto
            ]);
        } catch (PDOException $e) { // Captura cualquier excepción PDO
            error_log("Error al insertar producto: " . $e->getMessage()); // Registra el error en el log
            die("Error al insertar el producto: " . $e->getMessage()); // Termina la ejecución y muestra el error
        } catch (Exception $e) { // Captura cualquier otra excepción
            die($e->getMessage()); // Termina la ejecución y muestra el error
        }
    }

    public function updateProduct($id, $nombre, $descripcion, $precio, $estado, $imagen = null, $categorias_id) // Método para actualizar un producto
    {
        try {
            if ($imagen) { // Si se proporciona una imagen
                $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, estado = ?, imagen = ?, categorias_id = ? WHERE id = ?"; // Consulta SQL para actualizar el producto con imagen
                $params = [$nombre, $descripcion, $precio, $estado, $imagen, $categorias_id, $id]; // Parámetros para la consulta
            } else { // Si no se proporciona una imagen
                $sql = "UPDATE productos 
                    SET nombre = ?, descripcion = ?, precio = ?, estado = ?, categorias_id = ? 
                    WHERE id = ?"; // Consulta SQL para actualizar el producto sin imagen
                $params = [$nombre, $descripcion, $precio, $estado, $categorias_id, $id]; // Parámetros para la consulta
            }

            $stmt = $this->db->prepare($sql); // Prepara la consulta
            return $stmt->execute($params); // Ejecuta la consulta con los parámetros
        } catch (PDOException $e) { // Captura cualquier excepción PDO
            error_log("Error en updateProduct: " . $e->getMessage()); // Registra el error en el log
            return false; // Devuelve false en caso de error
        }
    }

    public function deleteProduct($id) // Método para eliminar un producto por su ID
    {
        $sql = "DELETE FROM productos WHERE id = ?"; // Consulta SQL para eliminar un producto por su ID
        $stmt = $this->db->prepare($sql); // Prepara la consulta
        return $stmt->execute([$id]); // Ejecuta la consulta con el ID proporcionado
    }

}
?>