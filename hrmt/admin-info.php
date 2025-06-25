<?php
include "../cont/conexion.php";

if (!isset($_POST['iddev']) || empty($_POST['iddev'])) {
    echo "No se recibió ningún número de control.";
    exit;
}

$idpres = $_POST['iddev'];
$sql = $con->prepare("
    SELECT 
        devol.id_pres,
        devol.Ncontrol,
        devol.tipo_dev,
        devol.Fcha_dev,
        devol.Devolucion,
        user.Nombre,
        user.Telefono,
        user.Email
    FROM devol
    INNER JOIN user ON devol.Ncontrol = user.Ncontrol
    WHERE devol.id_dev = ?
");
$sql->bind_param("i", $idpres);
$sql->execute();
$resultado = $sql->get_result();

if ($datos = $resultado->fetch_object()) {
?>
<div class="modal-dialog modal-dialog-centered">
  <div class="modal-content shadow-lg rounded-4 overflow-hidden">

    <!-- Encabezado -->
    <div class="modal-header bg-warning text-dark">
      <h5 class="modal-title">📑 Mostrar información</h5>
      <button type="button" class="btn-close" onclick="cerrar_edit()">x</button>
    </div>

    <!-- Cuerpo del formulario -->
    <form id="form-editar-libro">
      <div class="modal-body" id="modal-body-lib">

        <div class="mb-3">
          <label class="form-label fw-bold">Id de pedido</label>
          <p><?= htmlspecialchars($datos->id_pres) ?></p>        
        </div>

        <h5 class="modal-title">Datos de Usuario</h5>

        <div class="mb-3">
          <label class="form-label fw-bold">N° de control</label>
          <p><?= htmlspecialchars($datos->Ncontrol) ?></p>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Nombre del Usuario</label>
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

        <h5 class="modal-title">Información de la Devolución</h5>

        <div class="mb-3">
          <label class="form-label fw-bold">Fecha de devolución</label>
          <p><?= htmlspecialchars($datos->Fcha_dev) ?></p>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Devolución</label>
          <p><?= $datos->Devolucion == 1 ? '📚 Libro devuelto' : '📚 Libro no entregado' ?></p>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">Tipo de Devolución</label>
          <p><?= htmlspecialchars($datos->tipo_dev) ?></p>
        </div>

      </div>
    </form>

  </div>
</div>
<?php
}
?>
