<?php

include "../cont/conexion.php";
// Validar si se envió el ISBN
if (!isset($_POST['isbn']) || empty($_POST['isbn'])) {
    echo json_encode([
        'success' => false,
        'mensaje' => 'No se recibió ningún ISBN.'
    ]);
    exit;
}

$isbn = $_POST['isbn'];
// Preparar la consulta
$sql = $con->prepare("SELECT * FROM book WHERE ISBN = ?");
$sql->bind_param("s", $isbn);
$sql->execute();

// Obtener resultados
$resultado = $sql->get_result();
if ($datos = $resultado->fetch_object()) {
?>
 <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 overflow-hidden">

      <!-- Encabezado -->
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">✏️ Editar Libro</h5>
 <button type="button" class="btn-close" onclick="cerrar_edit()" >x</button>     
 </div>

      <!-- Cuerpo del formulario -->
      <form id="form-editar-libro">
        <div class="modal-body" id="modal-body-lib">

          <div class="position-relative d-inline-block mb-4 ">
            <img id="preview-por-edit" src="<?= nl2br(htmlspecialchars($datos->Portada)) ?>" alt="Portada del Libro" class="rounded-circle border border-3" width="120" height="120">
            <label for="file-libro-edit" class="btn btn-sm btn-warning position-absolute bottom-0 end-0 rounded-circle">
              <i class="bi bi-pencil-fill">✏️</i>
            </label>
            <input id="file-libro-edit" name="portada_edit" type="file" class="d-none" accept=".jpg, .jpeg, .png, image/jpeg, image/png">
          </div>

          <div class="mb-3">
            <label for="titulo-libro-edit" class="form-label fw-bold">Título del libro</label>
            <input type="text" class="form-control" id="titulo-libro-edit" value="<?= htmlspecialchars($datos->Nombre) ?>"  required  />
          </div>

          <div class="mb-3">
            <label for="isbn-edit" class="form-label fw-bold">ISBN</label>
            <input type="text" class="form-control" id="isbn-edit" value="<?= htmlspecialchars($datos->ISBN) ?>"  required />
          </div>

          <div class="mb-3">
            <label for="autor-edit" class="form-label fw-bold">Autor</label>
            <input type="text" class="form-control" id="autor-edit" value="<?= htmlspecialchars($datos->Autor) ?>" required />
          </div>

          <div class="mb-3">
            <label for="tipoLibro-edit" class="form-label fw-bold">Tipo de Libro</label>
            <select id="tipoLibro-edit" class="form-select" <?= nl2br(htmlspecialchars($datos->Tipo)) ?> required>
              <option value="">Seleccione una opción</option>
              <option value="Ciencias, Fisica, Matemáticas e Ingenieria" <?= $datos->Tipo == 'Ciencias, Fisica, Matemáticas e Ingenieria' ? 'selected' : '' ?>>Ciencias, Fisica, Matemáticas e Ingenieria</option>
              <option value="Biologia,Quimica y Salud"<?= $datos->Tipo == 'Biologia,Quimica y Salud' ? 'selected' : '' ?>>Biologia,Quimica y Salud</option>
              <option value="Ciencias Sociales"<?= $datos->Tipo == 'Ciencias Sociales' ? 'selected' : '' ?>>Ciencias Sociales</option>
              <option value="Humanidad y Arte"<?= $datos->Tipo == 'Humanidades y Arte ' ? 'selected' : '' ?>>Humanidad y Arte</option>
              <option value="Tecnología y Programación"<?= $datos->Tipo == 'Tecnología y Programación' ? 'selected' : '' ?>>Tecnología y Programación</option>
              <option value="Enciclopedias"<?= $datos->Tipo == 'Enciclopedia' ? 'selected' : '' ?>>Enciclopedias</option>
              <option value="Entretenimiento"<?= $datos->Tipo == 'Entretenimoento' ? 'selected' : '' ?>>Entretenimiento</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="existencias-edit" class="form-label fw-bold">Libros en existencia</label>
            <input type="text" class="form-control" id="existencias-edit" value="<?= nl2br(htmlspecialchars($datos->Existencia)) ?>" required />
          </div>

          <div class="mb-3">
            <label for="ubicacion-edit" class="form-label fw-bold">Ubicación</label>
            <input type="text" class="form-control" id="ubicacion-edit" value="<?= nl2br(htmlspecialchars($datos->Ubicacion)) ?> "required />
          </div>

          <div class="mb-3">
            <label for="contenido-edit" class="form-label fw-bold">Descripcion</label>
            <textarea class="form-control" id="contenido-edit" rows="4"required><?= nl2br(htmlspecialchars($datos->Descripcion)) ?> </textarea>
          </div>

        </div>

        <!-- Pie con botón -->
        <div class="modal-footer justify-content-end">
          <button type="button" class="btn btn-warning w-100" onclick="guardarEdicionLibro()">Guardar Cambios</button>
        </div>
      </form>

    </div>
  </div>
<?php
}
?>