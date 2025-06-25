<?php
// Mostrar errores para depuración (quítalo en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Incluir la conexión
include "../cont/conexion.php";

$id_com = $_POST['valor'] ?? '';

// Validar entrada
if (empty($id_com) || !is_numeric($id_com)) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'ID no válido',
        'descripcion' => 'ID de comentario faltante o incorrecto.'
    ]);
    exit;
}

// Preparar sentencia SQL corregida
$sql = $con->prepare("DELETE FROM comentarios WHERE Id_com = ? ");

if (!$sql) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error al preparar consulta',
        'descripcion' => 'No se pudo preparar la consulta SQL.'
    ]);
    exit;
}

// Vincular parámetros
$sql->bind_param("i", $id_com);

// Ejecutar consulta
if ($sql->execute()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => '¡Comentario eliminado!',
        'descripcion' => 'Comentario eliminado con éxito.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error al eliminar',
        'descripcion' => 'No se pudo eliminar el comentario.'
    ]);
}
?>
