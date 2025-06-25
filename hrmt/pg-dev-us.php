<?php
include "../cont/conexion.php"; // Ajusta según tu ruta
$id_pres = (int)$_POST['id']; 

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
    ]);
} else {
    echo json_encode(["error" => "No se encontraron datos actualizados."]);
}
?>