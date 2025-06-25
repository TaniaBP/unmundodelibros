<?php
session_start();
 include "../cont/conexion.php";
    $id_usr = $_SESSION['matricula'];

    $cons=("select * from user where Ncontrol=?;");
        $stmt = $con->prepare($cons);
            $stmt->bind_param("i", $id_usr); 
            $stmt->execute();
            $rst = $stmt->get_result();
            if($datos=$rst->fetch_object()){
                echo json_encode([
                'success' => true,
                'mat' => $datos->Ncontrol,
                'nombre' => $datos->Nombre,
                'email' => $datos->Email,
                'tel'=>$datos->Telefono,
                'perfil'=>$datos->Perfil
            ]);
            }
?>