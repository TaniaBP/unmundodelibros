<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "../cont/conexion.php";

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_POST['id'])) {
    echo json_encode([
        'success' => false,
        'tipo' => 'prevencion',
        'titulo' => 'Verificar el Préstamo',
        'descripcion' => 'Favor de volver a leer el QR'
    ]);
    exit;
}

$id_pres = (int)$_POST['id'];

// Consultar préstamo
$sql = $con->prepare("SELECT * FROM loan WHERE id_pres = ?");
$sql->bind_param("i", $id_pres);
$sql->execute();
$res = $sql->get_result();

if (!$res || $res->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Verificar el Préstamo',
        'descripcion' => 'No se encontró el préstamo'
    ]);
    exit;
}

$dts = $res->fetch_object();

$id_usr = $dts->Ncontrol;
$cuota = isset($dts->Cuota) ? (int)$dts->Cuota : 0;
$tipo_dev_actual = $dts->Tipo_dev;
$codigo = rand(100000, 999999);

// Verificar si Tipo_dev está vacío
if (empty($tipo_dev_actual)) {
    $tipo = ($cuota === 0) ? "entrega" : "paypal";

    $update = $con->prepare("UPDATE loan SET Tipo_dev = ?, Validar = ? WHERE id_pres = ?");
    $update->bind_param("sii", $tipo, $codigo, $id_pres);
} else {
    // Si ya tiene tipo_dev, solo se actualiza el código
    $update = $con->prepare("UPDATE loan SET Validar = ? WHERE id_pres = ?");
    $update->bind_param("ii", $codigo, $id_pres);
}

if ($update->execute()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => 'Código Generado',
        'descripcion' => 'Código generado exitosamente.',
        'validar' => $codigo
    ]);
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error al actualizar',
        'descripcion' => 'No se pudo actualizar el préstamo'
    ]);
}
?>
