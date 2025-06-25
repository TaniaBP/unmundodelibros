<?php
session_start();
header('Content-Type: application/json');
include "../cont/conexion.php";

// Validar si se envió el ID del préstamo
if (!isset($_POST['idpres']) || empty($_POST['idpres'])) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'ID faltante',
        'descripcion' => 'No se recibió el ID del préstamo.'
    ]);
    exit;
}

$idpres = $_POST['idpres'];

// Verificar si el préstamo existe
$check_sql = "SELECT * FROM loan WHERE id_pres = ?";
$stmt = $con->prepare($check_sql);
$stmt->bind_param("i", $idpres);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'tipo' => 'advertencia',
        'titulo' => 'No encontrado',
        'descripcion' => 'El préstamo no existe o ya fue eliminado.'
    ]);
    exit;
}

// Eliminar el préstamo
$delete_sql = "DELETE FROM loan WHERE id_pres = ?";
$del_stmt = $con->prepare($delete_sql);
$del_stmt->bind_param("i", $idpres);

if ($del_stmt->execute()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => 'Préstamo eliminado',
        'descripcion' => 'El préstamo fue eliminado correctamente.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error al eliminar',
        'descripcion' => 'No se pudo eliminar el préstamo.'
    ]);
}

// Cierre de conexiones
$stmt->close();
$del_stmt->close();
$con->close();
