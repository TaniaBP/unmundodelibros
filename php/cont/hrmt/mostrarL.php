<?php
include "../cont/conexion.php";

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permite peticiones desde otros dominios si es necesario

// Elimina o comenta esta línea si no es necesaria en este momento
// $id_usr = $_SESSION['id_usr'];

if (!isset($_GET['isbn'])) {
    echo json_encode(["error" => "Falta el parámetro ISBN."]);
    exit;
}

$isbn = $_GET['isbn'];

$cons = "SELECT * FROM book WHERE ISBN = ?";
$stmt = $con->prepare($cons);
if (!$stmt) {
    echo json_encode(["error" => "Error en la consulta SQL."]);
    exit;
}

$stmt->bind_param("s", $isbn);
$stmt->execute();
$rst = $stmt->get_result();

if ($datos = $rst->fetch_object()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => 'Datos actualizados',
        'descripcion' => 'Todos los datos se han guardado exitosamente.',
        "nombre" => $datos->Nombre,
        "tipoti" => $datos->Tipo,
        "autor" => $datos->Autor,
        "descripcion" => $datos->Descripcion,
        "isbn" => $datos->ISBN,
        "imagen" => $datos->Portada
    ]);
} else {
    echo json_encode(["error" => "No se encontró información para este ISBN."]);
}


?>
