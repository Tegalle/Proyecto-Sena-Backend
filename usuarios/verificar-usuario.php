<?php

require '../pdo/pdo.php';

// Crear una instancia de la clase Database
$database = new Database();

// Obtener la conexión a la base de datos
$conn = $database->getConnection();

// Establecer el conjunto de caracteres UTF-8 en la conexión
$conn->query("SET NAMES 'utf8'");

// Obtener el contenido del cuerpo de la solicitud
$json = file_get_contents('php://input');

// Decodificar el objeto JSON
$data = json_decode($json);

// Verificar si se proporcionó el usuario
$usuario = isset($data->usuario) ? $data->usuario : null;

if ($usuario) {
    $query = "SELECT contrasena FROM usuarios WHERE usuario = :usuario";

    $stmt = $conn->prepare($query);

    // Vincular los parámetros de la consulta
    $stmt->bindValue(':usuario', $usuario);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Contraseña obtenida exitosamente',
                'data' => $result['contrasena']
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'No se encontró ningún usuario con ese nombre',
                'data' => null
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Error al consultar el usuario',
            'data' => null
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'No se proporcionó un usuario para verificar',
        'data' => null
    ];
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
