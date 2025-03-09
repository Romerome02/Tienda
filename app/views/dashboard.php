<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Online</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="?route=dashboard">Tienda Online</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-white" href="?route=dashboard">Inicio</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn btn-danger text-white" href="?route=products">Productos</a>
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
                        <a class="nav-link btn btn-danger text-white" href="?route=logout">Cerrar Sesión</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body text-center p-5">
                        <h2 class="fw-bold">¡Bienvenido a Nuestra Tienda Online!</h2>
                        <p class="text-muted">Gestiona las categorías de productos de manera eficiente y facil.</p>
                        <form action="index.php?route=" method="POST" class="mt-4">
                            <div class="mb-3">
                                <a href="index.php?route=products"
                                    class="me-3 text-dark text-decoration-none">Productos</a>
                                <a href="index.php?route=categories"
                                    class="text-dark text-decoration-none">Categorías</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>