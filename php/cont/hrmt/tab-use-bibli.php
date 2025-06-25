<?php
include "../cont/conexion.php";
// Inicia la salida con el thead
echo '
<thead class="table-info">
    <tr>
        <th>Ncontrol</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Telefono</th>
        <th>Tipo de Usuario</th>
    </tr>
</thead>
<tbody>
';
$sql = $con->query("SELECT * FROM user ORDER BY Tipo ASC");
while ($datos = $sql->fetch_object()) {
    echo "<tr>
        <td>{$datos->Ncontrol}</td>
        <td>{$datos->Nombre}</td>
        <td>{$datos->Email}</td>
        <td>{$datos->Telefono}</td>
        <td>{$datos->Tipo}</td>
      
       
    </tr>";
}
?>
