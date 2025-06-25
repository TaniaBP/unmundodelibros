<?php
include "../cont/conexion.php";

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permite peticiones desde otros dominios si es necesario


// Obtener datos del POST
$id_com = $_POST['valor'] ?? '';
$titulo = $_POST['titul'] ?? '';
$comentario = $_POST['conten'] ?? '';

// Validar
if (empty($id_com) || !is_numeric($id_com) || empty($titulo) || empty($comentario)) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Datos incompletos',
        'descripcion' => 'Faltan datos para actualizar el comentario.'
    ]);
    exit;
}

// Preparar consulta
$sql = $con->prepare("UPDATE comentarios SET Titulo = ?, Comentario = ? WHERE Id_com = ?");

if (!$sql) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error en la consulta',
        'descripcion' => 'No se pudo preparar la actualización: ' . $con->error
    ]);
    exit;
}

// Vincular y ejecutar
$sql->bind_param("ssi", $titulo, $comentario, $id_com);

if ($sql->execute()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => '¡Comentario actualizado!',
        'descripcion' => 'El comentario se actualizó correctamente.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error al actualizar',
        'descripcion' => 'No se pudo actualizar el comentario: ' . $sql->error
    ]);
}

ob_end_flush(); // Limpia cualquier salida extra
?>
