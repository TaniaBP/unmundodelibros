<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include('../cont/conexion.php');


$matricula = $_POST['matricula'] ?? '';
$password = $_POST['password'] ?? '';
$email = $_POST['email'] ?? '';

// Valores por defecto
$nom = "Nuevo Usuario";
$tipo = "Usuario";
$tel = "0000000000"; // Cadena para bind_param con "s"
$perfil = "/integ/perfil/usuario.png"; // Ruta para mostrar en el navegador

// Verificar si la matrícula ya existe
$verificar = $con->prepare("SELECT Ncontrol FROM user WHERE Ncontrol = ?");
$verificar->bind_param("s", $matricula);
$verificar->execute();
$verificar->store_result();

if ($verificar->num_rows > 0) {
   echo json_encode([
        'success' => true,
        'tipo' => 'prevencion',
        'titulo' => 'Matricula existente',
        'descripcion' => 'Matris existente, favor de verificar'
    ]);
    $verificar->close();
    exit;
}else{
$verificar->close();

// Registrar nuevo usuario si no existe la matrícula
$sql = $con->prepare("INSERT INTO user (Ncontrol, Nombre, Email, Password,Perfil, Telefono, Tipo) VALUES (?, ?, ?, ?, ?, ?, ?)");
$sql->bind_param("sssssss", $matricula, $nom, $email, $password, $perfil, $tel, $tipo);

if ($sql->execute()) {
   echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => '¡Registro Exitoso!',
        'descripcion' => 'Nuevo usuario registrado.'
    ]);
} else {
    echo json_encode([
        'success' => true,
        'tipo' => 'error',
        'titulo' => 'Campos incompletos',
        'descripcion' => 'Verifica los campos.'
      
    ]);
}
}

?>
