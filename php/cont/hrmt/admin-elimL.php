<?php
session_start();
header('Content-Type: application/json');

include "../cont/conexion.php";

// Validar si se envió el ISBN
if (!isset($_POST['isbn']) || empty($_POST['isbn'])) {
    echo json_encode([
        'success' => false,
        'mensaje' => 'No se recibió ningún ISBN.'
    ]);
    exit;
}

$isbn = $_POST['isbn'];

// Verificar si el libro existe antes de eliminar
$check_sql = "SELECT * FROM book WHERE ISBN = ?";
$stmt = $con->prepare($check_sql);
$stmt->bind_param("s", $isbn);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'mensaje' => 'El libro con ese ISBN no existe.'
    ]);
    exit;
}

// Eliminar el libro
$delete_sql = "DELETE FROM book WHERE ISBN = ?";
$del_stmt = $con->prepare($delete_sql);
$del_stmt->bind_param("s", $isbn);

if ($del_stmt->execute()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => "Libro Eliminado",
        'descripcion' => "Libro eliminado exitosamente."
    ]);
} else {
    echo json_encode([
        'success' => false,
       'tipo' => 'prevencion',
        'titulo' => "Error de Eliminación",
        'descripcion' => "Libros prestados a usuarios."
    ]);
}

$stmt->close();
$del_stmt->close();
$con->close();
?>
