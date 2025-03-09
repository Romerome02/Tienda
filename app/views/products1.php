<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_logged_in'])) {
    header('Location: index.php?route=home');
    exit();
}

require_once __DIR__ . '/../controllers/ProductController.php';
require_once __DIR__ . '/../controllers/CategoryController.php'; // Asegurar carga de categorías

$productController = new ProductController();
$categoryController = new CategoryController();

$products = $productController->listProducts(false);
$categories = $categoryController->viewCategories(); // Obtener categorías

if (!isset($categories) || empty($categories)) {
    echo "<p>Error: No se cargaron las categorías.</p>";
    $categories = []; // Evitar errores si está vacío
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="?route=dashboard">Tienda Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item ms-3">
                        <a class="nav-link btn btn-danger text-white" href="?route=products">Inicio</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn btn-danger text-white" href="?route=add">Agregar Producto</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn btn-danger text-white" href="?route=categories">Categorias</a>
                    </li>
                    <?php if (isset($_SESSION['nombre'])): ?>
                        <li class="nav-item ms-3">
                            <span class="nav-link text-white">👤 <?php echo $_SESSION['nombre']; ?></span>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item ms-4">
                        <a class="nav-link btn btn-danger text-white" href="index.php?route=logout">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1 class="text-center mb-4">Lista de Productos</h1>
        <table class="table table-striped table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Categoría</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= $product['id']; ?></td>
                        <td><?= htmlspecialchars($product['nombre']); ?></td>
                        <td><?= htmlspecialchars($product['descripcion']); ?></td>
                        <td>$<?= number_format($product['precio'], 2); ?></td>
                        <td>
                            <span class="badge <?= $product['estado'] === 'publicado' ? 'bg-success' : 'bg-warning'; ?>">
                                <?= htmlspecialchars($product['estado']); ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($product['categoria']); ?></td>
                        <td><img src="imagenes/<?= $product['imagen']; ?>"
                                alt="<?= htmlspecialchars($product['nombre']); ?>" width="80"></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editModal<?= $product['id']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteModal<?= $product['id']; ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal<?= $product['id']; ?>" tabindex="-1"
                        aria-labelledby="editModalLabel<?= $product['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel<?= $product['id']; ?>">Editar Producto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="?route=&id=<?= $product['id']; ?>" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre:</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre"
                                                value="<?= htmlspecialchars($product['nombre']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="descripcion" class="form-label">Descripción:</label>
                                            <textarea class="form-control" id="descripcion" name="descripcion"
                                                required><?= htmlspecialchars($product['descripcion']); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="precio" class="form-label">Precio:</label>
                                            <input type="number" class="form-control" id="precio" name="precio"
                                                value="<?= $product['precio']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="estado" class="form-label">Estado:</label>
                                            <select class="form-select" id="estado" name="estado" required>
                                                <option value="publicado" <?= $product['estado'] === 'publicado' ? 'selected' : ''; ?>>Publicado</option>
                                                <option value="borrador" <?= $product['estado'] === 'borrador' ? 'selected' : ''; ?>>Borrador</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="categoria" class="form-label">Categoría:</label>
                                            <select class="form-select" id="categoria" name="categorias_id" required>
                                                <option value="" disabled>Seleccione una categoría</option>
                                                <?php if (!empty($categories)): ?>
                                                    <?php foreach ($categories as $categorie): ?>
                                                        <option value="<?= $categorie['id']; ?>"
                                                            <?= ($product['categorias_id'] == $categorie['id']) ? 'selected' : ''; ?>>
                                                            <?= htmlspecialchars($categorie['nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <option value="">No hay categorías disponibles</option>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="imagen" class="form-label">Imagen:</label>
                                            <input type="file" class="form-control" id="imagen" name="imagen">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteModal<?= $product['id']; ?>" tabindex="-1"
                        aria-labelledby="deleteModalLabel<?= $product['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel<?= $product['id']; ?>">Eliminar Producto
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de eliminar este producto?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancelar</button>
                                    <a href="?route=delete&id=<?= $product['id']; ?>" class="btn btn-danger">Eliminar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>