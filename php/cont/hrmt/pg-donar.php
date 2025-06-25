<?php
include "../hrmt/user.php";
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$id_pres = (int)$_POST['id']; 
$tipo="donar";


// Actualizar la cuota
$update = $con->prepare("UPDATE loan SET Tipo_dev = ? WHERE id_pres = ?");
$update->bind_param("si", $tipo, $id_pres);
$update->execute();

// Verificar que se haya actualizado correctamente
$sql = $con->prepare("SELECT * FROM loan WHERE id_pres = ?");
$sql->bind_param("i", $id_pres);
$sql->execute();
$res = $sql->get_result();

if ($dts = $res->fetch_object()) {
    $id_usr=$dts->id_usr;
    echo json_encode([
        "mensaje" => "Datos actualizados correctamente",
        "tipo" => $dts->Tipo_dev,
        "validar" => $dts->Validar
    ]);
} else {
    echo json_encode(["error" => "No se encontraron datos actualizados."]);
}
$sql = $con->prepare("INSERT INTO ");
$sql->bind_param("i", $id_pres);
$sql->execute();
$res = $sql->get_result();


?>