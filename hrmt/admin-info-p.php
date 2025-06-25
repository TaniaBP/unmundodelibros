<?php
include "../cont/conexion.php";

if (!isset($_POST['idpag']) || empty($_POST['idpag'])) {
    echo "No se recibió ningún ID de pago.";
    exit;
}

$idpag = $_POST['idpag'];

$sql = $con->prepare("
    SELECT 
        pagos.id_pag,
         pagos.id_pay,
        pagos.id_pres,
        pagos.Ncontrol,
        pagos.tipo_dev,
        pagos.Fcha_pag,
         pagos.Pago,
        pagos.Cuota,
        user.Nombre,
        user.Telefono,
        user.Email
    FROM pagos
    INNER JOIN user ON pagos.Ncontrol = user.Ncontrol
    WHERE pagos.id_pag = ?
");

$sql->bind_param("i", $idpag);
$sql->execute();
$resultado = $sql->get_result();

if ($datos = $resultado->fetch_object()) {
?>
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content shadow-lg rounded-4 overflow-hidden">

    <!-- Encabezado -->
    <div class="modal-header bg-success text-white">
      <h5 class="modal-title">💳 Información del Pago</h5>
      <button type="button" class="btn-close" onclick="cerrar_edit()">x</button>
    </div>

    <!-- Cuerpo del formulario -->
    <form id="form-info-pago">
      <div class="modal-body" id="modal-body-pago">

        <div class="mb-3">
          <label class="form-label fw-bold">ID de Pago</label>
          <p><?= htmlspecialchars($datos->id_pag) ?></p>
        </div>
          <div class="mb-3">
          <label class="form-label fw-bold">ID de Paypal</label>
          <p><?= htmlspecialchars($datos->id_pay) ?></p>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">ID de Préstamo</label>
          <p><?= htmlspecialchars($datos->id_pres) ?></p>
        </div>

        <h5 class="modal-title">Datos del Usuario</h5>

        <div class="mb-3">
          <label class="form-label fw-bold">N° de Control</label>
          <p><?= htmlspecialchars($datos->Ncontrol) ?></p>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Nombre</label>
          <p><?= htmlspecialchars($datos->Nombre) ?></p>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Teléfono</label>
          <p><?= htmlspecialchars($datos->Telefono) ?></p>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Email</label>
          <p><?= htmlspecialchars($datos->Email) ?></p>
        </div>

        <h5 class="modal-title">Detalles del Pago</h5>

          <div class="mb-3">
          <label class="form-label fw-bold">Pago Realizado</label>
          <p><?= htmlspecialchars($datos->Pago) ?></p>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold">Fecha del Pago</label>
          <p><?= htmlspecialchars($datos->Fcha_pag) ?></p>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Monto pagado</label>
          <p>$<?= number_format($datos->Cuota, 2) ?></p>
        </div>

      </div>
    </form>

  </div>
</div>
<?php
}
?>
