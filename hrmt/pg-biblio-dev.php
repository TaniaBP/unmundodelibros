<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include "../cont/conexion.php";

// Verifica si llegó el parámetro
if (!isset($_POST['id'])) {
    echo json_encode(["error" => "Falta el parámetro del QR."]);
    exit;
}

$id_pres = intval($_POST['id']); // Sanitiza entrada
$num_ver = mt_rand(1000, 99999); // Número aleatorio de validación

// Actualiza la tabla loan con el código de verificación
$update = $con->prepare("UPDATE loan SET Validar = ? WHERE id_pres = ?");
if (!$update) {
    echo json_encode(["error" => "Error al preparar UPDATE: " . $con->error]);
    exit;
}
$update->bind_param("ii", $num_ver, $id_pres);
if (!$update->execute()) {
    echo json_encode(["error" => "Error al ejecutar UPDATE: " . $update->error]);
    exit;
}

// Consulta los datos actualizados
$sql = $con->prepare("
    SELECT 
        user.Nombre ,
        user.Ncontrol,
        loan.Ncontrol,
        loan.ISBN,
        loan.Cuota,
        loan.Validar,
        book.Nombre AS Titulo,
        book.Autor
    FROM loan
    INNER JOIN book ON loan.ISBN = book.ISBN
    INNER JOIN user ON loan.Ncontrol = user.Ncontrol
    WHERE loan.id_pres = ?
");

if (!$sql) {
    echo json_encode(["error" => "Error al preparar SELECT: " . $con->error]);
    exit;
}

$sql->bind_param("i", $id_pres);
if (!$sql->execute()) {
    echo json_encode(["error" => "Error al ejecutar SELECT: " . $sql->error]);
    exit;
}

$res = $sql->get_result();
if ($dts = $res->fetch_object()) {
    echo json_encode([
        "mensaje" => "Datos encontrados",
        "Nombre" => $dts->NombreUsuario,
        "ISBN" => $dts->ISBN,
        "Cuotas" => $dts->Cuota,
        "Book" => $dts->Titulo,
        "Autor" => $dts->Autor,
        "Validar" => $dts->Validar
    ]);
} else {
    echo json_encode(["error" => "No se encontraron datos."]);
}
?>
