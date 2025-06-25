<?php
session_start();
header('Content-Type: application/json');
include "../cont/conexion.php";

// Validar si se envió el ID de lista negra
if (!isset($_POST['idneg']) || empty($_POST['idneg'])) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'ID faltante',
        'descripcion' => 'No se recibió el ID de la lista negra.'
    ]);
    exit;
}

$idneg = $_POST['idneg'];

// 1. Obtener el número de control (Ncontrol) desde listNeg
$getUser = $con->prepare("SELECT Ncontrol FROM lisneg WHERE id_neg = ?");
$getUser->bind_param("i", $idneg);
$getUser->execute();
$resUser = $getUser->get_result();

if ($resUser->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'tipo' => 'advertencia',
        'titulo' => 'No encontrado',
        'descripcion' => 'La entrada en lista negra no existe.'
    ]);
    exit;
}

$ncontrol = $resUser->fetch_object()->Ncontrol;
$getUser->close();

// 2. Verificar si hay algún préstamo con cuota >= 500
$checkLoan = $con->prepare("SELECT COUNT(*) AS total FROM loan WHERE Ncontrol = ? AND Cuota >= 500");
$checkLoan->bind_param("s", $ncontrol);
$checkLoan->execute();
$resCheck = $checkLoan->get_result();
$totalConCuotaAlta = $resCheck->fetch_object()->total;
$checkLoan->close();

// 3. Si tiene préstamos con cuota >= 500, no eliminar
if ($totalConCuotaAlta > 0) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'No se puede eliminar',
        'descripcion' => 'El usuario tiene préstamos con cuota mayor o igual a 500.'
    ]);
    exit;
}

// 4. Eliminar de listNeg
$deleteNeg = $con->prepare("DELETE FROM lisneg WHERE id_neg = ?");
$deleteNeg->bind_param("i", $idneg);

if ($deleteNeg->execute()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => 'Eliminado',
        'descripcion' => 'El usuario fue eliminado de la lista negra correctamente.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error al eliminar',
        'descripcion' => 'No se pudo eliminar la entrada de la lista negra.'
    ]);
}

$deleteNeg->close();
$con->close();
