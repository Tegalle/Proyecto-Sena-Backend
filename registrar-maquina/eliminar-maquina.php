<?php
require '../pdo/pdo.php';

// Crear una instancia de la clase Database
$database = new Database();

// Obtener la conexión a la base de datos
$conn = $database->getConnection();

// Obtener los datos enviados desde Angular
$id = isset($_GET['id']) ? $_GET['id'] : null;

if($id) {
    $query = "DELETE FROM maquinas WHERE id = :id";

    $stmt = $conn->prepare($query);

    // Vincular los parámetros de la consulta
    $stmt->bindValue(':id', $id);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Máquina eliminada correctamente',
            'data' => null
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Error al eliminar la maquina',
            'data' => null
        ];
    }
} else {
    $response = [
        'success' => false,
        'message' => 'No se proporciono un id para eliminar',
        'data' => null
    ];
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>