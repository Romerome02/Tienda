<?php

require_once __DIR__ . '/../models/ProductModel.php'; // Incluir el modelo de productos
require_once __DIR__ . '/../models/CategoryModel.php'; // Incluir el modelo de categorías
require_once __DIR__ . '/../../database/conexion.php'; // Incluir la conexión a la base de datos

class ProductController
{
    private $productModel; // Propiedad para almacenar la instancia del modelo de productos
    private $categoryModel; // Propiedad para almacenar la instancia del modelo de categorías

    public function __construct() // Constructor de la clase ProductoController
    {
        $dbConnection = Conexion::getInstance()->getConnection(); // Obtener la instancia de la conexión a la base de datos
        $this->productModel = new ProductoModel($dbConnection); // Crear una instancia del modelo de productos con la conexión a la base de datos
        $this->categoryModel = new Category($dbConnection); // Crear una instancia del modelo de categorías con la conexión a la base de datos
    }

    public function listProducts($publicados = false) // Método para obtener la lista de todos los productos
    {
        return $this->productModel->getAllProducts($publicados); // Llamar al método del modelo para obtener todos los productos
    }

    public function addProduct() // Método para agregar un nuevo producto
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verificar si la solicitud es POST
            $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8'); // Sanitizar y validar el nombre
            $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8'); // Sanitizar y validar la descripción
            $precio = filter_input(INPUT_POST, 'precio', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); // Sanitizar y validar el precio
            $imagen = null; // Inicializar la variable de imagen
            $estado = htmlspecialchars($_POST['estado'], ENT_QUOTES, 'UTF-8'); // Definir el estado del producto
            $categorias_id = filter_input(INPUT_POST, 'categorias_id', FILTER_VALIDATE_INT); // Obtener el ID de la categoría del formulario y validarlo
            if (empty($categorias_id)) { // Verificar si se seleccionó una categoría
                throw new Exception("Debe seleccionar una categoría."); // Lanzar una excepción si no se seleccionó una categoría
            }

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) { // Verificar si se ha subido una imagen
                $permitidos = ['image/jpeg', 'image/png', 'image/webp']; // Tipos de archivo permitidos
                $tipoArchivo = $_FILES['imagen']['type']; // Obtener el tipo de archivo

                if (!in_array($tipoArchivo, $permitidos)) { // Validar el tipo de archivo
                    die("Error: Formato de imagen no permitido. Solo JPG, PNG o WEBP."); // Mostrar mensaje de error si el formato no es permitido
                }

                $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION)); // Obtener la extensión del archivo
                $nombreUnico = bin2hex(random_bytes(10)) . '.' . $extension; // Generar un nombre único
                $rutaDestino = __DIR__ . "/../../imagenes/$nombreUnico"; // Definir la ruta de destino

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) { // Mover la imagen a la carpeta
                    $imagen = $nombreUnico; // Asignar el nombre único a la variable de imagen
                }
            }

            $data = [ // Preparar los datos para insertar
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'precio' => $precio,
                'imagen' => $imagen,
                'estado' => $estado,
                'categorias_id' => $categorias_id, // Ahora se obtiene correctamente
            ];

            if ($this->productModel->insertProduct($data)) { // Insertar el producto en la base de datos
                header("Location: ?route=addProduct&status=success"); // Redirigir después de insertar
                exit();
            } else {
                die("Error al insertar el producto."); // Mostrar mensaje de error si falla la inserción
            }
        }
    }

    public function updateProduct($data, $files) // Método para actualizar un producto
    {
        try {
            $id = htmlspecialchars($data['id'], ENT_QUOTES, 'UTF-8'); // Sanitizar y validar el ID
            $nombre = trim($data['nombre']); // Sanitizar y validar el nombre
            $descripcion = trim($data['descripcion']); // Sanitizar y validar la descripción
            $precio = floatval($data['precio']); // Sanitizar y validar el precio
            $estado = $data['estado']; // Definir el estado del producto
            $categorias_id = intval($data['categorias_id']); // Obtener el ID de la categoría y validarlo
    
            // Validaciones
            if ($precio <= 0) { // Verificar si el precio es positivo
                throw new Exception("El precio debe ser un número positivo."); // Lanzar una excepción si el precio no es válido
            }
            if ($categorias_id <= 0) { // Verificar si se seleccionó una categoría válida
                throw new Exception("Debe seleccionar una categoría válida."); // Lanzar una excepción si la categoría no es válida
            }
    
            // Obtener el producto actual
            $productoActual = $this->productModel->getProductById($id); // Obtener el producto por su ID
            if (!$productoActual) { // Verificar si el producto existe
                throw new Exception("Producto no encontrado."); // Lanzar una excepción si el producto no existe
            }
    
            // Mantener la imagen actual si no se sube una nueva
            $imagen = $productoActual['imagen']; // Asignar la imagen actual
    
            // Procesar nueva imagen si se subió
            if (!empty($files['imagen']['tmp_name']) && $files['imagen']['error'] === UPLOAD_ERR_OK) { // Verificar si se subió una nueva imagen
                $imagen = $this->procesarImagen($files['imagen'], $productoActual['imagen']); // Procesar la nueva imagen
            }
    
            // Actualizar el producto en la base de datos
            $this->productModel->updateProduct($id, $nombre, $descripcion, $precio, $estado, $imagen, $categorias_id); // Actualizar el producto
    
            header("Location: ?route=products&status=updated"); // Redirigir después de actualizar
            exit();
        } catch (Exception $e) { // Capturar excepciones
            error_log("Error en updateProduct: " . $e->getMessage()); // Registrar el error en el log
            header("Location: ?route=editProduct&id=$id&error=" . urlencode($e->getMessage())); // Redirigir con un mensaje de error
            exit();
        }
    }
    
    private function procesarImagen($imagen, $imagenActual) // Método privado para procesar la imagen
    {
        $permitidos = ['image/jpeg', 'image/png', 'image/webp']; // Tipos de archivo permitidos
        $tipoArchivo = $imagen['type']; // Obtener el tipo de archivo
    
        if (!in_array($tipoArchivo, $permitidos)) { // Validar el tipo de archivo
            throw new Exception("Formato de imagen no permitido."); // Lanzar una excepción si el formato no es permitido
        }
    
        $directorio = __DIR__ . "/../../imagenes/"; // Definir el directorio de imágenes
        $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION); // Obtener la extensión del archivo
        $nombreArchivo = uniqid() . '.' . $extension; // Generar un nombre único
        $rutaArchivo = $directorio . $nombreArchivo; // Definir la ruta del archivo
    
        // Subir nueva imagen
        if (!move_uploaded_file($imagen['tmp_name'], $rutaArchivo)) { // Mover la imagen a la carpeta
            throw new Exception("Error al subir la imagen."); // Lanzar una excepción si falla la subida
        }
    
        // Eliminar imagen anterior si existe y no es la misma
        $rutaImagenAnterior = $directorio . $imagenActual; // Definir la ruta de la imagen anterior
        if (!empty($imagenActual) && file_exists($rutaImagenAnterior) && $imagenActual !== $nombreArchivo) { // Verificar si la imagen anterior existe y no es la misma
            unlink($rutaImagenAnterior); // Eliminar la imagen anterior
        }
    
        return $nombreArchivo; // Retornar el nombre del nuevo archivo
    }

    public function deleteProduct($id) // Método para eliminar un producto
    {
        $producto = $this->productModel->getProductById($id); // Obtener el producto por su ID

        if ($producto) { // Verificar si el producto existe
            if (!empty($producto['imagen'])) { // Verificar si el producto tiene una imagen
                $rutaImagen = __DIR__ . "/../../imagenes/" . $producto['imagen']; // Definir la ruta de la imagen

                if (file_exists($rutaImagen)) { // Verificar si la imagen existe antes de intentar eliminarla
                    if (unlink($rutaImagen)) { // Eliminar la imagen
                        error_log("Imagen eliminada correctamente: " . $producto['imagen']); // Registrar la eliminación de la imagen
                    } else {
                        error_log("Error al intentar eliminar la imagen: " . $producto['imagen']); // Registrar el error al eliminar la imagen
                    }
                } else {
                    error_log("Imagen no encontrada en la ruta especificada: " . $rutaImagen); // Registrar que la imagen no se encontró
                }
            }

            if ($this->productModel->deleteProduct($id)) { // Llamar al modelo para eliminar el producto
                header("Location: ?route=products"); // Redirigir después de eliminar
                exit();
            } else {
                die("Error al eliminar el producto de la base de datos."); // Mostrar mensaje de error si falla la eliminación
            }
        } else {
            die("Error: Producto no encontrado."); // Mostrar mensaje si el producto no existe
        }
    }

    public function showProductList() // Método para mostrar la lista de productos
    {
        $products = $this->productModel->getAllProducts(); // Obtener todos los productos
        $categories = $this->categoryModel->getAllCategories(); // Obtener todas las categorías

        include __DIR__ . '/../views/products.php'; // Incluir la vista de productos
    }
}
?>