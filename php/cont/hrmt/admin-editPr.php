<?php
include "../cont/conexion.php";

if (!isset($_POST['idpres']) || empty($_POST['idpres'])) {
    echo "No se recibió ningún número de control.";
    exit;
}

$idpres = $_POST['idpres'];
$sql = $con->prepare("SELECT * FROM loan WHERE id_pres = ?");
$sql->bind_param("i", $idpres);
$sql->execute();
$resultado = $sql->get_result();

if ($datos = $resultado->fetch_object()) {
?>

<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content shadow-lg rounded-4 overflow-hidden">

    <!-- Encabezado -->
    <div class="modal-header bg-warning text-dark">
      <h5 class="modal-title">✏️ Editar Préstamo</h5>
      <button type="button" class="btn-close" onclick="cerrar_edit()">x</button>
    </div>

    <!-- Cuerpo del formulario -->
    <form id="form-editar-libro">
      <div class="modal-body" id="modal-body-lib">

        <div class="mb-3">
          <label for="ncontrol" class="form-label fw-bold">N° de Control</label>
          <p><?= htmlspecialchars($datos->Ncontrol) ?></p> 
          </div>

        <div class="mb-3">
          <label for="isbn-edit" class="form-label fw-bold">ISBN</label>
          <p><?= htmlspecialchars($datos->ISBN) ?></p> 
        </div>



        <div class="mb-3">
          <label for="prestamo" class="form-label fw-bold">Fecha de préstamo</label>
          <input type="date" class="form-control" id="prestamo" value="<?= date('Y-m-d', strtotime($datos->Fcha_pres)) ?>" required />
        </div>

        <div class="mb-3">
          <label for="devolucion" class="form-label fw-bold">Fecha de devolución</label>
          <input type="date" class="form-control" id="devolucion" value="<?= date('Y-m-d', strtotime($datos->Fcha_dev)) ?>"  required />
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Devolución</label>
          <p><?= $datos->Devolucion == 1 ? '📦 Libro devuelto' : '📚 Libro no entregado' ?></p>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Préstamo</label>
          <p><?= $datos->Prestamo == 1 ? '📦 Libro entregado' : '📚 Libro no entregado' ?></p>
        </div>

        <div class="mb-3">
          <label for="tipopag" class="form-label fw-bold">Tipo de Devolución</label>
          <select id="tipopag" class="form-select" required>
            <option value="">Seleccione una opción</option>
            <option value="entrega" <?= $datos->Tipo_dev == 'entrega' ? 'selected' : '' ?>>Entrega</option>
            <option value="paypal" <?= $datos->Tipo_dev == 'paypal' ? 'selected' : '' ?>>Paypal</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="pag" class="form-label fw-bold">Cuota</label>
          <input type="text" class="form-control" id="pag" value="<?= htmlspecialchars($datos->Cuota) ?>" required />
        </div>

      </div>

      <!-- Pie con botón -->
      <div class="modal-footer justify-content-end">
        <button type="button" class="btn btn-warning w-100" onclick="guardarCambiosPres(<?= $datos->id_pres ?>)">Guardar Cambios</button>
      </div>
    </form>

  </div>
</div>

<?php
}
?>
