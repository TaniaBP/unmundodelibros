function cerrarSesion() {
  fetch('../cont/cerrarsesion.php', { method: 'POST' })  // Ajusta la ruta según tu proyecto
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Redirige a la página deseada tras cerrar sesión
        window.location.href = '../../index.php';  // Cambia a la URL que quieras
      } else {
        alert('No se pudo cerrar sesión.');
      }
    })
    .catch(error => {
      console.error('Error al cerrar sesión:', error);
    });
}
 