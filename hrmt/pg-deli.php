<?php
include "../cont/conexion.php";

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Permite peticiones desde otros dominios si es necesario


// Validar parámetros requeridos
if (!isset($_POST['id']) || !isset($_POST['cos'])) {
          echo json_encode([
            'success' => false,
            'tipo' => 'prevencion',
            'titulo' => 'Campos Incompletos ',
            'descripcion' => 'Revisar los campos.',
        ]);
    exit;
}

$id_pres = (int)$_POST['id'];   // Convertir a entero
$cos = (int)$_POST['cos'];      // Convertir a entero

// Actualizar la cuota
$update = $con->prepare("UPDATE loan SET Cuota = ? WHERE id_pres = ?");
$update->bind_param("ii", $cos, $id_pres);
$update->execute();

// Verificar que se haya actualizado correctamente
$sql = $con->prepare("SELECT * FROM loan WHERE id_pres = ?");
$sql->bind_param("i", $id_pres);
$sql->execute();
$res = $sql->get_result();

if ($dts = $res->fetch_object()) {
           echo json_encode([
            'success' => true,
            'tipo' => 'exito',
            'titulo' => 'Cuota Actualizada',
            'descripcion' => 'Datos actualizados exitosamente.',
                "Cuotas" => $dts->Cuota
        ]);

} else {
         echo json_encode([
            'success' => false,
            'tipo' => 'error',
            'titulo' => 'Error de actualización',
            'descripcion' => 'No se actualizaron los datos.',
        ]);
}
?>
