<?php
require '../pdo/pdo.php';

// Crear una instancia de la clase Database
$database = new Database();

// Obtener la conexión a la base de datos
$conn = $database->getConnection();

// Inicializar la variable de respuesta
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

// Obtener los datos enviados desde Angular
$id = isset($_POST['id']) ? $_POST['id'] : null;
$no_chasis = isset($_POST['no_chasis']) ? $_POST['no_chasis'] : null;
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : null;
$modelo = isset($_POST['modelo']) ? $_POST['modelo'] : null;
$estado = isset($_POST['estado']) ? $_POST['estado'] : null;
$horometro = isset($_POST['horometro']) ? $_POST['horometro'] : null;
$empresa = isset($_POST['empresa']) ? $_POST['empresa'] : null;
$marca = isset($_POST['marca']) ? $_POST['marca'] : null;

// Verificar si se enviaron todos los campos requeridos
if ($no_chasis && $tipo && $modelo && $estado && $horometro && $empresa && $marca) {

    $imagen = isset($_FILES['imagen']) ? $_FILES['imagen'] : null;
    $imagen_movida = false;

    // Si hay una imagen...
    if ($imagen) {
        $imagen_nombre = uniqid() . '_' . $imagen['name']; // Se establece un id unico separado por guin bajo (_), para evitar que se sobrescriban las imagenes
        $imagen_tmp = $imagen['tmp_name'];
        $imagen_tipo = $imagen['type'];

        // Ruta donde se guardará la imagen
        $imagen_movida = move_uploaded_file($imagen_tmp, "../imagenes_maquina/" . $imagen_nombre);

        if (!$imagen_movida) {
            $response['message'] = 'Error al mover la imagen';
            // Devolver la respuesta en formato JSON
            header('Content-Type: application/json');
            echo json_encode($response);
            exit; // Terminar la ejecución del script
        }
    } else {
        $imagen_nombre = "img.png";
    }

    $query = "INSERT INTO maquinas (no_chasis, id_tipo_maquina, modelo, id_estado, horometro, id_empresa, id_marca, imagen) VALUES (:no_chasis, :tipo, :modelo, :estado, :horometro, :empresa, :marca, :imagen)";

    $stmt = $conn->prepare($query);

    // Vincular los parámetros de la consulta
    $stmt->bindValue(':no_chasis', $no_chasis);
    $stmt->bindValue(':tipo', $tipo);
    $stmt->bindValue(':modelo', $modelo);
    $stmt->bindValue(':estado', $estado);
    $stmt->bindValue(':horometro', $horometro);
    $stmt->bindValue(':empresa', $empresa);
    $stmt->bindValue(':marca', $marca);
    $stmt->bindValue(':imagen', $imagen_nombre);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Máquina guardada correctamente';
    } else {
        $response['message'] = 'Error al guardar la máquina';
    }
} else {
    $response['message'] = 'Faltan campos requeridos';
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
