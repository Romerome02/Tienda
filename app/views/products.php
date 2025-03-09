<?php
if (session_status() === PHP_SESSION_NONE) { // Verificar si la sesión no ha sido iniciada
    session_start(); // Iniciar la sesión
}

if (!isset($_SESSION['user_logged_in'])) { // Verificar si el usuario no ha iniciado sesión
    header('Location: index.php?route=home'); // Redirigir a la página de inicio
    exit(); // Salir del script
}

require_once __DIR__ . '/../controllers/ProductController.php'; // Incluir el controlador de productos
require_once __DIR__ . '/../controllers/CategoryController.php'; // Incluir el controlador de categorías

$productController = new ProductController(); // Crear una instancia del controlador de productos
$categoryController = new CategoryController(); // Crear una instancia del controlador de categorías

$products = $productController->listProducts(false); // Obtener la lista de productos
$categories = $categoryController->viewCategories(); // Obtener la lista de categorías

if (!isset($categories) || empty($categories)) { // Verificar si no se cargaron las categorías
    echo "<p>Error: No se cargaron las categorías.</p>"; // Mostrar mensaje de error
    $categories = []; // Inicializar categorías como un arreglo vacío
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteId'])) { // Verificar si se ha enviado el formulario para eliminar un producto
    $productController->deleteProduct($_POST['deleteId']); // Eliminar el producto
    header("Location: ?route=products"); // Redirigir después de eliminar
    exit(); // Salir del script
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) { // Verificar si se ha enviado el formulario para actualizar un producto
    $productController->updateProduct($_POST, $_FILES); // Actualizar el producto
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> <!-- Incluir Font Awesome CSS -->
    <link rel="stylesheet" href="../../public/css/styles.css"> <!-- Incluir estilos personalizados -->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark"> <!-- Barra de navegación -->
        <div class="container">
            <a class="navbar-brand" href="?route=dashboard">Tienda Online</a> <!-- Enlace a la página principal -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span> <!-- Icono de la barra de navegación -->
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item ms-3">
                        <a class="nav-link btn btn-danger text-white" href="?route=dashboard">Inicio</a> <!-- Enlace a la página de inicio -->
                    </li>
                    <li class="nav-item ms-3">
                        <button class="nav-link btn btn-success text-white" data-bs-toggle="modal"
                            data-bs-target="#addProductModal">Agregar Producto</button> <!-- Botón para agregar un producto -->
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn btn-danger text-white" href="?route=categories">Categorias</a> <!-- Enlace a la página de categorías -->
                    </li>
                    <?php if (isset($_SESSION['nombre'])): ?> <!-- Verificar si el nombre de usuario está en la sesión -->
                        <li class="nav-item ms-3">
                            <span class="nav-link text-white">👤 <?php echo $_SESSION['nombre']; ?></span> <!-- Mostrar el nombre de usuario -->
                        </li>
                    <?php endif; ?>
                    <li class="nav-item ms-4">
                        <a class="nav-link btn btn-danger text-white" href="index.php?route=logout">Cerrar Sesión</a> <!-- Enlace para cerrar sesión -->
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center mb-4">Lista de Productos</h1> <!-- Título de la página -->
        <table class="table table-striped table-hover text-center"> <!-- Tabla de productos -->
            <thead class="table-dark">
                <tr>
                    <th>ID</th> <!-- Columna de ID -->
                    <th>Nombre</th> <!-- Columna de Nombre -->
                    <th>Descripción</th> <!-- Columna de Descripción -->
                    <th>Precio</th> <!-- Columna de Precio -->
                    <th>Estado</th> <!-- Columna de Estado -->
                    <th>Categoría</th> <!-- Columna de Categoría -->
                    <th>Imagen</th> <!-- Columna de Imagen -->
                    <th>Acciones</th> <!-- Columna de Acciones -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?> <!-- Iterar sobre los productos -->
                    <tr>
                        <td><?= $product['id']; ?></td> <!-- Mostrar el ID del producto -->
                        <td><?= htmlspecialchars($product['nombre']); ?></td> <!-- Mostrar el nombre del producto -->
                        <td><?= htmlspecialchars($product['descripcion']); ?></td> <!-- Mostrar la descripción del producto -->
                        <td>$<?= number_format($product['precio'], 2); ?></td> <!-- Mostrar el precio del producto -->
                        <td>
                            <span class="badge <?= $product['estado'] === 'publicado' ? 'bg-success' : 'bg-warning'; ?>">
                                <?= htmlspecialchars($product['estado']); ?> <!-- Mostrar el estado del producto -->
                            </span>
                        </td>
                        <td><?= htmlspecialchars($product['categoria']); ?></td> <!-- Mostrar la categoría del producto -->
                        <td><img src="imagenes/<?= htmlspecialchars($product['imagen']); ?>"
                                alt="<?= htmlspecialchars($product['nombre']); ?>" width="80"></td> <!-- Mostrar la imagen del producto -->

                        <td>
                            <!-- Botón de editar -->
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $product['id']; ?>">
                                <i class="fas fa-edit"></i> <!-- Icono de editar -->
                            </button>

                            <!-- Botón de eliminar -->
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteModal<?= $product['id']; ?>">
                                <i class="fas fa-trash"></i> <!-- Icono de eliminar -->
                            </button>
                        </td>
                    </tr>

                    <!-- Modal de Edición -->
                    <div class="modal fade" id="editModal<?= $product['id']; ?>" tabindex="-1" name="update_product">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Producto</h5> <!-- Título del modal -->
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button> <!-- Botón de cerrar -->
                                </div>
                                <div class="modal-body">
                                    <form action="?route=updateProduct" method="POST" enctype="multipart/form-data"> <!-- Formulario para actualizar producto -->
                                        <input type="hidden" name="id" value="<?= $product['id']; ?>"> <!-- Campo oculto con el ID del producto -->
                                        <div class="mb-3">
                                            <label class="form-label">Nombre:</label> <!-- Etiqueta para el nombre -->
                                            <input type="text" class="form-control" name="nombre"
                                                value="<?= htmlspecialchars($product['nombre']); ?>" required> <!-- Campo de texto para el nombre -->
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Descripción:</label> <!-- Etiqueta para la descripción -->
                                            <textarea class="form-control" name="descripcion"
                                                required><?= htmlspecialchars($product['descripcion']); ?></textarea> <!-- Campo de texto para la descripción -->
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Precio:</label> <!-- Etiqueta para el precio -->
                                            <input type="number" class="form-control" name="precio"
                                                value="<?= $product['precio']; ?>" required> <!-- Campo de texto para el precio -->
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Estado:</label> <!-- Etiqueta para el estado -->
                                            <select class="form-select" name="estado" required> <!-- Selección para el estado -->
                                                <option value="publicado" <?= $product['estado'] === 'Publicado' ? 'selected' : ''; ?>>
                                                    Publicado</option> <!-- Opción Publicado -->
                                                <option value="pausado" <?= $product['estado'] === 'Pausado' ? 'selected' : ''; ?>>
                                                    Pausado</option> <!-- Opción Pausado -->
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Categoría:</label> <!-- Etiqueta para la categoría -->
                                            <select class="form-select" name="categorias_id" required> <!-- Selección para la categoría -->
                                                <option value="" disabled>Seleccione una categoría</option> <!-- Opción por defecto -->
                                                <?php foreach ($categories as $categorie): ?> <!-- Iterar sobre las categorías -->
                                                    <option value="<?= $categorie['id']; ?>"
                                                        <?= $product['categorias_id'] === $categorie['id'] ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($categorie['nombre']); ?> <!-- Mostrar el nombre de la categoría -->
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Imagen:</label> <!-- Etiqueta para la imagen -->
                                            <input type="file" class="form-control" name="imagen" > <!-- Campo de archivo para la imagen -->
                                        </div>
                                        <button type="submit" class="btn btn-primary">Actualizar</button> <!-- Botón para actualizar -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de Eliminación -->
                    <div class="modal fade" id="deleteModal<?= $product['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Eliminar Producto</h5> <!-- Título del modal -->
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button> <!-- Botón de cerrar -->
                                </div>
                                <div class="modal-body">
                                    <p>¿Estás seguro de que quieres eliminar
                                        <strong><?= htmlspecialchars($product['nombre']); ?></strong>?
                                    </p> <!-- Pregunta de confirmación -->
                                </div>
                                <div class="modal-footer">
                                    <form action="?route=products" method="POST"> <!-- Formulario para eliminar producto -->
                                        <input type="hidden" name="deleteId" value="<?= $product['id']; ?>"> <!-- Campo oculto con el ID del producto -->
                                        <button type="submit" class="btn btn-danger">Eliminar</button> <!-- Botón para eliminar -->
                                    </form>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button> <!-- Botón para cancelar -->
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </tbody>
    </table>
    </div>
    <!-- Modal para agregar producto -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Agregar Producto</h5> <!-- Título del modal -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> <!-- Botón de cerrar -->
                </div>
                <div class="modal-body">
                    <form action="?route=processAddProduct" method="POST" enctype="multipart/form-data"> <!-- Formulario para agregar producto -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label> <!-- Etiqueta para el nombre -->
                            <input type="text" class="form-control" id="nombre" name="nombre" required> <!-- Campo de texto para el nombre -->
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción:</label> <!-- Etiqueta para la descripción -->
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea> <!-- Campo de texto para la descripción -->
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio:</label> <!-- Etiqueta para el precio -->
                            <input type="number" class="form-control" id="precio" name="precio" required> <!-- Campo de texto para el precio -->
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado:</label> <!-- Etiqueta para el estado -->
                            <select class="form-select" id="estado" name="estado" required> <!-- Selección para el estado -->
                                <option value="" disabled selected>Seleccione un estado</option> <!-- Opción por defecto -->
                                <option value="publicado">Publicado</option> <!-- Opción Publicado -->
                                <option value="pausado">Pausado</option> <!-- Opción Pausado -->
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría:</label> <!-- Etiqueta para la categoría -->
                            <select class="form-select" id="categoria" name="categorias_id" required> <!-- Selección para la categoría -->
                                <option value="" disabled selected>Seleccione una categoría</option> <!-- Opción por defecto -->
                                <?php foreach ($categories as $categorie): ?> <!-- Iterar sobre las categorías -->
                                    <option value="<?= $categorie['id']; ?>">
                                        <?= htmlspecialchars($categorie['nombre']); ?> <!-- Mostrar el nombre de la categoría -->
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen:</label> <!-- Etiqueta para la imagen -->
                            <input type="file" class="form-control" id="imagen" name="imagen" required> <!-- Campo de archivo para la imagen -->
                        </div>
                        <button type="submit" class="btn btn-success">Agregar Producto</button> <!-- Botón para agregar -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Incluir Bootstrap JS -->
</body>

</html>