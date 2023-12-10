<?php
require '../pdo/pdo.php';

// Crear una instancia de la clase Database
$database = new Database();

// Obtener la conexión a la base de datos
$conn = $database->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : null;

// Consulta SQL con los JOINs para obtener los datos de las máquinas y sus relaciones
$query = "SELECT m.*, tm.nombre AS tipo_maquina, e.nombre AS empresa, es.nombre AS estado, ma.nombre AS marca
          FROM maquinas m
          LEFT JOIN tipos_maquinas tm ON m.id_tipo_maquina = tm.id
          LEFT JOIN empresas e ON m.id_empresa = e.id
          LEFT JOIN estados_maquina es ON m.id_estado = es.id
          LEFT JOIN marcas ma ON m.id_marca = ma.id WHERE m.id = :id";

// Preparar la consulta
$stmt = $conn->prepare($query);

$stmt->bindParam(':id', $id);

// Ejecutar la consulta
$stmt->execute();

// Obtener el primer resultado
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si se obtuvo un resultado
if ($result) {

    $serverUrl = 'http://localhost/maquiservicios_back'; // url servidor
    $imagesFolder = '/imagenes_maquina'; // Ruta relativa de la carpeta de imágenes

    // Obtener la ruta completa de la imagen
    $rutaImagen = $serverUrl . $imagesFolder . "/" . $result['imagen'];
    $result['imagen'] = $rutaImagen;

} else {
    // No se encontró ningún resultado
    $response = [
        'success' => false,
        'message' => 'No se encontró ninguna máquina con el ID especificado.'
    ];

    // Devolver la respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Salir del script después de enviar la respuesta
}

// Devolver los resultados en formato JSON
header('Content-Type: application/json');
echo json_encode($result);

?>
