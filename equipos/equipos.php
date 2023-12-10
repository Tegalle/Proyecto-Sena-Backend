<?php
require '../pdo/pdo.php';

// Crear una instancia de la clase Database
$database = new Database();

// Obtener la conexión a la base de datos
$conn = $database->getConnection();

// Consulta SQL con los JOINs para obtener los datos de las máquinas y sus relaciones
$query = "SELECT m.*, tm.nombre AS tipo_maquina, e.nombre AS empresa, es.nombre AS estado, ma.nombre AS marca
          FROM maquinas m
          LEFT JOIN tipos_maquinas tm ON m.id_tipo_maquina = tm.id
          LEFT JOIN empresas e ON m.id_empresa = e.id
          LEFT JOIN estados_maquina es ON m.id_estado = es.id
          LEFT JOIN marcas ma ON m.id_marca = ma.id";

// Preparar la consulta
$stmt = $conn->prepare($query);

// Ejecutar la consulta
$stmt->execute();

// Obtener los resultados
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Iterar sobre los resultados y ajustar la ruta de la imagen
foreach ($results as &$row) {
    $row['imagen'] = '../imagenes/maquina/' . $row['imagen'];
}

// Devolver los resultados en formato JSON
header('Content-Type: application/json');
echo json_encode($results);
?>
