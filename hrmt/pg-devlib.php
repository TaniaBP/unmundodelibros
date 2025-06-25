<?php
include "../cont/conexion.php";

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Validar que se haya enviado el ID
if (!isset($_POST['id'])) {
    echo json_encode(["error" => "Falta el parámetro del QR."]);
    exit;
}

$id_pres = (int) $_POST['id'];
$lib = 215; // Monto adicional a sumar

// Obtener la cuota actual
$sql = $con->prepare("SELECT Cuota FROM loan WHERE id_pres = ?");
$sql->bind_param("i", $id_pres);
$sql->execute();
$res = $sql->get_result();

if ($dts = $res->fetch_object()) {
    $cuo = (float) $dts->Cuota;
    $total = $cuo + $lib;

    // ✅ Actualizar la cuota Y el tipo de devolución
    $update = $con->prepare("UPDATE loan SET Cuota = ?, Tipo_dev = ? WHERE id_pres = ?");
    $tipo_dev = "entrega sin libro";
    $update->bind_param("dsi", $total, $tipo_dev, $id_pres);
    $update->execute();

    // Confirmar si se actualizó correctamente
    $sql = $con->prepare("SELECT * FROM loan WHERE id_pres = ?");
    $sql->bind_param("i", $id_pres);
    $sql->execute();
    $res = $sql->get_result();

    if ($dts = $res->fetch_object()) {
        echo json_encode([
            'success' => true,
            'tipo' => 'exito',
            'titulo' => 'Cuota Actualizada',
            'descripcion' => 'Cuota y tipo de devolución actualizados correctamente.',
            'Cuotas' => $dts->Cuota,
            'Tipo_dev' => $dts->Tipo_dev
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'tipo' => 'error',
            'titulo' => 'Sin actualización',
            'descripcion' => 'No se encontraron datos actualizados.'
        ]);
    }

} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error de Datos',
        'descripcion' => 'ID de préstamo no encontrado.'
    ]);
}
?>
