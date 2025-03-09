<?php
if (session_status() === PHP_SESSION_NONE) { // Verificar si la sesión ya está iniciada
    session_start(); // Iniciar la sesión si no está iniciada
}

require_once __DIR__ . '/../../database/conexion.php'; // Incluir el archivo de conexión a la base de datos

class LoginController
{
    private $db; // Propiedad para almacenar la conexión a la base de datos

    public function __construct() // Constructor para inicializar la conexión a la base de datos
    {
        $this->db = Conexion::getInstance()->getConnection(); // Obtener la instancia de la conexión a la base de datos
    }

    public function authenticateUser($email, $password) // Método para autenticar al usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email"); // Preparar la consulta para obtener el usuario por email
        $stmt->execute(['email' => $email]); // Ejecutar la consulta con el email proporcionado
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener el resultado como un array asociativo

        if (!$user || !password_verify($password, $user['contrasena'])) { // Validar si el usuario existe y la contraseña es correcta
            $_SESSION['login_error'] = "Correo o contraseña incorrectos"; // Establecer un mensaje de error en la sesión
            header("Location: /Tienda/index.php?route=home"); // Redirigir al login
            exit(); // Salir del script
        }

        $_SESSION['user_logged_in'] = true; // Guardar el estado de sesión iniciada
        $_SESSION['user_role'] = $user['rol']; // Guardar el rol del usuario en la sesión
        $_SESSION['username'] = $user['email']; // Guardar el email del usuario en la sesión
        $_SESSION['nombre'] = $user['nombre']; // Guardar el nombre del usuario en la sesión

        $redirectRoute = ($user['rol'] === 'admin') ? 'dashboard' : 'cliente'; // Definir la ruta de redirección según el rol del usuario
        header("Location: /Tienda/index.php?route=$redirectRoute"); // Redirigir según el rol del usuario
        exit(); // Salir del script
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Manejo del formulario de inicio de sesión
    $loginController = new LoginController(); // Crear una instancia del controlador de login
    $loginController->authenticateUser($_POST['email'], $_POST['password']); // Autenticar al usuario con los datos del formulario
}
?>