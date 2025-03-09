<?php
if (session_status() === PHP_SESSION_NONE) { // Verificar si la sesión ya está iniciada
    session_start(); // Iniciar la sesión si no está iniciada
}

// Cargar las rutas desde el archivo routes.php
$routesFile = __DIR__ . DIRECTORY_SEPARATOR . 'routes.php'; // Definir la ruta del archivo de rutas

if (!file_exists($routesFile)) { // Verificar si el archivo de rutas existe
    die("Error: No se encontró el archivo de rutas en: " . $routesFile); // Mostrar mensaje de error si no existe
}

// Obtener rutas
$routes = require $routesFile; // Incluir el archivo de rutas y obtener las rutas

require_once __DIR__ . '/app/controllers/CategoryController.php';

// Obtener la ruta desde la URL, si no existe, se usa 'home'
$route = $_GET['route'] ?? 'home'; // Obtener la ruta desde la URL, si no existe, se usa 'home'

// Si el usuario está autenticado y no se especificó una ruta, redirigirlo a su dashboard
if (isset($_SESSION['user_logged_in']) && empty($_GET['route'])) { // Si el usuario está autenticado y no se especificó una ruta
    $defaultRoute = ($_SESSION['user_role'] === 'admin') ? 'dashboard' : 'cliente'; // Definir la ruta por defecto según el rol del usuario
    header("Location: index.php?route=$defaultRoute"); // Redirigir a la ruta por defecto
    exit(); // Salir del script
}

// Verificar si la ruta existe en la lista y cargar el archivo correspondiente
if (array_key_exists($route, $routes)) { // Verificar si la ruta existe en la lista
    $destination = $routes[$route]; // Obtener el destino de la ruta

    // Si la ruta es un string, se trata de un archivo a incluir
    if (is_string($destination)) { // Si la ruta es un string, se trata de un archivo a incluir
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . $destination; // Definir la ruta completa del archivo
        if (!file_exists($filePath)) { // Verificar si el archivo existe
            die("Error: No se encontró el archivo en: $filePath"); // Mostrar mensaje de error si no existe
        }
        require_once $filePath; // Incluir el archivo
    }
    // Si la ruta es un array, significa que es un controlador/método
    elseif (is_array($destination) && isset($destination['controller']) && isset($destination['method'])) { // Si la ruta es un array, significa que es un controlador/método
        $controllerName = $destination['controller']; // Obtener el nombre del controlador
        $methodName = $destination['method']; // Obtener el nombre del método

        // Construir la ruta del controlador
        $controllerPath = __DIR__ . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR . $controllerName . ".php"; // Construir la ruta del controlador

        if (!file_exists($controllerPath)) { // Verificar si el controlador existe
            die("Error: No se encontró el controlador en: $controllerPath"); // Mostrar mensaje de error si no existe
        }

        require_once $controllerPath; // Incluir el controlador

        // Instanciar el controlador
        if (!class_exists($controllerName)) { // Verificar si la clase del controlador existe
            die("Error: La clase $controllerName no existe en el controlador."); // Mostrar mensaje de error si no existe
        }
        $controller = new $controllerName(); // Instanciar el controlador

        // Verificar si el método existe antes de llamarlo
        if (!method_exists($controller, $methodName)) { // Verificar si el método existe en el controlador
            die("Error: El método $methodName no existe en el controlador $controllerName."); // Mostrar mensaje de error si no existe
        }

        // Llamar al método del controlador
        $controller->$methodName($_POST, $_FILES); // Llamar al método del controlador
    } else {
        die("Error: La ruta '$route' está mal definida en routes.php."); // Mostrar mensaje de error si la ruta está mal definida
    }
} else {
    // Si la ruta no es válida, redirigir al login
    header("Location: index.php?route=home"); // Redirigir al login si la ruta no es válida
    exit(); // Salir del script
}

// Si la ruta es 'categories', obtenemos las categorías y cargamos la vista
if ($route === 'categories') {
    if (!isset($controller)) { 
        $controller = new CategoryController();
    }
    $categories = $controller->viewCategories();
    
    // Verificar que haya categorías antes de cargar la vista
    if (!empty($categories)) {
        require __DIR__ . '/app/views/categories.php';
    } else {
        die("No hay categorías disponibles.");
    }
    exit();
}

?>
