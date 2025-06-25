<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include('../cont/conexion.php');


if (isset($_POST['matricula']) && isset($_POST['password'])) {
    $matricula = $_POST['matricula'];
    $password = $_POST['password'];

    // Verificar si el usuario está en lista negra
    $checkListaNegra = $con->prepare("SELECT id_neg FROM lisneg WHERE Ncontrol = ?");
    $checkListaNegra->bind_param("s", $matricula);
    $checkListaNegra->execute();
    $resLista = $checkListaNegra->get_result();

    if ($resLista->num_rows > 0) {
        // Usuario en lista negra
        echo json_encode([
            'success' => false,
            'tipo' => 'prevencion',
            'titulo' => 'Acceso restringido',
            'descripcion' => 'Tienes restricciones activas. Por favor acude con el administrador.'
        ]);
        exit;
    }

    // Si no está en lista negra, proceder con la autenticación
    $consulta = "SELECT * FROM user WHERE Ncontrol = ? AND Password = ?";
    $stmt = $con->prepare($consulta);
    $stmt->bind_param("ss", $matricula, $password);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($usuario = $resultado->fetch_object()) {
        $_SESSION['matricula'] = $usuario->Ncontrol;
        $_SESSION['tipo'] = $usuario->Tipo;
        $_SESSION['perfil'] = $usuario->Perfil;
        $_SESSION['nombre'] = $usuario->Nombre;
        $_SESSION['email_usr'] = $usuario->Email;

        echo json_encode([
            'success' => true,
            'tipo' => 'exito',
            'titulo' => '¡Bienvenido!',
            'descripcion' => 'Inicio de sesión exitoso.',
            'tipo_usuario' => $usuario->Tipo
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'tipo' => 'error',
            'titulo' => 'Credenciales inválidas',
            'descripcion' => 'La matrícula o contraseña son incorrectas.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Faltan datos',
        'descripcion' => 'Debes ingresar matrícula y contraseña.'
    ]);
}
?>
