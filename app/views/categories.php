<?php
if (session_status() === PHP_SESSION_NONE) { // Si el estado de la sesión es NONE (ninguno)
    session_start(); // Iniciar la sesión
}

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) { // Si no está definida la sesión 'user_logged_in' o no es verdadera
    exit('Acceso denegado. Inicia sesión.'); // Salir con el mensaje 'Acceso denegado. Inicia sesión.'
}

require_once __DIR__ . '/../controllers/CategoryController.php'; // Incluir el archivo CategoryController.php

$controller = new CategoryController(); // Crear una nueva instancia de CategoryController
$categories = $controller->viewCategories(); // Llamar al método viewCategories y almacenar el resultado en $categories

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Si el método de la solicitud es POST
    if (isset($_POST['addCategory'])) { // Si está definida la variable POST 'addCategory'
        $controller->addCategory($_POST['nombre']); // Llamar al método addCategory con el nombre de la categoría
    } elseif (isset($_POST['deleteCategory'])) { // Si está definida la variable POST 'deleteCategory'
        $controller->deleteCategory($_POST['id']); // Llamar al método deleteCategory con el ID de la categoría
    } elseif (isset($_POST['updateCategory'])) { // Si está definida la variable POST 'updateCategory'
        $controller->updateCategory($_POST['id'], $_POST['nombre']); // Llamar al método updateCategory con el ID y el nombre de la categoría
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Establecer el conjunto de caracteres a UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Establecer la vista del puerto a ancho de dispositivo y escala inicial 1.0 -->
    <title>Gestión de Categorías</title> <!-- Título de la página -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Incluir el CSS de Bootstrap -->
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"> <!-- Barra de navegación con expansión grande y fondo oscuro -->
        <div class="container"> <!-- Contenedor -->
            <a class="navbar-brand" href="?route=dashboard">Tienda Online</a> <!-- Enlace de la marca de la barra de navegación -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"> <!-- Botón de alternancia de la barra de navegación -->
                <span class="navbar-toggler-icon"></span> <!-- Icono del botón de alternancia -->
            </button>
            <div class="collapse navbar-collapse" id="navbarNav"> <!-- Contenedor colapsable de la barra de navegación -->
                <ul class="navbar-nav ms-auto"> <!-- Lista de navegación alineada a la derecha -->
                    <li class="nav-item"> <!-- Elemento de la lista de navegación -->
                        <a class="nav-link btn btn-danger text-white" href="?route=dashboard">Inicio</a> <!-- Enlace de navegación con botón de inicio -->
                    </li>
                    <li class="nav-item ms-3"> <!-- Elemento de la lista de navegación con margen izquierdo -->
                        <a class="nav-link text-white" href="?route=products">Productos</a> <!-- Enlace de navegación a productos -->
                    </li>
                    <?php if (isset($_SESSION['nombre'])): ?> <!-- Si está definida la sesión 'nombre' -->
                        <li class="nav-item ms-3"> <!-- Elemento de la lista de navegación con margen izquierdo -->
                            <span class="nav-link text-white">👤 <?php echo $_SESSION['nombre']; ?></span> <!-- Mostrar el nombre del usuario -->
                        </li>
                    <?php endif; ?>
                    <li class="nav-item ms-4"> <!-- Elemento de la lista de navegación con margen izquierdo -->
                        <a class="nav-link btn btn-danger text-white" href="index.php?route=logout">Cerrar Sesión</a> <!-- Enlace de navegación para cerrar sesión -->
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido -->
    <div class="container mt-4"> <!-- Contenedor con margen superior -->
        <h2 class="text-center mb-4">Gestión de Categorías</h2> <!-- Título centrado con margen inferior -->

        <!-- Botón para agregar categoría -->
        <div class="text-end mb-3"> <!-- Contenedor alineado a la derecha con margen inferior -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal"> <!-- Botón para abrir el modal de agregar categoría -->
                + Agregar Categoría
            </button>
        </div>

        <div class="row"> <!-- Fila -->
            <?php foreach ($categories as $category): ?> <!-- Iterar sobre cada categoría -->
                <div class="col-md-6 col-lg-4 d-flex p-2"> <!-- Columna con diseño flexible y padding -->
                    <div class="card shadow-sm w-100 p-3"> <!-- Tarjeta con sombra y padding -->
                        <div class="card-body"> <!-- Cuerpo de la tarjeta -->
                            <h5 class="card-title"><?php echo htmlspecialchars($category['nombre']); ?></h5> <!-- Título de la tarjeta con el nombre de la categoría -->
                            <p class="text-muted mb-1">Fecha de Creación:
                                <?php echo htmlspecialchars($category['fecha_creacion']); ?> <!-- Fecha de creación de la categoría -->
                            </p>
                            <p class="text-muted mb-1">Última Actualización:
                                <?php echo htmlspecialchars($category['fecha_actualizacion']); ?> <!-- Última actualización de la categoría -->
                            </p>

                            <form action="?route=categories" method="POST"> <!-- Formulario para eliminar o editar la categoría -->
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($category['id']); ?>"> <!-- Campo oculto con el ID de la categoría -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editCategory<?php echo htmlspecialchars($category['id']); ?>">Editar</button> <!-- Botón para abrir el modal de editar categoría -->
                                <button type="submit" name="deleteCategory" class="btn btn-danger btn-sm">Eliminar</button> <!-- Botón para eliminar la categoría -->
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal para editar categoría -->
                <div class="modal fade" id="editCategory<?php echo htmlspecialchars($category['id']); ?>" tabindex="-1"
                    aria-labelledby="editCategoryLabel<?php echo htmlspecialchars($category['id']); ?>" aria-hidden="true"> <!-- Modal para editar categoría -->
                    <div class="modal-dialog"> <!-- Diálogo del modal -->
                        <div class="modal-content"> <!-- Contenido del modal -->
                            <div class="modal-header"> <!-- Encabezado del modal -->
                                <h5 class="modal-title"
                                    id="editCategoryLabel<?php echo htmlspecialchars($category['id']); ?>">
                                    Editar Categoría
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button> <!-- Botón para cerrar el modal -->
                            </div>
                            <form method="POST" action="?route=categories"> <!-- Formulario para actualizar la categoría -->
                                <div class="modal-body"> <!-- Cuerpo del modal -->
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($category['id']); ?>"> <!-- Campo oculto con el ID de la categoría -->
                                    <label class="form-label">Nombre</label> <!-- Etiqueta del campo de nombre -->
                                    <input type="text" name="nombre" class="form-control"
                                        value="<?php echo htmlspecialchars($category['nombre']); ?>" required> <!-- Campo de texto con el nombre de la categoría -->
                                </div>
                                <div class="modal-footer"> <!-- Pie del modal -->
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button> <!-- Botón para cancelar -->
                                    <button type="submit" name="updateCategory" class="btn btn-success">Guardar
                                        cambios</button> <!-- Botón para guardar los cambios -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>        
        </div>
    </div>

    <!-- Modal para agregar nueva categoría -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true"> <!-- Modal para agregar nueva categoría -->
        <div class="modal-dialog"> <!-- Diálogo del modal -->
            <div class="modal-content"> <!-- Contenido del modal -->
                <div class="modal-header"> <!-- Encabezado del modal -->
                    <h5 class="modal-title" id="addCategoryLabel">Agregar Nueva Categoría</h5> <!-- Título del modal -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button> <!-- Botón para cerrar el modal -->
                </div>
                <form method="POST" action="?route=categories"> <!-- Formulario para agregar nueva categoría -->
                    <div class="modal-body"> <!-- Cuerpo del modal -->
                        <label class="form-label">Nombre de la Categoría</label> <!-- Etiqueta del campo de nombre -->
                        <input type="text" name="nombre" class="form-control" required> <!-- Campo de texto para el nombre de la categoría -->
                    </div>
                    <div class="modal-footer"> <!-- Pie del modal -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button> <!-- Botón para cancelar -->
                        <button type="submit" name="addCategory" class="btn btn-success">Agregar</button> <!-- Botón para agregar la categoría -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include_once __DIR__ .'/footer.php'; ?> <!-- Incluir el archivo footer.php -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Incluir el JS de Bootstrap -->
</body>

</html>