<?php
require '../pdo/pdo.php';

// Crear una instancia de la clase Database
$database = new Database();

// Obtener la conexión a la base de datos
$conn = $database->getConnection();

// Obtener los datos enviados desde Angular
$data = json_decode(file_get_contents('php://input'), true);

try {
    // Consulta para verificar las credenciales del usuario
    $query = "SELECT * FROM marcas";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    // Obtener los resultados de la consulta
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Comprobar si se encontraron roles
    if ($roles) {
        $response = [
            'success' => true,
            'message' => 'Marcas obtenidas exitosamente',
            'data' => $roles
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'No se encontraron marcas',
            'data' => null
        ];
    }
} catch (PDOException $e) {
    // Error en la conexión o consulta, devuelve una respuesta con el mensaje de error
    $response = [
        'success' => false,
        'message' => 'Error en la conexión o consulta: ' . $e->getMessage()
    ];
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
