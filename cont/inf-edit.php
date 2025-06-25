<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include "../cont/conexion.php";

// Validar sesión
if (!isset($_SESSION['matricula'])) {
    echo json_encode([
        'success' => false,
        'descripcion' => 'Sesión no iniciada.'
    ]);
    exit;
}

$id_usr = $_SESSION['matricula'];
$nombre = $_POST['nombre'] ?? '';
$email  = $_POST['email'] ?? '';
$tel    = $_POST['telefono'] ?? '';
$perfil = '';
$webPath = null;

$folder = "C:/xampp/htdocs/integ/perfil/";
$webBasePath = "/integ/perfil/";

if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

// Procesar imagen si se subió
if (isset($_FILES['perfil']) && $_FILES['perfil']['error'] === 0) {
    $mime = mime_content_type($_FILES['perfil']['tmp_name']);
    $ext = strtolower(pathinfo($_FILES['perfil']['name'], PATHINFO_EXTENSION));

    if (
        in_array($ext, ['jpg', 'jpeg', 'png']) &&
        in_array($mime, ['image/jpeg', 'image/pjpeg', 'image/png'])
    ) {
        $perfil = uniqid('perfil_', true) . '.' . $ext;
        $rutaDestino = $folder . $perfil;
        $webPath = $webBasePath . $perfil;

        if (!move_uploaded_file($_FILES['perfil']['tmp_name'], $rutaDestino)) {
            echo json_encode([
                'success' => false,
                'descripcion' => 'No se pudo mover la imagen.'
            ]);
            exit;
        }
    } else {
        echo json_encode([
            'success' => true,
            'tipo' => 'error',
            'titulo' =>'IMAGEN NO VALIDA' ,
            'descripcion' =>'La imagen no cumple con las caracteristicas.',
        ]);
        exit;
    }
} else {
    // No se subió imagen, usar la actual si existe
    $stmtImg = $con->prepare("SELECT Perfil FROM user WHERE Ncontrol = ?");
    $stmtImg->bind_param("i", $id_usr);
    $stmtImg->execute();
    $stmtImg->bind_result($webPath);
    $stmtImg->fetch();
    $stmtImg->close();

    if (!$webPath || trim($webPath) === '') {
        $webPath = $webBasePath . "usuario.png"; // Imagen por defecto
    }
}

// Ejecutar UPDATE
if ($stmt = $con->prepare("UPDATE user SET Nombre = ?, Email = ?, Telefono = ?, Perfil = ? WHERE Ncontrol = ?")) {
    $stmt->bind_param("ssssi", $nombre, $email, $tel, $webPath, $id_usr);
if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'tipo' => 'exito',
        'titulo' => 'Datos actualizados',
        'descripcion' => 'Todos los datos se han guardado exitosamente.',
        'imagen' => $webPath
    ]);
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Error',
        'descripcion' => 'Error al ejecutar el UPDATE.'
    ]);
}


} else {
    echo json_encode([
        'success' => false,
        'descripcion' => 'No se pudo preparar la consulta SQL.'
    ]);
}
?>
