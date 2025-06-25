<?php
include "../cont/conexion.php";

// Inicia la salida con el thead
echo '
<thead class="table-info">
    <tr>
        <th>Id de prestamo</th>
        <th>Ncontrol</th>
        <th>Tipo de  Devolucion</th>
        <th>Fecha de Pago</th>
        <th>Pago</th>
        <th>Cuota</th>
             <th style="color:blue;">Información</th>
    </tr>
</thead>
<tbody>
';

$sql = $con->query("SELECT * FROM pagos ORDER BY Cuota DESC;");
while ($datos = $sql->fetch_object()) {
    echo "<tr>
               
        <td>{$datos->id_pres}</td>
        <td>{$datos->Ncontrol}</td>
        <td>{$datos->tipo_dev}</td>
        <td>{$datos->Fcha_pag}</td>
        <td>" . ($datos->Pago == 1 ? 'Pago realizado' : 'Pago no realizado ') . "</td>
        <td> $ {$datos->Cuota}</td>
               <td>
    <a href=\"javascript:abPag('{$datos->id_pag}')\" class=\"btn btn-sm btn-warning\" title=\"Información\">
        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"icon icon-tabler icons-tabler-outline icon-tabler-file-info\">
            <path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/>
            <path d=\"M14 3v4a1 1 0 0 0 1 1h4\" />
            <path d=\"M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z\" />
            <path d=\"M11 14h1v4h1\" />
            <path d=\"M12 11h.01\" />
        </svg>
    </a>
</td>
    </tr>";
}

echo '</tbody>';
?>

