<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include "../cont/conexion.php";


$id_usr = $_SESSION['matricula'];
$contra = $_POST['contr'] ?? '';
$verif  = $_POST['verif'] ?? '';

// Validación: Longitud mínima
if (strlen($contra) < 8) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Contraseña inválida',
        'descripcion' => 'La contraseña debe tener al menos 8 caracteres.'
    ]);
    exit;
}

// Validación: Coincidencia
if ($contra !== $verif) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Las contraseñas no coinciden',
        'descripcion' => 'La contraseña y la verificación no son iguales.'
    ]);
    exit;
}

// Encriptar contraseña (recomendado)
// $hashed = password_hash($contra, PASSWORD_DEFAULT);

// Actualizar en base de datos
$stmt = $con->prepare("UPDATE user SET Password = ? WHERE Ncontrol = ?");
$stmt->bind_param("si", $contra, $id_usr);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => 'Contraseña actualizada',
        'descripcion' => 'Tu contraseña se ha cambiado correctamente.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error al actualizar',
        'descripcion' => 'Ocurrió un error al guardar la nueva contraseña.'
    ]);
}
?>
