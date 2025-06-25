
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

include "../cont/conexion.php";

// Validar sesión
if (!isset($_SESSION['matricula'])) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Sesión inválida',
        'descripcion' => 'No has iniciado sesión.'
    ]);
    exit;
}

$id_com = $_POST['valor'] ?? '';

if (empty($id_com) || !is_numeric($id_com)) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'ID inválido',
        'descripcion' => 'ID de comentario no válido.'
    ]);
    exit;
}

// Consulta
$sql = $con->prepare("SELECT Titulo, Comentario FROM comentarios WHERE Id_com = ?");
$sql->bind_param("i", $id_com);
$sql->execute();
$result = $sql->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'Titul' => $row['Titulo'],
        'com' => $row['Comentario']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'No encontrado',
        'descripcion' => 'No se encontró el comentario.'
    ]);
}
?>
