<?php
header('Content-Type: application/json');
include "../cont/conexion.php";

$query = trim($_POST['query'] ?? '');

if ($query === '') {
    echo json_encode(['success' => false, 'descripcion' => 'Consulta vacía.']);
    exit;
}

$query = "%" . $query . "%";

// --- Buscar Usuarios ---
$stmtUser = $con->prepare("SELECT Ncontrol, Nombre, Email, Telefono, Tipo FROM user 
                           WHERE Ncontrol LIKE ? OR Nombre LIKE ? OR Email LIKE ? OR Telefono LIKE ? OR Tipo LIKE ?");
$stmtUser->bind_param("sssss", $query, $query, $query, $query, $query);
$stmtUser->execute();
$resUser = $stmtUser->get_result();
$usuarios = $resUser->fetch_all(MYSQLI_ASSOC);

// --- Buscar Préstamos ---
$stmtLoan = $con->prepare("SELECT id_pres, Ncontrol, ISBN, Fcha_pres, Fcha_dev, Nom_qr, Prestamo, Cuota FROM loan 
                           WHERE Ncontrol LIKE ? OR ISBN LIKE ? OR Fcha_pres LIKE ? OR Fcha_dev LIKE ? OR Nom_qr LIKE ?");
$stmtLoan->bind_param("sssss", $query, $query, $query, $query, $query);
$stmtLoan->execute();
$resLoan = $stmtLoan->get_result();
$prestamos = [];
while ($row = $resLoan->fetch_assoc()) {
    $row['Estado'] = $row['Prestamo'] == 1 ? 'Libro Prestado' : 'Libro No Entregado';
    $prestamos[] = $row;
}

// --- Buscar Libros ---
$stmtBook = $con->prepare("SELECT ISBN, Nombre, Autor, Tipo, Existencia, Ubicacion FROM book 
                           WHERE ISBN LIKE ? OR Nombre LIKE ? OR Autor LIKE ? OR Tipo LIKE ? OR Existencia LIKE ? OR Ubicacion LIKE ?");
$stmtBook->bind_param("ssssss", $query, $query, $query, $query, $query, $query);
$stmtBook->execute();
$resBook = $stmtBook->get_result();
$libros = $resBook->fetch_all(MYSQLI_ASSOC);

// --- Buscar Pagos ---
$stmtPag = $con->prepare("SELECT id_pag, id_pres, Ncontrol, tipo_dev, Fcha_pag, Pago, Cuota FROM pagos 
                          WHERE Ncontrol LIKE ? OR tipo_dev LIKE ? OR Fcha_pag LIKE ? OR id_pres LIKE ? OR id_pay LIKE ?");
$stmtPag->bind_param("sssss", $query, $query, $query, $query,$query);
$stmtPag->execute();
$resPag = $stmtPag->get_result();
$pagos = [];
while ($row = $resPag->fetch_assoc()) {
    $row['EstadoPago'] = $row['Pago'] == 1 ? 'Pago realizado' : 'Pago no realizado';
    $pagos[] = $row;
}

// --- Buscar Devoluciones ---
$stmtDev = $con->prepare("SELECT id_dev, id_pres, Ncontrol, tipo_dev, Fcha_dev, Devolucion FROM devol 
                          WHERE id_pres LIKE ? OR Ncontrol LIKE ? OR tipo_dev LIKE ? OR Fcha_dev LIKE ? OR Devolucion LIKE ?");
$stmtDev->bind_param("sssss", $query, $query, $query, $query, $query);
$stmtDev->execute();
$resDev = $stmtDev->get_result();
$devoluciones = $resDev->fetch_all(MYSQLI_ASSOC);

// --- Respuesta JSON ---
echo json_encode([
    'success' => true,
    'usuarios' => $usuarios,
    'prestamos' => $prestamos,
    'libros' => $libros,
    'pagos' => $pagos,
    'devoluciones' => $devoluciones
]);