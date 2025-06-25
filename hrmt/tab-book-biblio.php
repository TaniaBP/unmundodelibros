<?php
include "../cont/conexion.php";

// Inicia la salida con el thead
echo '
<thead class="table-info">
    <tr>
        <th>ISBN</th>
        <th>Nombre</th>
        <th>Existencias</th>
        <th>Tipo</th>
        <th>Stock</th>
        <th>Ubicación</th>
        <th>Descripción</th>

    </tr>
</thead>
<tbody>
';
$sql = $con->query("SELECT * FROM book ORDER BY Nombre ASC");
while ($datos= $sql->fetch_object()) {
    echo "<tr>
        <td>{$datos->ISBN}</td>
        <td>{$datos->Nombre}</td>
        <td>{$datos->Existencia}</td>
        <td>{$datos->Tipo}</td>
        <td>{$datos->Stock}</td>
        <td>{$datos->Ubicacion}</td>
        <td>{$datos->Descripcion}</td>
    </tr>";
}
?>
