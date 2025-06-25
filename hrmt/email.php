<?php

// session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
include('../cont/conexion.php');

ini_set('display_errors', 1);



    $nv_pwds = mt_rand(1000, 9999); // Nueva contraseña aleatoria
    $email = $_POST['email'];
    $veri_email = $_POST['verif'];

    if ($email === $veri_email) {
        // Actualizar la contraseña en la base de datos
        $sql = $con->prepare("UPDATE user SET Password = ? WHERE Email = ?");
        $sql->bind_param("ss", $nv_pwds, $email);

        if ($sql->execute()) {
            // Email al usuario con su nueva contraseña
            $destino = $email;
            $asunto = 'Recuperación de contraseña - UN MUNDO DE LIBROS';

            $body = '
<html>
<head>
    <title>Recuperación de contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:rgb(195, 232, 248);
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            display: block;
            margin: auto;
            max-width: 150px;
        }
        .password-box {
            background-color: #ffe6e6;
            border: 1px solid #e74c3c;
            color: #c0392b;
            font-size: 1.5rem;
            font-weight: bold;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
    
        <h2 style="text-align: center; color: #2c3e50;">UN MUNDO DE LIBROS</h2>
        <p>Hola,</p>
        <p>Tu nueva contraseña temporal es:</p>
        <div class="password-box">' . $nv_pwds . '</div>
        <p>Por favor, cambia tu contraseña una vez que hayas iniciado sesión.</p>
        <div class="footer">
            <p>Atentamente,<br>El equipo de UN MUNDO DE LIBROS</p>
        </div>
    </div>
</body>
</html>';


            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: UN MUNDO DE LIBROS <noreply@unmundodelibros.com>\r\n";
            $headers .= "Return-path: $destino\r\n";

            if (mail($destino, $asunto, $body, $headers)) {
                echo json_encode([
                'success' => true,
                'tipo' => 'info',
                'titulo' => "Se envió una nueva contraseña.",
                'descripcion' => "El correo demora en llegar, favor de no generar otra contraseña."
            ]);
            } else {
                  echo json_encode([
                'success' => false,
                'tipo' => 'error',
                'titulo' => 'Error de envío',
                'descripcion' => 'No se pudo enviar el correo.'
            ]);
            }
        } else {
               echo json_encode([
            'success' => false,
            'tipo' => 'error',
            'titulo' => 'Error en la base de datos',
            'descripcion' => 'No se pudo actualizar la contraseña.'
        ]);
        }
    } else {
            echo json_encode([
        'success' => false,
        'tipo' => 'error',
        'titulo' => 'Correos no coinciden',
        'descripcion' => 'Los correos ingresados no coinciden.'
    ]);

    }

?>
