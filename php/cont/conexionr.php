<?php
$host = "localhost";
$dbname = "library";
$user = "root";
$pass = "";

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Establece el modo de error a excepciones
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    session_start(); // Inicia la sesión
    $id_usr = 0;

    // Opcional: puedes imprimir un mensaje si quieres verificar
    // echo "<h1>Conexión exitosa</h1>";
    
} catch (PDOException $e) {
    // En caso de error
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>
