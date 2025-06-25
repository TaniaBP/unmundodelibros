<?php
// Incluir librerías y conexión
// Incluir librerías y conexión
require_once '../../phpqrcode/qrlib.php';
include "../cont/conexion.php";

// Iniciar sesión y preparar cabeceras
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$matricula = $_SESSION['matricula'];

// Validar entrada
if (!isset($matricula) || empty($matricula)) {
    echo json_encode(["error" => "Faltan parámetros requeridos es"]);
    exit;
}



$isbn = $_POST['valor'];
// Variables de entrada
$fcha_sol = date("Y-m-d H:i:s");
$fcha_max = date("Y-m-d H:i:s", strtotime($fcha_sol . " +3 minutes"));

// Valores por defecto
$fcha_pres = "0000-00-00";
$fcha_dev = "0000-00-00";
$dev = "false";
$cuota = 0;
$prestamo = 0;
$qr = 0;
$nom_qr = 0;

// Insertar registro de préstamo
$sql = $con->prepare("
    INSERT INTO loan (Ncontrol, ISBN, Fcha_soli, Fcha_max, Fcha_pres, Fcha_dev, QR, Nom_qr, Devolucion, Prestamo, Cuota) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$sql->bind_param("ssssssssssi", $matricula, $isbn, $fcha_sol, $fcha_max, $fcha_pres, $fcha_dev, $qr, $nom_qr, $dev, $prestamo, $cuota);
$res = $sql->execute();

if (!$res) {
     echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error de parametros',
        'descripcion' => 'Error al registrar el préstamo.'
    ]);
    exit;
}

$id_generado = $con->insert_id;

// Generar código QR
$valor_qr = $id_generado . $isbn;
$folder = "C:/xampp/htdocs/integ/qrcodes/";

if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

$filePath = $folder . $valor_qr . ".png";
$webPath = "/integ/qrcodes/" . $valor_qr . ".png";

// Crear QR
QRcode::png($valor_qr, $filePath, 'L', 10);

// Actualizar registro con QR
$sql = $con->prepare("UPDATE loan SET QR = ?, Nom_qr = ? WHERE id_pres = ?");
$sql->bind_param("ssi", $webPath, $valor_qr, $id_generado);

if (!$sql->execute()) {
    echo json_encode(["error" => "Error al actualizar el QR."]);
    exit;
}

// Obtener y retornar los datos actualizados
$consulta = $con->prepare("SELECT * FROM loan WHERE id_pres = ?");
$consulta->bind_param("i", $id_generado);
$consulta->execute();
$rst = $consulta->get_result();

if ($datos = $rst->fetch_object()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => 'Codigo Generado',
        'descripcion' => 'Codigo generado exitosamente.',
        "id_pres" => $datos->id_pres,
        "qr"      => $datos->QR,
        "isbn"    => $datos->ISBN
    ]);
} else {
 echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Datos no encontrados',
        'descripcion' => 'No se encontraron datos necesarios.'
    ]);
}
?>
