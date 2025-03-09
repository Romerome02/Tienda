<?php
if (session_status() === PHP_SESSION_NONE) { // Verificar si la sesión ya está iniciada
    session_start(); // Iniciar la sesión si no está iniciada
}

$error = $_SESSION['register_error'] ?? null; // Obtener el mensaje de error de la sesión si existe
unset($_SESSION['register_error']); // Limpiar el error después de mostrarlo

$fecha_actual = date("Y-m-d H:i:s"); // Obtener la fecha y hora actual
?>

<!DOCTYPE html>
<html lang="es"> <!-- Establecer el idioma del documento -->

<head>
    <meta charset="UTF-8"> <!-- Establecer la codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configurar la vista para dispositivos móviles -->
    <title>Registrar Usuario</title> <!-- Título de la página -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> <!-- Incluir Bootstrap CSS -->
    <link rel="stylesheet" href="/Tienda/public/css/styles.css"> <!-- Incluir estilos personalizados -->
</head>

<body>

    <div class="login-container"> <!-- Usamos la misma clase para mantener la coherencia -->
        <img src="/Tienda/public/images/logo1.png" alt="Logo" class="logo"> <!-- Logo de la tienda -->
        <h3>Crear Cuenta</h3> <!-- Título de la sección -->

        <?php if (!empty($error)): ?> <!-- Mostrar el mensaje de error si existe -->
            <div class="alert alert-danger"><?php echo $error; ?></div> <!-- Contenedor del mensaje de error -->
        <?php endif; ?>

        <form action="index.php?route=registerUser" method="POST"> <!-- Formulario de registro de usuario -->
            <div class="mb-3">
                <input type="text" name="nombre" class="form-control" placeholder="Nombre completo" required> <!-- Campo de entrada para el nombre completo -->
            </div>
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required> <!-- Campo de entrada para el correo electrónico -->
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Contraseña" required> <!-- Campo de entrada para la contraseña -->
            </div>
            <div class="mb-3">
                <select name="rol" class="form-control" required> <!-- Campo de selección para el rol -->
                    <option value="" disabled selected>Seleccione un rol</option> <!-- Opción por defecto -->
                    <option value="admin">Admin</option> <!-- Opción para rol de administrador -->
                    <option value="cliente">Cliente</option> <!-- Opción para rol de cliente -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrarse</button> <!-- Botón para enviar el formulario -->
            <div class="text-center mt-3">
                <a href="index.php?route=home" class="register-link">¿Ya tienes una cuenta? Iniciar sesión</a> <!-- Enlace para iniciar sesión -->
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Incluir Bootstrap JS -->
</body>

</html>
