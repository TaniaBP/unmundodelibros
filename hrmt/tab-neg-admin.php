<?php
include "../cont/conexion.php";

// Inicia la salida con el thead
echo '
<thead class="table-info">
    <tr>
        <th>Ncontrol</th>
        <th>Nombre</th>
        <th style="color:#ff5f5f;">Eliminar</th>
    </tr>
</thead>
<tbody>
';

$sql = $con->query(" SELECT 
         lisneg.id_Neg,
    lisneg.Ncontrol,
    user.Nombre

    FROM lisneg
    INNER JOIN user ON lisneg.Ncontrol = user.Ncontrol
");
while ($datos = $sql->fetch_object()) {
    echo "<tr>
        <td>{$datos->Ncontrol}</td>
        <td>{$datos->Nombre}</td>
        <td>
            <a href=\"javascript:EliminarNeg('{$datos->id_Neg}')\" class='btn btn-sm btn-danger' title='Eliminar' >
                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' viewBox='0 0 24 24'>
                    <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                    <path d='M18.333 6a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-3.333 -4c1.094 0 1.828 .533 2.374 1.514a1 1 0 1 1 -1.748 .972c-.221 -.398 -.342 -.486 -.626 -.486h-10c-.548 0 -1 .452 -1 1v9.998c0 .32 .154 .618 .407 .805l.1 .065a1 1 0 1 1 -.99 1.738a3 3 0 0 1 -1.517 -2.606v-10c0 -1.652 1.348 -3 3 -3zm.8 8.786l-1.837 1.799l-1.749 -1.785a1 1 0 0 0 -1.319 -.096l-.095 .082a1 1 0 0 0 -.014 1.414l1.749 1.785l-1.835 1.8a1 1 0 0 0 -.096 1.32l.082 .095a1 1 0 0 0 1.414 .014l1.836 -1.8l1.75 1.786a1 1 0 0 0 1.319 .096l.095 -.082a1 1 0 0 0 .014 -1.414l-1.75 -1.786l1.836 -1.8a1 1 0 0 0 .096 -1.319l-.082 -.095a1 1 0 0 0 -1.414 -.014'/>
                </svg>
            </a>
        </td>
    </tr>";
}

echo '</tbody>';
?>

