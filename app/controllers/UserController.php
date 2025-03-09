<?php
require_once __DIR__ . '/../models/UserModel.php'; // Incluir el modelo de usuario
require_once __DIR__ . '/../../database/conexion.php'; // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verificar si la solicitud es POST
    require_once __DIR__ . '/../models/UserModel.php'; // Incluir el modelo de usuario
    $controller = new UserController(); // Crear una instancia del controlador de usuario
    $controller->registerUser(); // Llamar al método para registrar un nuevo usuario
}

class UserController {
    private $userModel; // Propiedad para almacenar la instancia del modelo de usuario

    public function __construct() { // Constructor de la clase UserController
        $this->userModel = new UserModel(); // Crear una instancia del modelo de usuario
    }

    public function registerUser() { // Método para registrar un nuevo usuario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Verificar si la solicitud es POST
            $nombre = trim($_POST['nombre']); // Obtener y sanitizar el nombre
            $email = trim($_POST['email']); // Obtener y sanitizar el email
            $password = trim($_POST['password']); // Obtener y sanitizar la contraseña
            
            if (!isset($_POST['rol']) || empty(trim($_POST['rol']))) { // Validar que el campo "rol" no esté vacío
                $_SESSION['register_error'] = "Debe seleccionar un rol."; // Establecer un mensaje de error en la sesión
                header("Location: index.php?route=register"); // Redirigir al formulario de registro
                exit();
            }

            $rol = trim($_POST['rol']); // Asignar rol solo si es válido
            $fechaCreacion = date('Y-m-d H:i:s'); // Obtener la fecha y hora actual

            if (empty($nombre) || empty($email) || empty($password)) { // Validar que los campos no estén vacíos
                $_SESSION['register_error'] = "Todos los campos son obligatorios."; // Establecer un mensaje de error en la sesión
                header("Location: index.php?route=register"); // Redirigir al formulario de registro
                exit();
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Validar formato de email
                $_SESSION['register_error'] = "Correo electrónico inválido."; // Establecer un mensaje de error en la sesión
                header("Location: index.php?route=register"); // Redirigir al formulario de registro
                exit();
            }

            if ($this->userModel->emailExists($email)) { // Verificar si el correo ya está registrado
                $_SESSION['register_error'] = "El correo electrónico ya está en uso."; // Establecer un mensaje de error en la sesión
                header("Location: index.php?route=register"); // Redirigir al formulario de registro
                exit();
            }

            $passwordHash = password_hash($password, PASSWORD_BCRYPT); // Encriptar la contraseña

            $success = $this->userModel->registerUser($nombre, $email, $passwordHash, $rol, $fechaCreacion); // Insertar usuario en la base de datos

            if ($success) { // Verificar si la inserción fue exitosa
                $_SESSION['register_success'] = "Usuario registrado correctamente. Inicia sesión."; // Establecer un mensaje de éxito en la sesión
                header("Location: index.php?route=index"); // Redirigir al formulario de inicio de sesión
                exit();
            } else {
                $_SESSION['register_error'] = "Error al registrar el usuario."; // Establecer un mensaje de error en la sesión
                header("Location: index.php?route=register"); // Redirigir al formulario de registro
                exit();
            }
        } else {
            echo "Método no permitido."; // Mostrar mensaje de error si el método no es POST
        }
    }
}
?>
