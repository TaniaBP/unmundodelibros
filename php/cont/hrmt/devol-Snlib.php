<?php
header('Content-Type: application/json');
include "../cont/conexion.php";

// Asignar los valores recibidos
$cuota      = intval($_POST['cuota']);
$idpres     = $_POST['id_pres'];
$order_id   = $_POST['order_id'];
$payer_email= $_POST['payer_email'];
$status     = $_POST['status'];
$fcha_pag   = $_POST['fcha'];

// Validar que el estatus sea COMPLETED
if (strtoupper($status) !== "COMPLETED") {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => "Pago no procesado",
        'descripcion' => 'El pago no fue completado.'
    ]);
    exit;
}

// Verificar si el préstamo existe y asignar variables
$check_sql = "SELECT * FROM loan WHERE id_pres = ?";
$stmt = $con->prepare($check_sql);
$stmt->bind_param("i", $idpres);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $prestamo = $result->fetch_assoc();

    $ncontrol = $prestamo['Ncontrol'];
    $tipo_dev = $prestamo['Tipo_dev'] ?: 'paypal';
    $qr       = $prestamo['QR'];
    $isbn     = $prestamo['ISBN'];
    $devol    = 1;
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'preventivo',
        'titulo' => "No se encontró el préstamo",
        'descripcion' => 'No se puede procesar la devolución porque el préstamo no existe.'
    ]);
    exit;
}

// Insertar registro de pago
$insert_sql = "INSERT INTO pagos (id_pay, id_pres, Ncontrol, tipo_dev, Fcha_pag, Pago, Cuota) 
               VALUES (?, ?, ?, ?, ?, ?, ?)";
$insert_stmt = $con->prepare($insert_sql);
$insert_stmt->bind_param("sissssi", 
    $order_id,
    $idpres,
    $ncontrol,
    $tipo_dev,
    $fcha_pag,
    $status,
    $cuota
);

if (!$insert_stmt->execute()) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => "Pago no registrado",
        'descripcion' => 'Error al registrar el pago.'
    ]);
    exit;
}

// Borrar QR si existe
$filePath = "C:/xampp/htdocs" . $qr;
if (file_exists($filePath)) {
    unlink($filePath);
}

// Eliminar el préstamo
$delete_sql = "DELETE FROM loan WHERE id_pres = ?";
$delete_stmt = $con->prepare($delete_sql);
$delete_stmt->bind_param("i", $idpres);
$delete_stmt->execute();

if ($delete_stmt->affected_rows === 0) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => "No se eliminó el préstamo",
        'descripcion' => "La eliminación del préstamo falló. Revisa si existe o si hay restricciones en la base de datos."
    ]);
    exit;
}

// Actualizar stock del libro
$update_book_sql = "UPDATE book SET Existencia= Existencia -?, Prestamo = Prestamo - ? WHERE ISBN = ?";
$update_book_stmt = $con->prepare($update_book_sql);
$update_book_stmt->bind_param("iis", $devol, $devol, $isbn);
$update_book_stmt->execute();

// Respuesta final
echo json_encode([
    'success' => true,
    'tipo' => 'exito',
    'titulo' => "Libro Devuelto",
    'descripcion' => "Se registró el pago y devolución exitosamente."
]);

// Cerrar conexiones
$stmt->close();
$insert_stmt->close();
$update_book_stmt->close();
$delete_stmt->close();
$con->close();
?>
