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
$productos = $controller->listProducts(false); // Obtener la lista de todos los productos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteProduct'])) { // Verificar si se ha enviado el formulario para eliminar un producto
    $controller->deleteProduct($_POST['deleteId']); // Eliminar el producto
    header("Location: ?route=products"); // Redirigir después de eliminar
    exit(); // Salir del script
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) { // Verificar si se ha enviado el formulario para actualizar un producto
    $controller->updateProduct($_POST, $_FILES); // Actualizar el producto
    header("Location: ?route=products"); // Redirigir después de actualizar
    exit(); // Salir del script
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Establecer la codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configurar la vista para dispositivos móviles -->
    <title>Panel de Administración</title> <!-- Título de la página -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Incluir Bootstrap CSS -->
    <link rel="stylesheet" href="../../public/css/styles.css"> <!-- Incluir estilos personalizados -->
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
                        <a class="nav-link btn btn-danger text-white" href="?route=dashboard">Inicio</a>
                    </li>          
                    <li class="nav-item ms-3">
                        <a class="nav-link btn btn-danger text-white" href="?route=addProduct">Agregar Producto</a> <!-- Enlace para agregar producto -->
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn btn-danger text-white" href="?route=categories">Categorias</a>
                    </li>
                    <?php if (isset($_SESSION['nombre'])): ?> <!-- Mostrar el nombre del usuario si está disponible -->
                        <li class="nav-item ms-3">
                            <span class="nav-link text-white">👤 <?php echo $_SESSION['nombre']; ?></span>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item ms-4">
                        <a class="nav-link btn btn-danger text-white" href="index.php?route=logout">Cerrar Sesión</a> <!-- Enlace para cerrar sesión -->
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <div class="container mt-3">
        <h2 class="text-center">Gestión de Productos</h2> <!-- Título de la sección -->

        <div class="row align-items-stretch">
            <?php foreach ($productos as $producto): ?> <!-- Iterar sobre los productos -->
                <div class="col-md-6 col-lg-4 d-flex p-1">
                    <div class="card shadow-sm d-flex flex-column h-auto p-2">
                        <img src="imagenes/<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top"
                            alt="Imagen del producto" style="height: 100px; object-fit: cover;"> <!-- Imagen del producto -->

                        <div class="card-body d-flex flex-column flex-grow-1 p-0">
                            <h6 class="card-title mb-1"><?php echo $producto['nombre']; ?></h6> <!-- Nombre del producto -->
                            <p class="card-text text-sm flex-grow-1"><?php echo $producto['descripcion']; ?></p> <!-- Descripción del producto -->
                            <p class="fw-bold mb-1">💲 <?php echo number_format($producto['precio'], 2); ?></p> <!-- Precio del producto -->
                            <p class="text-muted mb-1">Fecha de Creación: <?php echo ucfirst($producto['fecha_creacion']); ?></p> <!-- Fecha de creación del producto -->
                            <p class="text-muted mb-1">Fecha de Actualizacion: <?php echo ucfirst($producto['fecha_actualizacion']); ?></p> <!-- Fecha de actualización del producto -->

                            <form action="?route=products" method="POST" enctype="multipart/form-data"
                                class="d-flex flex-column flex-grow-1"> <!-- Formulario para actualizar producto -->
                                <input type="hidden" name="id" value="<?php echo $producto['id']; ?>"> <!-- ID del producto -->

                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control mb-1"
                                    value="<?php echo $producto['nombre']; ?>" required> <!-- Campo para el nombre del producto -->

                                <label class="form-label">Descripción</label>
                                <input type="text" name="descripcion" class="form-control mb-1"
                                    value="<?php echo $producto['descripcion']; ?>" required> <!-- Campo para la descripción del producto -->

                                <label class="form-label">Precio</label>
                                <input type="number" step="0.01" name="precio" class="form-control mb-1"
                                    value="<?php echo $producto['precio']; ?>" required> <!-- Campo para el precio del producto -->

                                <label class="form-label">Estado</label>
                                <select name="estado" class="form-select mb-1"> <!-- Campo para el estado del producto -->
                                    <option value="publicado" <?php echo $producto['estado'] === 'publicado' ? 'selected' : ''; ?>>Publicado</option>
                                    <option value="pausado" <?php echo $producto['estado'] === 'pausado' ? 'selected' : ''; ?>>Pausado</option>
                                </select>

                                <label class="form-label">Imagen</label>
                                <input type="file" name="imagen" class="form-control mb-1"> <!-- Campo para la imagen del producto -->

                                <button type="submit" name="update_product"
                                    class="btn btn-primary btn-sm w-100 mt-auto">Actualizar</button> <!-- Botón para actualizar producto -->

                                <button type="button" class="btn btn-danger btn-sm mt-1" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal<?php echo $producto['id']; ?>"> <!-- Botón para abrir el modal de eliminación -->
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="deleteModal<?php echo $producto['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $producto['id']; ?>" aria-hidden="true"> <!-- Modal de confirmación de eliminación -->
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel<?php echo $producto['id']; ?>">Confirmar Eliminación</h5> <!-- Título del modal -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                ¿Estás seguro de que deseas eliminar el producto <strong><?php echo htmlspecialchars($producto['id']); ?></strong>? Esta acción no se puede deshacer. <!-- Mensaje de confirmación -->
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button> <!-- Botón para cancelar -->
                                <form method="POST" action="?route=products"> <!-- Formulario para eliminar producto -->
                                    <input type="hidden" name="deleteId" value="<?php echo $producto['id']; ?>"> <!-- ID del producto -->
                                    <button type="submit" name="deleteProduct" class="btn btn-danger btn-sm">Eliminar</button> <!-- Botón para eliminar producto -->
                                </form>
                            </div>
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
