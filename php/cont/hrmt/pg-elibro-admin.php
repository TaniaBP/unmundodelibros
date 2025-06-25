<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include "../cont/conexion.php";

// Verificar sesión activa
if (!isset($_SESSION['matricula'])) {
    echo json_encode([
        'success' => false,
        'descripcion' => 'Sesión no iniciada.'
    ]);
    exit;
}

// Datos del formulario
$id_usr   = $_SESSION['matricula'];
$titulo   = $_POST['titulo'] ?? '';
$isbn     = $_POST['isbn'] ?? '';
$autor    = $_POST['autor'] ?? '';
$exis     = intval($_POST['existencias'] ?? 0);
$ubi      = $_POST['ubicacion'] ?? '';
$conte    = $_POST['contenido'] ?? '';
$tipo     = $_POST['tipoLibro'] ?? '';
$pres     = 0;

$webPath  = null;

// Procesar imagen
$folder = "C:/xampp/htdocs/integ/portadas/";
$webBasePath = "/integ/portadas/";

if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

if (isset($_FILES['portada_edit']) && $_FILES['portada_edit']['error'] === 0) {
    $mime = mime_content_type($_FILES['portada_edit']['tmp_name']);
    $ext = strtolower(pathinfo($_FILES['portada_edit']['name'], PATHINFO_EXTENSION));

    if (
        in_array($ext, ['jpg', 'jpeg', 'png']) &&
        in_array($mime, ['image/jpeg', 'image/pjpeg', 'image/png'])
    ) {
        $nombreArchivo = uniqid('portada_', true) . '.' . $ext;
        $rutaDestino = $folder . $nombreArchivo;
        $webPath = $webBasePath . $nombreArchivo;

        if (!move_uploaded_file($_FILES['portada_edit']['tmp_name'], $rutaDestino)) {
            echo json_encode([
                'success' => false,
                'descripcion' => 'No se pudo mover la imagen al destino.',
                'rutaIntento' => $rutaDestino,
                'nombreTemporal' => $_FILES['portada_edit']['tmp_name'],
                'permisoCarpeta' => is_writable($folder) ? 'OK' : 'No se puede escribir en carpeta'
            ]);
            exit;
        }
    } else {
        echo json_encode([
            'success' => false,
            'tipo' => 'error',
            'titulo' => 'Imagen no válida',
            'descripcion' => 'La imagen no cumple con las características.'
        ]);
        exit;
    }
}

// Preparar consulta UPDATE
$sql = "UPDATE book 
        SET Nombre = ?, 
            Autor = ?, 
            Portada = IFNULL(?, Portada),
            Existencia = ?, 
            Tipo = ?, 
            Stock = ?, 
            Prestamo = ?, 
            Ubicacion = ?, 
            Descripcion = ?
        WHERE ISBN = ?";

if ($stmt = $con->prepare($sql)) {
    $stmt->bind_param(
        "sssissssss",
        $titulo,
        $autor,
        $webPath,
        $exis,
        $tipo,
        $exis,
        $pres,
        $ubi,
        $conte,
        $isbn
    );

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'tipo' => 'exito',
            'titulo' => 'Datos actualizados',
            'descripcion' => 'El libro ha sido actualizado correctamente.',
            'imagen' => $webPath
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'tipo' => 'error',
            'titulo' => 'Error al actualizar',
            'descripcion' => 'Error al ejecutar el UPDATE.',
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'descripcion' => 'No se pudo preparar la consulta SQL.'
    ]);
}

$con->close();
?>
