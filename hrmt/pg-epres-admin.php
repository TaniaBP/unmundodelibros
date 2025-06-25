<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include "../cont/conexion.php";

// Verificar sesión activa
if (!isset($_SESSION['matricula'])) {
    echo json_encode([
        'success' => false,
        'descripcion' => 'Sesión no iniciada.'
    ]);
    exit;
}

// Datos del formulario
$ncon  = $_POST['idpres'] ?? '';
$pres  = $_POST['pres'] ?? '';
$dev   = $_POST['dev'] ?? '';
$tipo  = $_POST['tipo'] ?? '';
$pag   = $_POST['pag'] ?? '';

// Preparar consulta UPDATE
$sql = "UPDATE loan
        SET Fcha_pres = ?, 
            Fcha_dev = ?, 
            Tipo_dev = ?, 
            Cuota = ?
        WHERE id_pres = ?";

if ($stmt = $con->prepare($sql)) {
    $stmt->bind_param("sssii", $pres, $dev, $tipo, $pag, $ncon);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'tipo' => 'exito',
            'titulo' => 'Datos actualizados',
            'descripcion' => 'Préstamo actualizado correctamente.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'tipo' => 'error',
            'titulo' => 'Error al actualizar',
            'descripcion' => 'Error al ejecutar el UPDATE.'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'descripcion' => 'No se pudo preparar la consulta SQL.'
    ]);
}

$con->close();
?>
