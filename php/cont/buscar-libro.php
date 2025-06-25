<?php
header('Content-Type: application/json');
include "../cont/conexion.php";

$query = $_POST['query'] ?? '';

if (trim($query) === '') {
    echo json_encode(['success' => false, 'descripcion' => 'Consulta vacía.']);
    exit;
}

$sql = "SELECT ISBN, Nombre, Autor, Tipo, Portada FROM book 
        WHERE ISBN LIKE ? OR Nombre LIKE ? OR Autor LIKE ?";
$stmt = $con->prepare($sql);

$like = "%" . $query . "%";
$stmt->bind_param("sss", $like, $like, $like);
$stmt->execute();
$result = $stmt->get_result();

$libros = [];
while ($row = $result->fetch_assoc()) {
    $libros[] = $row;
}

echo json_encode([
    'success' => true,
    'resultados' => $libros
]);
?>
