<?php
include "../cont/conexion.php";
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_POST['id'])) {
    echo json_encode(["success" => false, "error" => "Falta el parámetro del QR."]);
    exit;
}

$id_pres = $_POST['id'];
$fcha_pres = date("Y-m-d H:i:s");
$fcha_dev = date("Y-m-d H:i:s", strtotime($fcha_pres . " +8 days"));
$fcha_sol = "0000-00-00";
$fcha_max = "0000-00-00";
$pres = 1;

// 1. Obtener el ISBN y Ncontrol asociado al préstamo
$getDatos = $con->prepare("SELECT ISBN, Ncontrol FROM loan WHERE id_pres = ?");
$getDatos->bind_param("i", $id_pres);
$getDatos->execute();
$getDatos->store_result();

if ($getDatos->num_rows == 0) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Préstamo no encontrado',
        'descripcion' => 'No se encontró el préstamo con el ID proporcionado.'
    ]);
    exit;
}

$getDatos->bind_result($isbn, $ncontrol);
$getDatos->fetch();
$getDatos->close();

// 2. Verificar que el usuario no tenga más de 3 libros prestados
$verificaLimite = $con->prepare("SELECT COUNT(*) FROM loan WHERE Ncontrol = ? AND Prestamo = 1");
$verificaLimite->bind_param("i", $ncontrol);
$verificaLimite->execute();
$verificaLimite->bind_result($prestamos_activos);
$verificaLimite->fetch();
$verificaLimite->close();

if ($prestamos_activos > 3) {
    echo json_encode([
        'success' => false,
        'tipo' => 'warning',
        'titulo' => 'Límite alcanzado',
        'descripcion' => 'Solo puedes tener hasta 3 libros prestados simultáneamente.'
    ]);
    exit;
} 
    echo json_encode([
        'success' => true,
        'tipo' => 'info',
        'titulo' => 'Puedes pedir más libros',
        'descripcion' => "Actualmente tienes $prestamos_activos libro(s) prestado(s). Puedes pedir hasta " . (3 - $prestamos_activos) . " más."
    ]);


// 3. Verificar stock del libro
$consultaStock = $con->prepare("SELECT Stock FROM book WHERE ISBN = ?");
$consultaStock->bind_param("s", $isbn);
$consultaStock->execute();
$resultadoStock = $consultaStock->get_result();

if ($filaLibro = $resultadoStock->fetch_object()) {
    if ($filaLibro->Stock <= 0) {
        echo json_encode([
            'success' => false,
            'tipo' => 'warning',
            'titulo' => 'Libros agotados',
            'descripcion' => 'No hay libros disponibles para préstamo en este momento.'
        ]);
        exit;
    }

    // 4. Actualizar tabla loan
    $sql = $con->prepare("UPDATE loan SET Fcha_soli = ?, Fcha_max = ?, Fcha_pres = ?, Fcha_dev = ?, Prestamo = ? WHERE id_pres = ?");
    $sql->bind_param("ssssii", $fcha_sol, $fcha_max, $fcha_pres, $fcha_dev, $pres, $id_pres);
    if (!$sql->execute()) {
        echo json_encode([
            'success' => false,
            'tipo' => 'error',
            'titulo' => 'Error de actualización',
            'descripcion' => 'Error al actualizar el préstamo.'
        ]);
        exit;
    }

    // 5. Actualizar Stock y Prestamos del libro
    $updateLibro = $con->prepare("UPDATE book SET Stock = Stock - 1, Prestamos = Prestamos + 1 WHERE ISBN = ?");
    $updateLibro->bind_param("s", $isbn);
    $updateLibro->execute();

    // 6. Obtener los datos actualizados del préstamo
    $select = $con->prepare("SELECT Fcha_dev, Prestamo, Cuota FROM loan WHERE id_pres = ?");
    $select->bind_param("i", $id_pres);
    $select->execute();
    $result = $select->get_result();

    if ($datos = $result->fetch_object()) {
        echo json_encode([
            'success' => true,
            'tipo' => 'exito',
            'titulo' => 'Préstamo exitoso',
            'descripcion' => 'Préstamo realizado exitosamente.',
            'Fcha_dev' => $datos->Fcha_dev,
            'Prestamo' => $datos->Prestamo,
            'Cuota' => $datos->Cuota
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'tipo' => 'error',
            'titulo' => 'Verificación fallida',
            'descripcion' => 'No se pudo verificar el estado del préstamo.'
        ]);
    }

} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error de lectura',
        'descripcion' => 'No se pudo obtener información del libro.'
    ]);
}
?>
