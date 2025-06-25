<?php
session_start();
header('Content-Type: application/json');
include("conexion.php");

// Validar sesión
if (!isset($_SESSION['matricula'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "No autenticado"]);
    exit;
}

// Leer datos JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['isbn']) || !isset($data['favorito'])) {
    echo json_encode(["status" => "error", "message" => "Datos incompletos"]);
    exit;
}

$isbn = $data['isbn'];
$favorito = $data['favorito'];
$matricula = $_SESSION['matricula'];

try {
    if ($favorito) {
        $sql = $con->prepare("INSERT INTO reaction (Ncontrol, ISBN) VALUES (?, ?) 
                              ON DUPLICATE KEY UPDATE ISBN = ?");
        $sql->bind_param("iss", $matricula, $isbn, $isbn);
        $sql->execute();
    } else {
        $sql = $con->prepare("DELETE FROM reaction WHERE Ncontrol = ? AND ISBN = ?");
        $sql->bind_param("is", $matricula, $isbn);
        $sql->execute();
    }

    echo json_encode(["status" => "ok"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
