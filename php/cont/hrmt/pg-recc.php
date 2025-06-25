<?php
// Mostrar errores para depuración (quítalo en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Incluir la conexión
include "../cont/conexion.php";

// Obtener matrícula desde la sesión
$id_usr = isset($_SESSION['matricula']) ? $_SESSION['matricula'] : null;

if ($id_usr) {
    // Consulta corregida (sin coma al final del SELECT)
    $sql = $con->prepare("
        SELECT 
            reaction.id_reac,
            reaction.Ncontrol,
            reaction.ISBN,
            book.Nombre,
            book.Autor,
            book.Portada,
            book.Tipo
        FROM reaction
        INNER JOIN book ON reaction.ISBN = book.ISBN
        WHERE reaction.Ncontrol = ?
    ");
    
    $sql->bind_param("i", $id_usr);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        while ($datos = $result->fetch_object()) {
?>

<!-- Vista móvil (xs a md) -->
<div class="row d-md-none w-100 mb-4 ">
    <div class="col-12">
        <div class="p-3 rounded shadow-sm" style="background-color: white;">
            <h2 class="titulo-libro"><?= htmlspecialchars($datos->Nombre) ?></h2>
            <p class="autor-libro"><?= htmlspecialchars($datos->Autor) ?></p>
            <p class="autor-libro">Tipo: <?= htmlspecialchars($datos->Tipo) ?></p>
            <div class="d-flex justify-content-center gap-2 mt-3">
                <button class="btn-info p-0 border-0 bg-transparent" onclick="CargarInfo('<?= $datos->ISBN ?>')">
                    <img src="../../img/informacion.png" alt="Info" style="width: 20px; height: 20px;">
                </button>
                <button class="btn-fav p-0 border-0 bg-transparent" onclick="cambiarImagen(event)" data-isbn="<?= $datos->ISBN ?>">
                    <img class="btn-fav-img" src="../../img/Corazon.png" alt="Favorito" style="width: 20px; height: 20px;">
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Vista de PC (md en adelante) -->
<div class="d-none d-md-flex cont-libros flex-column align-items-center">
    <div class="img">
        <img src="<?= htmlspecialchars($datos->Portada) ?>" alt="Portada del libro">
    </div>
    <div class="dtos">
        <h2 class="titulo-libro"><?= htmlspecialchars($datos->Nombre) ?></h2>
        <p class="autor-libro"><?= htmlspecialchars($datos->Autor) ?></p>
    </div>
    <div class="buton">
        <button class="btn-info" onclick="CargarInfo('<?= $datos->ISBN ?>')">
            <img src="../../img/informacion.png" alt="Info">
        </button>
        <button class="btn-fav p-0 border-0 bg-transparent" onclick="cambiarImagen(event)" data-isbn="<?= $datos->ISBN ?>">
            <img class="btn-fav-img" src="../../img/Corazon.png" alt="Favorito" style="width: 20px; height: 20px;">
        </button>
    </div>
</div>

<?php
        }
    } else {
        echo "<p>No se encontraron libros registrados.</p>";
    }
} else {
    echo "<p>Error: Sesión no iniciada o matrícula no disponible.</p>";
}
?>
<script src="../../js/pg-reacion.js"></script>