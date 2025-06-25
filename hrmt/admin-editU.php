<?php
include "../cont/conexion.php";

if (!isset($_POST['ncontrol']) || empty($_POST['ncontrol'])) {
    echo "No se recibió ningún número de control.";
    exit;
}

$ncontrol = $_POST['ncontrol'];
$sql = $con->prepare("SELECT * FROM user WHERE Ncontrol = ?");
$sql->bind_param("i", $ncontrol);
$sql->execute();
$resultado = $sql->get_result();

if ($datos = $resultado->fetch_object()){
?>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 overflow-hidden">

      <!-- Encabezado -->
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">✏️ Editar Usuario</h5>
 <button type="button" class="btn-close" onclick="cerrar_edit()" >x</button>     
 </div>

      <!-- Cuerpo del formulario -->
      <form id="form-editar-libro">
        <div class="modal-body" id="modal-body-lib">
<div class="position-relative d-inline-block mb-4">
  <img id="preview-usuario" src="<?= htmlspecialchars($datos->Perfil) ?>" alt="Foto de Perfil" class="rounded-circle border border-3" width="120" height="120">
</div>

<div class="mb-3">
  <label for="ncontrol" class="form-label fw-bold">N° de Control</label>
<p><?= htmlspecialchars($datos->Ncontrol) ?></p>
</div>

<div class="mb-3">
  <label for="nombre" class="form-label fw-bold">Nombre completo</label>
  <input type="text" class="form-control" id="nombre-editar" value="<?= htmlspecialchars($datos->Nombre) ?>" required />
</div>

<div class="mb-3">
  <label for="email" class="form-label fw-bold">Correo electrónico</label>
  <input type="email" class="form-control" id="email-editar" value="<?= htmlspecialchars($datos->Email) ?>" required />
</div>

<div class="mb-3">
  <label for="telefono" class="form-label fw-bold">Teléfono</label>
  <input type="tel" class="form-control" id="telefono" value="<?= htmlspecialchars($datos->Telefono) ?>" required />
</div>

<div class="mb-3">
  <label for="tipoUsuario" class="form-label fw-bold">Tipo de usuario</label>
  <select id="tipoUsuario" class="form-select" required>
    <option value="">Seleccione una opción</option>
    <option value="Usuario" <?= $datos->Tipo == 'Usuario' ? 'selected' : '' ?>>Usuario</option>
    <option value="Bibliotecario" <?= $datos->Tipo == 'Bibliotecario' ? 'selected' : '' ?>>Bibliotecario</option>
    <option value="Administrador" <?= $datos->Tipo == 'Administrador' ? 'selected' : '' ?>>Administrador</option>
  </select>
</div>

<!-- Pie con botón -->
<div class="modal-footer justify-content-end">
  <button type="button" class="btn btn-warning w-100" onclick="guardarCambiosUsuario()">Guardar Cambios</button>
</div>
      </form>

    </div>
  </div>

<?php
}
?>
