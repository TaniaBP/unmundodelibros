<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include "../cont/conexion.php";

// Validación del parámetro
if (!isset($_POST['qrValue']) || empty(trim($_POST['qrValue']))) {
    echo json_encode([
        'success' => false,
        'titulo' => 'Campos incompletos',
        'descripcion' => 'Revisar si los campos son válidos.'
    ]);
    exit;
}

$qr = trim($_POST['qrValue']);

// Consulta a la base de datos
$consulta = $con->prepare("
    SELECT 
        loan.id_pres,
        loan.Fcha_Max,
        loan.Fcha_dev,
        loan.Prestamo,
        loan.Cuota,
        book.Nombre, 
        book.Autor,
        book.Ubicacion,
        book.Existencia,
        book.Portada,
        book.Stock
    FROM loan
    INNER JOIN book ON loan.ISBN = book.ISBN
    WHERE loan.Nom_qr = ?
");

$consulta->bind_param("s", $qr);
$consulta->execute();
$result = $consulta->get_result();

if ($datos = $result->fetch_object()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => 'QR válido',
        'descripcion' => 'Petición de libros vigente.',
        'id_pres' => $datos->id_pres,
        'Nombre' => $datos->Nombre,
        'Autor' => $datos->Autor,
        'Prestamo' => $datos->Prestamo,
        'Portada' => $datos->Portada,
        'Peticion' => $datos->Fcha_Max,
        'Fcha_dev' => $datos->Fcha_dev,
        'Cuota' => $datos->Cuota,
        'stock' => $datos->Stock
    ]);
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'QR no válido',
        'descripcion' => 'Petición no disponible.'
    ]);
}
