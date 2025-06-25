<?php
include "conexion.php";
session_start();


$tipo = $_GET['tipo'] ?? null;

if ($tipo) {
    $sql = $con->prepare("SELECT * FROM book WHERE Tipo = ?");
    $sql->bind_param("s", $tipo);
    $sql->execute();
    $result = $sql->get_result();

    while ($datos = $result->fetch_object()) {
        ?>
        <!-- Vista móvil (xs a md) -->
        <div class="row d-md-none w-100 mb-4">
            <div class="col-12">
                <div class="p-3 rounded shadow-sm" style="background-color: white;">
                    <h2 class="titulo-libro"><?= htmlspecialchars($datos->Nombre) ?></h2>
                    <p class="autor-libro"><?= htmlspecialchars($datos->Autor) ?></p>
                    <p class="autor-libro">Tipo: <?= htmlspecialchars($datos->Tipo) ?></p>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button class="btn-info p-0 border-0 bg-transparent" onclick="CargarInfo('<?= htmlspecialchars($datos->ISBN) ?>')">
                            <img src="../../img/informacion.png" alt="Info" style="width: 20px; height: 20px;">
                        </button>
                        <button class="btn-fav p-0 border-0 bg-transparent" onclick="cambiarImagen(event)">
                            <img class="btn-fav-img" src="../../img/Cora-va.png" alt="Favorito" style="width: 20px; height: 20px;">
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vista de PC (md en adelante) -->
        <div class="d-none d-md-flex cont-libros flex-column align-items-center">
            <div class="img">
                <img src="../../img/animales_fant.jpeg" alt="Portada del libro">
            </div>
            <div class="dtos">
                <h2 class="titulo-libro"><?= htmlspecialchars($datos->Nombre) ?></h2>
                <p class="autor-libro"><?= htmlspecialchars($datos->Autor) ?></p>
            </div>
            <div class="buton">
                <button class="btn-info" onclick="CargarInfo('<?= htmlspecialchars($datos->ISBN) ?>')">
                    <img src="../../img/informacion.png" alt="Info">
                </button>
                <button class="btn-fav" onclick="cambiarImagen(event)">
                    <img class="btn-fav-img" src="../../img/Cora-va.png" alt="Favorito">
                </button>
            </div>
        </div>
        <?php
    }
} else {
    echo "<p>No hay datos para mostrar o el usuario no está autenticado.</p>";
}
?>

<!-- Script adicional -->
<script src="../../js/pg-book-esp.js"></script>
