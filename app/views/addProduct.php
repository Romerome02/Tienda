<?php
if (session_status() === PHP_SESSION_NONE) { // Verificar si la sesión ya está iniciada
    session_start(); // Iniciar la sesión si no está iniciada
}

if (!isset($_SESSION['user_logged_in'])) { // Verificar si el usuario ha iniciado sesión
    header('Location: index.php?route=home'); // Redirigir al inicio si no ha iniciado sesión
    exit(); // Salir del script
}

require_once __DIR__ . '/../controllers/ProductController.php'; // Incluir el controlador de productos
require_once __DIR__ . '/../controllers/CategoryController.php'; // Incluir el controlador de categorías

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Establecer la codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Configurar la vista para dispositivos móviles -->
    <title>Agregar Producto</title> <!-- Título de la página -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir Bootstrap CSS -->
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"> <!-- Barra de navegación -->
        <div class="container">
            <a class="navbar-brand" href="?route=dashboard">Tienda Online</a> <!-- Enlace al dashboard -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                   
                    <li class="nav-item ms-3">
                        <a class="nav-link btn btn-danger text-white" href="?route=products">Ver Productos</a> <!-- Enlace para agregar producto -->
                    </li>
                    <?php if (isset($_SESSION['nombre'])): ?> <!-- Mostrar el nombre del usuario si está disponible -->
                        <li class="nav-item ms-3">
                            <span class="nav-link text-white">👤 <?php echo $_SESSION['nombre']; ?></span>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item ms-4">
                        <a class="nav-link btn btn-danger text-white" href="index.php?route=logout">Cerrar Sesión</a>
                        <!-- Enlace para cerrar sesión -->
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mt-4">
        <h1 class="text-center">Agregar Nuevo Producto</h1> <!-- Título de la página -->
        <form action="?route=processAddProduct" method="POST" enctype="multipart/form-data">
            <!-- Formulario para agregar producto -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto:</label>
                <!-- Etiqueta para el nombre del producto -->
                <input type="text" class="form-control" id="nombre" name="nombre" required>
                <!-- Campo de entrada para el nombre del producto -->
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <!-- Etiqueta para la descripción del producto -->
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                <!-- Campo de entrada para la descripción del producto -->
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio:</label> <!-- Etiqueta para el precio del producto -->
                <input type="number" class="form-control" id="precio" name="precio" required>
                <!-- Campo de entrada para el precio del producto -->
            </div>
            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label> <!-- Etiqueta para el estado del producto -->
                <select class="form-select" id="estado" name="estado" required>
                    <!-- Menú desplegable para el estado del producto -->
                    <option value="">Seleccione una categoría</option> <!-- Opción predeterminada -->
                    <option value="publicado">Publicado</option> <!-- Opción para publicado -->
                    <option value="borrador">Pausado</option> <!-- Opción para borrador -->
                </select>
            </div>
            <div class="mb-3">
                <label for="categorias_id" class="form-label">Categoría:</label>
                <select class="form-select" id="categorias_id" name="categorias_id" required>
                    <option value="" disabled selected>Seleccione una categoría</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $categorie): ?>
                            <option value="<?= $categorie['id']; ?>"><?= htmlspecialchars($categorie['nombre']); ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No hay categorías disponibles</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label> <!-- Etiqueta para la imagen del producto -->
                <input type="file" class="form-control" id="imagen" name="imagen" required>
                <!-- Campo de entrada para la imagen del producto -->
            </div>

            <button type="submit" class="btn btn-primary w-100">Agregar Producto</button>
            <!-- Botón para enviar el formulario -->
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Incluir Bootstrap JS -->
</body>

</html>