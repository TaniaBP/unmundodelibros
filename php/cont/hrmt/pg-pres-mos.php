<?php
include "../cont/conexion.php";

$id_usr = $_GET['id_usr'] ?? null;

if ($id_usr) {
    $sql = $con->prepare("SELECT 
        loan.id_pres,
        loan.Ncontrol,
        loan.ISBN,
        loan.Fcha_max,
        loan.Fcha_dev,
        loan.Fcha_soli,
        loan.Fcha_pres,
        loan.QR,
        book.Tipo,
        book.Nombre,
        book.Portada,
        book.Autor
    FROM loan
    INNER JOIN book ON loan.ISBN = book.ISBN
    WHERE loan.Ncontrol = ?");

    $sql->bind_param("s", $id_usr);
    $sql->execute();
    $result = $sql->get_result();

    while ($datos = $result->fetch_object()) {
        // Condicional para elegir la fecha y el título correcto
        $fecha_a_mostrar = ($datos->Fcha_max === "0000-00-00") ? $datos->Fcha_dev : $datos->Fcha_max;
        $titulo_fecha = ($datos->Fcha_max === "0000-00-00") ? "Fecha de devolución:" : "Fecha máxima:";
?>
<!-- Vista móvil (xs a md) -->
<div class="row d-md-none w-100 mb-4">
  <div class="col-12">
    <div class="p-3 rounded shadow-sm" style="background-color: white;">
      <h2 class="titulo-libro"><?= $datos->Nombre ?></h2>
      <p class="autor-libro"><?= $datos->Autor ?></p>
      <p class="autor-libro">Tipo: <?= $datos->Tipo ?></p>
      <h1 class="fmax1 text-danger"><?= $titulo_fecha ?></h1>
      <p class="fmax text-danger"><?= $fecha_a_mostrar ?></p>
      <div class="d-flex justify-content-center gap-2 mt-3">
        <button class="btn btn-info" onclick="MostrarQR('<?= $datos->id_pres ?>')">
          <img src="../../img/codigo-qr.png" alt="QR" style="width: 20px; height: 20px;"> Mostrar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Vista de PC (md en adelante) -->
<div class="d-none d-md-flex cont-libros flex-column align-items-center mb-4">
  <h2><?= $datos->Tipo ?></h2>
  <p id="id_pres" style="display:none;"><?= $datos->id_pres ?></p>

  <div class="img mb-3">
    <img src="<?= htmlspecialchars($datos->Portada) ?>" alt="Portada del libro" class="img-fluid">
  </div>

  <div class="dtos text-center mb-3">
    <h2 class="titulo-libro" style="font-size:25px;"><?= $datos->Nombre ?></h2>
    <p class="autor-libro" style="color:blue;font-size:20px;"><?= $datos->Autor ?></p>  
    <h1 class="fmax1 text-danger" style="font-size:26px;"><?= $titulo_fecha ?></h1>
    <p class="fmax text-danger" style="font-size:25px;"><?= $fecha_a_mostrar ?></p>
  </div>

  <div class="buton d-flex gap-2">
    <button class="btn btn-info" onclick="MostrarQR('<?= $datos->id_pres ?>')">
      <img src="../../img/codigo-qr.png" alt="QR"> Mostrar
    </button>
  </div>
</div>

<?php
    }
}
?>
<script src="../../js/pg-pedido.js"></script>
