<?php
error_reporting(E_ALL); // Mostrar todos los errores
ini_set('display_errors', 1); // Habilitar la visualización de errores
require_once 'database/conexion.php'; // Incluir el archivo de conexión a la base de datos

try {
    $db = Conexion::getInstance()->getConnection(); // Obtener la instancia de la conexión a la base de datos
    echo "✅ Conexión exitosa.<br>"; // Mensaje de éxito en la conexión

    $usuarios = [ // Usuarios de prueba
        ['nombre' => 'Rodrigo', 'email' => 'rrm@gmail.com', 'password' => '123456', 'rol' => 'admin'],
        ['nombre' => 'Andrea', 'email' => 'andr@example.com', 'password' => '123456', 'rol' => 'cliente'],
    ];

    foreach ($usuarios as $usuario) {
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email"); // Preparar la consulta para verificar si el usuario ya existe
        $stmt->execute(['email' => $usuario['email']]); // Ejecutar la consulta con el email proporcionado
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC); // Obtener el resultado como un array asociativo

        if ($existingUser) { // Verificar si el usuario ya existe
            echo "⚠ Usuario " . $usuario['email'] . " ya existe.<br>"; // Mensaje de advertencia si el usuario ya existe
        } else {
            $hashedPassword = password_hash($usuario['password'], PASSWORD_BCRYPT); // Hashear la contraseña

            $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, contrasena, rol) VALUES (:nombre, :email, :contrasena, :rol)"); // Preparar la consulta para insertar el usuario
            $stmt->execute([ // Ejecutar la consulta con los datos proporcionados
                'nombre' => $usuario['nombre'],
                'email' => $usuario['email'],
                'contrasena' => $hashedPassword,
                'rol' => $usuario['rol']
            ]);

            echo "✅ Usuario " . $usuario['email'] . " insertado correctamente.<br>"; // Mensaje de éxito en la inserción
        }
    }
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage(); // Mensaje de error en caso de excepción
}
?>