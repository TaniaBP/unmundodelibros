<?php
include "../cont/conexion.php";

if (isset($_POST['id_pres'])) {
    $id_pres = $_POST['id_pres'];

    $sql = $con->prepare("SELECT QR FROM loan WHERE id_pres = ?");
    $sql->bind_param("i", $id_pres);
    $sql->execute();
    $result = $sql->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'success' => true,
        'tipo' => 'exito',
        'titulo' => 'QR vigente',
        'descripcion' => 'Tu codigo QR todavia es.',
            'valor_qr' => $id_pres,
            'qr' => $row['QR'] // Aquí se espera que contenga la URL de la imagen del QR
        ]);
    } else {
        echo json_encode(['error' => 'No se encontró QR']);
    }
} else {
    echo json_encode(['error' => 'Falta id_pres']);
}
?>
