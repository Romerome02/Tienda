<?php
if (session_status() === PHP_SESSION_NONE) { // Verificar si la sesión ya está iniciada
    session_start(); // Iniciar la sesión si no está iniciada
}

if (!isset($_SESSION['user_logged_in'])) { // Verificar si el usuario ha iniciado sesión
    header('Location: index.php?route=home'); // Redirigir al inicio si no ha iniciado sesión
    exit(); // Salir del script
}

require_once __DIR__ . '/../controllers/ProductController.php'; // Incluir el controlador de productos

$controller = new ProductController(); // Crear una instancia del controlador de productos
$productos = $controller->listProducts(true); // Obtener la lista de productos publicados
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Establecer la codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configurar la vista para dispositivos móviles -->
    <title>Productos - Tienda</title> <!-- Título de la página -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Incluir Bootstrap CSS -->
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"> <!-- Barra de navegación -->
        <div class="container">
            <a class="navbar-brand" href="?route=cliente">Tienda</a> <!-- Enlace a la página de cliente -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['nombre'])): ?> <!-- Mostrar el nombre del usuario si está disponible -->
                        <li class="nav-item">
                            <span class="nav-link text-white">👤 <?php echo $_SESSION['nombre']; ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-white" href="logout.php">Cerrar Sesión</a> <!-- Enlace para cerrar sesión -->
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white" href="login.php">Iniciar Sesión</a> <!-- Enlace para iniciar sesión -->
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mt-4">
        <h1 class="text-center">Productos Disponibles</h1> <!-- Título de la sección -->

        <div class="row">
            <?php foreach ($productos as $producto): ?> <!-- Iterar sobre los productos -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top"
                            alt="Imagen del producto" style="height: 100px; object-fit: cover;"> <!-- Imagen del producto -->
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $producto['nombre']; ?></h5> <!-- Nombre del producto -->
                            <p class="card-text"><?php echo $producto['descripcion']; ?></p> <!-- Descripción del producto -->
                            <p class="fw-bold">Precio: $<?php echo number_format($producto['precio'], 2); ?></p> <!-- Precio del producto -->
                            <a href="#" class="btn btn-success w-100">
                                Agregar al Carrito 🛒 <!-- Botón para agregar al carrito -->
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?> <!-- Incluir el pie de página -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Incluir Bootstrap JS -->

</body>

</html>