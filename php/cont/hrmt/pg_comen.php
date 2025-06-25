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
    // Consulta para obtener los comentarios del usuario junto con información del libro y usuario
    $sql = $con->prepare("
        SELECT 
            user.Ncontrol,
            user.Nombre,
            user.Perfil,
            comentarios.Titulo,
            comentarios.Comentario
        FROM comentarios
        INNER JOIN user ON comentarios.Ncontrol = user.Ncontrol
    ");
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        while ($datos = $result->fetch_object()) {
?>
        <!-- Comentario -->
        <div class="comment-card mb-4 p-3 bg-light rounded shadow-sm">
          <div class="d-flex align-items-center mb-2">
            <img src="<?= htmlspecialchars($datos->Perfil) ?>" class="rounded-circle me-2" width="40" height="40" />
            <span class="text-muted small"><?= htmlspecialchars($datos->Nombre) ?></span>
          </div>
          <h5 class="fw-bold mb-1"><?= htmlspecialchars($datos->Titulo) ?></h5>
          <h6 class="text-secondary mb-1"></h6>
        <span class="badge bg-warning text-dark mb-2">Comentario</span>
          <p class="text-secondary"><?= nl2br(htmlspecialchars($datos->Comentario)) ?></p>
        </div>

<?php
        }
    } else {
        echo "<p class='text-muted'>No se encontraron comentarios registrados.</p>";
    }
} else {
    echo "<p class='text-danger'>Error: Sesión no iniciada o matrícula no disponible.</p>";
}
?>
