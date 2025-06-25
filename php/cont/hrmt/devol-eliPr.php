<?php
session_start();
header('Content-Type: application/json');
include "../cont/conexion.php";

// Validar si se enviaron los datos necesarios
if (!isset($_POST['idpres'])) {

    exit;
}

$idpres = $_POST['idpres'];
$devol = 1;
$fcha_dev = date("Y-m-d");

// Verificar si el préstamo existe
$check_sql = "SELECT * FROM loan WHERE id_pres = ?";
$stmt = $con->prepare($check_sql);
$stmt->bind_param("i", $idpres);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {

    exit;
}

$prestamo = $result->fetch_assoc();

$id_pres   = $prestamo['id_pres'];
$isbn      = $prestamo['ISBN'];
$ncontrol  = $prestamo['Ncontrol'];
$tipo_dev  = 'entregar'; // Si siempre es este tipo
$qr   = $prestamo['QR'];   

// Insertar registro de devolución
$insert_sql = "INSERT INTO devol (id_pres, Ncontrol, tipo_dev, Fcha_dev, Devolucion) VALUES (?, ?, ?, ?, ?)";
$insert_stmt = $con->prepare($insert_sql);
$insert_stmt->bind_param("isssi", $idpres, $ncontrol, $tipo_dev, $fcha_dev, $devol);

if (!$insert_stmt->execute()) {
    exit;
}

 $folder = "C:/xampp/htdocs";
                $filePath = $folder . $qr ;
                
                // Verificar si el archivo existe antes de borrarlo
                if (file_exists($filePath)) {
                    if (unlink($filePath)) {
                      
                    } else {
                      
                    }
                } else {
                   
                }
// Actualizar stock en tabla book (sumar devolución al stock)
$update_book_sql = "UPDATE book SET Stock = Stock + ? WHERE ISBN = ?";
$update_book_stmt = $con->prepare($update_book_sql);
$update_book_stmt->bind_param("is", $devol, $isbn);
$update_book_stmt->execute();

// Actualizar préstamo (restar cantidad devuelta)
$update_loan_sql = "UPDATE book SET prestamo = prestamo - ? WHERE ISBN = ?";
$update_loan_stmt = $con->prepare($update_loan_sql);
$update_loan_stmt->bind_param("ii", $devol, $isbn);
$update_loan_stmt->execute();

// 🔥 Eliminar el préstamo si ya se devolvió todo
$delete_sql = "DELETE FROM loan WHERE id_pres = ?";
$delete_stmt = $con->prepare($delete_sql);
$delete_stmt->bind_param("i", $idpres);
$delete_stmt->execute();

echo json_encode([
    'success' => true,
    'tipo' => 'exito',
    'titulo' => "Libro Devuelto",
    'descripcion' => "Se registró la devolución, se actualizó el stock y se eliminó el préstamo."
]);

// Cerrar sentencias y conexión
$stmt->close();
$insert_stmt->close();
$update_book_stmt->close();
$update_loan_stmt->close();
$delete_stmt->close();
$con->close();
?>
