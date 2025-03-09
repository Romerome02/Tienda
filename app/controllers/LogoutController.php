<?php
session_start(); // Iniciar la sesión

session_unset(); // Eliminar todas las variables de sesión

session_destroy(); // Destruir la sesión

setcookie(session_name(), '', time() - 3600, '/'); // Eliminar la cookie de sesión (opcional)

header("Location: /Tienda/index.php?route=home"); // Redirigir al login
exit(); // Salir del script
?>
