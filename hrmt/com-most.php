<?php
// Mostrar errores para depuración (quítalo en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión
session_start();

// Incluir la conexión
include "../cont/conexion.php";

// Validar sesión
$id_usr = $_SESSION['matricula'] ?? null;

if (!$id_usr) {
    echo "<p class='text-danger'>Error: Usuario no autenticado.</p>";
    exit;
}

// Consulta corregida
$consulta = "
    SELECT 
        user.Ncontrol,
        user.Nombre,
        user.Perfil,
        comentarios.id_com,
        comentarios.Titulo,
        comentarios.Comentario
    FROM comentarios
    INNER JOIN user ON comentarios.Ncontrol = user.Ncontrol
    WHERE comentarios.Ncontrol = ?
";

// Preparar y validar
$stmt = $con->prepare($consulta);
if (!$stmt) {
    echo "<p class='text-danger'>Error al preparar la consulta: " . htmlspecialchars($con->error) . "</p>";
    exit;
}

$stmt->bind_param("i", $id_usr); 
$stmt->execute();
$rst = $stmt->get_result();

// Mostrar resultados
if ($rst->num_rows > 0) {
    while ($datos = $rst->fetch_object()) {
        ?>
        
        <div class="comment-card mb-4 border p-3 rounded shadow-sm">
          <div class="d-flex align-items-center mb-2">
            <img src="<?= htmlspecialchars($datos->Perfil) ?>" class="rounded-circle me-2" width="40" height="40" />
            <span class="text-muted small"><?= htmlspecialchars($datos->Nombre) ?></span>
          </div>
          <h5 class="fw-bold mb-1"><?= htmlspecialchars($datos->Titulo) ?></h5>
          <p class="mb-2 text-secondary"><?= nl2br(htmlspecialchars($datos->Comentario)) ?></p>
          <p style="display:none" id="id_com"><?= $datos->id_com ?></p>
          <div class="d-flex justify-content-end gap-2">
            <button class="btn btn-outline-danger btn-sm-custom" onclick="EliminarCom('<?= $datos->id_com ?>')">
              <i class="bi bi-trash"></i> Eliminar
            </button>
            <button class="btn btn-outline-secondary btn-sm-custom" onclick="ed_Comentario()">
              <i class="bi bi-pencil-square"></i> Editar
            </button>
          </div>
        </div>
        <?php
    }
} else {
    echo "<p class='text-muted'>No se encontraron comentarios.</p>";
}

?>
