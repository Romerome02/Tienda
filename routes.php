<?php
return [
    // Vistas
    'home' => 'app/views/login.php',      // Página de inicio de sesión
    'dashboard' => 'app/views/dashboard.php', // Vista para agregar categoría
    'products' => 'app/views/products.php',      // Panel de administración
    'products2' => 'app/views/products2.php',      // Panel de administración
    'cliente' => 'app/views/cliente.php',    // Página de cliente
    'register' => 'app/views/register.php',   // Página de registro
    'add' => 'app/views/addProduct.php', // Vista para agregar producto
    

    // Controladores
    'login' => ['controller' => 'LoginController', 'method' => 'authenticateUser'], // Procesar inicio de sesión
    'categories' => ['controller' => 'CategoryController', 'method' => 'viewCategories'], // Vista para ver categorías
    'logout' => 'app/controllers/LogoutController.php', // Ruta para cerrar sesión
    'registerUser' => ['controller' => 'userController', 'method' => 'registerUser'],

    // Productos
    'addProduct' => ['controller' => 'ProductController', 'method' => 'showProductList'], // Cambiado para mostrar el formulario
    'processAddProduct' => ['controller' => 'ProductController', 'method' => 'addProduct'], // Nueva ruta para procesar el formulario
    'updateProduct' => ['controller' => 'ProductController', 'method' => 'updateProduct'], // Ruta para actualizar producto
    'deleteProduct' => ['controller' => 'ProductController', 'method' => 'deleteProduct'], // Ruta para eliminar producto
    //'addCategory' => ['controller' => 'ProductController', 'method' => 'showAddProductForm'], // Ruta para agregar producto
];

?>