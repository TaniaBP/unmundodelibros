<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include "../cont/conexion.php";

// Costo diario por retraso
$cos_in = 20;

// Obtener todos los préstamos activos
$sql = $con->prepare("SELECT id_pres, Fcha_dev, Cuota, Ncontrol FROM loan WHERE Prestamo = 1");
if (!$sql) {
    echo json_encode([
        'success' => false,
        'error' => 'Error al preparar la consulta de préstamos: ' . $con->error
    ]);
    exit;
}
$sql->execute();
$result = $sql->get_result();

$respuestas = [];

while ($row = $result->fetch_object()) {
    $id_pres = $row->id_pres;
    $fecha_dev = $row->Fcha_dev;
    $cuota_actual = $row->Cuota;
    $ncontrol = $row->Ncontrol;

    // Verificar si el usuario ya está en la lista negra
    $checkNeg = $con->prepare("SELECT id_Neg FROM lisneg WHERE Ncontrol = ?");
    if (!$checkNeg) {
        echo json_encode([
            'success' => false,
            'error' => 'Error en prepare de lista negra: ' . $con->error
        ]);
        exit;
    }
    $checkNeg->bind_param("s", $ncontrol);
    $checkNeg->execute();
    $resNeg = $checkNeg->get_result();

    if ($resNeg->num_rows > 0) {
        // Usuario ya está en lista negra, saltar esta iteración
        $checkNeg->close();
        continue;
    }
    $checkNeg->close();

    // Validar que la fecha sea válida
    if ($fecha_dev !== '0000-00-00' && !empty($fecha_dev)) {
        $fecha_actual = new DateTime();
        $fecha_devolucion = new DateTime($fecha_dev);

        // Solo si la fecha ya pasó
        if ($fecha_actual > $fecha_devolucion) {
            // Calcular días de retraso
            $dias_retraso = $fecha_devolucion->diff($fecha_actual)->days;

            // Calcular cuota nueva
            $cuota_nueva = $dias_retraso * $cos_in;

            // Solo actualizar si la cuota nueva es mayor
            if ($cuota_nueva > $cuota_actual) {
                $update = $con->prepare("UPDATE loan SET Cuota = ? WHERE id_pres = ?");
                if (!$update) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'Error en prepare de UPDATE: ' . $con->error
                    ]);
                    exit;
                }
                $update->bind_param("di", $cuota_nueva, $id_pres);
                $update->execute();
                $update->close();
            }

            // Si la cuota llega a 500 o más, insertar en lista negra
            if ($cuota_nueva >= 500) {
                $insertNeg = $con->prepare("INSERT INTO lisNeg (Ncontrol) VALUES (?)");
                if (!$insertNeg) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'Error en prepare de insertNeg: ' . $con->error
                    ]);
                    exit;
                }
                $insertNeg->bind_param("s", $ncontrol);
                $insertNeg->execute();
                $insertNeg->close();
            }
        }
    }
}

// Devolver mensaje de éxito
echo json_encode([
    'success' => true,
    'mensaje' => 'Verificación y actualización completadas.'
]);

$con->close();
?>
