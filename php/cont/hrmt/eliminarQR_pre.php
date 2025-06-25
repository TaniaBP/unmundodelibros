<?php
include "../cont/conexion.php";

    $sql = $con->prepare("SELECT * FROM loan");
    $sql->execute();
    $result = $sql->get_result();

    while($row=$result->fetch_object()){
        $id_pres = $row->id_pres;
        $fecha_max = $row->Fcha_max;
        $qr=$row->QR;
    
        // Verifica que la fecha no sea 0000-00-00
        if ($fecha_max !== '0000-00-00') {
            // Compara con la fecha actual
            if (strtotime($fecha_max) < strtotime(date('Y-m-d'))) {
                // La fecha ya pasó, por ejemplo: eliminar el préstamo
                $con->query("DELETE FROM loan WHERE id_pres = $id_pres");

                $folder = "C:/xampp/htdocs";
                $filePath = $folder . $qr ;
                
                // Verificar si el archivo existe antes de borrarlo
                if (file_exists($filePath)) {
                    if (unlink($filePath)) {
                        echo "Imagen borrada exitosamente.";
                    } else {
                        echo "Error al borrar la imagen.";
                    }
                } else {
                    echo "El archivo no existe.";
                }
    
                echo "Eliminado préstamo con ID: $id_pres<br>";
            }
        }
    }
?>
