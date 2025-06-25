<?php
session_start();
header('Content-Type: application/json'); // Forzar JSON
include "../cont/conexion.php";

// Validación básica
if (!isset($_SESSION['matricula'])) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Sesión no válida',
        'descripcion' => 'Usuario no autenticado'
    ]);
    exit;
}

$id_usr = $_SESSION['matricula'];
$titul  = $_POST['titul'] ?? '';
$comen  = $_POST['comen'] ?? '';
if (empty($titul) || empty($comen)) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Datos incompletos',
        'descripcion' => 'Faltan datos para actualizar el comentario.'
    ]);
    exit;
}

// Preparar sentencia
$sql = $con->prepare("INSERT INTO comentarios (Ncontrol, Titulo, Comentario) VALUES (?, ?, ?)");

if (!$sql) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error en preparación',
        'descripcion' => 'No se pudo preparar la consulta.'
    ]);
    exit;
}

$sql->bind_param("iss", $id_usr, $titul, $comen);

// Ejecutar y responder
if ($sql->execute()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => '¡Comentario exitoso!',
        'descripcion' => 'Nuevo comentario publicado.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error al comentar',
        'descripcion' => 'No se pudo guardar el comentario.'
    ]);
}
?>
