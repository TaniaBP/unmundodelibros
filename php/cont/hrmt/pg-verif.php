<?php
session_start();
header('Content-Type: application/json');
include "../cont/conexion.php";

// Validar sesión
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
$cod = $_POST['codigo'] ?? '';

if (empty($cod)) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Datos incompletos',
        'descripcion' => 'Faltan datos para verificar.'
    ]);
    exit;
}

// Preparar consulta
$sql = $con->prepare("SELECT Tipo_dev, Cuota, id_pres FROM loan WHERE Validar = ? AND Ncontrol = ?");
if (!$sql) {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error en preparación',
        'descripcion' => 'No se pudo preparar la consulta.'
    ]);
    exit;
}

// Si $cod es texto y $id_usr es numérico: usa "si"
$sql->bind_param("si", $cod, $id_usr);

if ($sql->execute()) {
    $result = $sql->get_result();
    if ($result && $result->num_rows > 0) {
        $datos = $result->fetch_assoc();
        echo json_encode([
            // 'success' => true,
            // 'tipo' => 'exito',
            // 'titulo' => 'Verificación exitosa',
            // 'descripcion' => 'Datos encontrados correctamente.',
            'dev' => $datos['Tipo_dev'],
            'cuota' => $datos['Cuota'],
            'idpres' => $datos['id_pres']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'tipo' => 'error',
            'titulo' => 'No encontrado',
            'descripcion' => 'No se encontraron datos con el código y usuario proporcionados.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error al ejecutar',
        'descripcion' => 'No se pudo ejecutar la consulta.'
    ]);
}

$sql->close();
$con->close();

?>