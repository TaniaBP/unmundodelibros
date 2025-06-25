<?php
include "../cont/conexion.php";
// Inicia la salida con el thead
echo '
<thead class="table-info">
    <tr>
        <th>Ncontrol</th>
        <th>ISBN</th>
        <th>Fecha de prestamo</th>
        <th>Fecha de devolución</th>
        <th>QR</th>
        <th>Prestamos</th>
        <th>Cuotas</th>

</thead>
<tbody>
';
$sql = $con->query("SELECT * FROM loan ORDER BY Cuota DESC;");
while ($datos = $sql->fetch_object()) {
    echo "<tr>
     
        <td>{$datos->Ncontrol}</td>
        <td>{$datos->ISBN}</td>
        <td>{$datos->Fcha_pres}</td>
        <td>{$datos->Fcha_dev}</td>
        <td>{$datos->Nom_qr}</td>
        <td>" . ($datos->Prestamo == 1 ? 'Libro Prestado' : 'Libro No Entregado') . "</td>
        <td>$ {$datos->Cuota}.00</td>
    </tr>";
}
?>

