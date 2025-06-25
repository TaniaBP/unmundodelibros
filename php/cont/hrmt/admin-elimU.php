<?php
session_start();
header('Content-Type: application/json');

include "../cont/conexion.php";

// Validar si se envió el número de control
if (!isset($_POST['ncontrol']) || empty($_POST['ncontrol'])) {
    echo json_encode([
        'success' => false,
        'mensaje' => 'No se recibió el número de control del usuario.'
    ]);
    exit;
}

$ncontrol = $_POST['ncontrol'];

// Verificar si el usuario existe antes de eliminar
$check_sql = "SELECT * FROM user WHERE Ncontrol = ?";
$stmt = $con->prepare($check_sql);
$stmt->bind_param("s", $ncontrol);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'mensaje' => 'El usuario con ese número de control no existe.'
    ]);
    exit;
}

// Verificar si tiene préstamos activos (opcional)
$check_loans = "SELECT * FROM loan WHERE Ncontrol = ?";
$loan_stmt = $con->prepare($check_loans);
$loan_stmt->bind_param("s", $ncontrol);
$loan_stmt->execute();
$loan_result = $loan_stmt->get_result();

if ($loan_result->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'tipo' => 'prevencion',
        'titulo' => "Error de Eliminación",
        'descripcion' => "El usuario tiene préstamos activos. No se puede eliminar."
    ]);
    exit;
}

// Eliminar el usuario
$delete_sql = "DELETE FROM user WHERE Ncontrol = ?";
$del_stmt = $con->prepare($delete_sql);
$del_stmt->bind_param("s", $ncontrol);

if ($del_stmt->execute()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => "Usuario Eliminado",
        'descripcion' => "El usuario fue eliminado exitosamente."
    ]);
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => "Error al Eliminar",
        'descripcion' => "Hubo un problema al intentar eliminar el usuario."
    ]);
}

// Cierre de conexiones
$stmt->close();
$loan_stmt->close();
$del_stmt->close();
$con->close();
?>
