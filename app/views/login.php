<?php
if (session_status() === PHP_SESSION_NONE) { // Verificar si la sesión ya está iniciada
    session_start(); // Iniciar la sesión si no está iniciada
}

$error = $_SESSION['login_error'] ?? null; // Obtener el mensaje de error de la sesión si existe
unset($_SESSION['login_error']); // Limpiar el error después de mostrarlo
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8"> <!-- Establecer la codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configurar la vista para dispositivos móviles -->
    <title>Iniciar Sesión</title> <!-- Título de la página -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Incluir Bootstrap CSS -->
    <link rel="stylesheet" href="/Tienda/public/css/styles.css"> <!-- Incluir estilos personalizados -->
</head>

<body>

    <div class="login-container">
        <img src="/Tienda/public/images/logo1.png" alt="Logo" class="logo"> <!-- Logo de la tienda -->
        <h3>Iniciar Sesión</h3> <!-- Título de la sección -->

        <?php if (!empty($error)): ?> <!-- Mostrar el mensaje de error si existe -->
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="index.php?route=login" method="POST"> <!-- Formulario de inicio de sesión -->
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required> <!-- Campo de entrada para el correo electrónico -->
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required> <!-- Campo de entrada para la contraseña -->
            </div>
            <button type="submit" class="btn btn-primary">Ingresar</button> <!-- Botón para enviar el formulario -->
            <div class="text-center mt-3">
                <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</> <!-- Enlace para recuperar la contraseña -->
                <a href="index.php?route=register" class="register-link">Crear cuenta</a> <!-- Enlace para crear una cuenta -->
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Incluir Bootstrap JS -->
</body>

</html>