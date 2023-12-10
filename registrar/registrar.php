<?php
require '../pdo/pdo.php';

// Crear una instancia de la clase Database
$database = new Database();

// Obtener la conexión a la base de datos
$conn = $database->getConnection();

// Obtener los datos enviados desde Angular
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $nombres = $data['nombres'];
    $no_documento = $data['no_documento'];
    $usuario = $data['usuario'];
    $correo = $data['correo'];
    $telefono = $data['telefono'];
    $rol = $data['rol'];
    $contrasena = $data['contrasena'];

    // Crear la consulta preparada
    $query = "INSERT INTO USUARIOS (nombres, no_documento, usuario, correo, telefono, id_rol, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Vincular los parámetros de la consulta
    $stmt->bindParam(1, $nombres);
    $stmt->bindParam(2, $no_documento);
    $stmt->bindParam(3, $usuario);
    $stmt->bindParam(4, $correo);
    $stmt->bindParam(5, $telefono);
    $stmt->bindParam(6, $rol);
    $stmt->bindParam(7, $contrasena);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Usuario guardado correctamente',
            'data' => null
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Error al guardar el usuario',
            'data' => null
        ];
    }
} else {
    // Ruta no encontrada, devuelve una respuesta de error
    $response = [
        'success' => false,
        'message' => 'Ruta no encontrada',
        'data' => null
    ];

    // Devuelve la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>