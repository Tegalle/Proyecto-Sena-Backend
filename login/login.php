<?php
require '../pdo/pdo.php';

// Crear una instancia de la clase Database
$database = new Database();

// Obtener la conexión a la base de datos
$conn = $database->getConnection();

// Obtener los datos enviados desde Angular
$data = json_decode(file_get_contents('php://input'), true);

try {

    $usuario = $data['usuario'];
    $contrasena = $data['contrasena'];
    // Consulta para verificar las credenciales del usuario
    $query = "SELECT * FROM USUARIOS WHERE usuario = :usuario AND contrasena = :contrasena";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':contrasena', $contrasena);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Inicio de sesión exitoso, devuelve una respuesta exitosa
        $response = [
            'success' => true,
            'message' => 'Inicio de sesión exitoso'
        ];
    } else {
        // Credenciales incorrectas, devuelve una respuesta con error
        $response = [
            'success' => false,
            'message' => 'Usuario o contraseña incorrectos'
        ];
    }
} catch (PDOException $e) {
    // Error en la conexión o consulta, devuelve una respuesta con error
    $response = [
        'success' => false,
        'message' => 'Error en la conexión o consulta'
    ];
}

// Devuelve la respuesta en formato JSON
echo json_encode($response);



