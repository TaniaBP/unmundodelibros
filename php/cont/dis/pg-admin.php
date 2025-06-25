<?php
session_start();

if (!isset($_SESSION['matricula'])) {
    // No hay sesión iniciada, redirige a login
    header("Location:'../../../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <link rel="stylesheet"  type="text/css" href="../../css/pg-admi.css">
    <link rel="stylesheet" href="../../css/alerta.css" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css" />
</head>
<body>
     <!-- Encabezado -->
  <header class="header py-1 bg-light border-bottom">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-md-9 col-12 d-flex flex-wrap align-items-center gap-1">
          <p class="mb-0">📞 (+52) 55-1234-1234</p>
          <p class="mb-0">📍 CDMX</p>
          <p class="mb-0">🏢 Sucursales: 12</p>
        </div>
        <div class="col-md-3 col-12 text-md-end text-center mt-2 mt-md-0">
          <span class="tit fs-4 fw-bold">Un Mundo de Libros</span>
        </div>
      </div>
    </div>
  </header>

  <!-- Menú de navegación responsive -->
  <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../../img/logo.png" alt="Logo" style="height: 40px;" class="me-2" />
        <span class="tit fs-5 fw-semibold">Biblioteca</span>
      </a>

      <!-- Botón hamburguesa -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar" aria-controls="menuNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Contenido del menú -->
      <div class="collapse navbar-collapse" id="menuNavbar">
        
      <!-- Links -->
        <ul class="navbar-nav mx-auto mb-2 mb-md-0">
          <li class="nav-item" >
            <a class="nav-link" href="javascript:cargarLibros()">Libros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="javascript:cargarPrestamos()">Prestamos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="javascript:cargarUsuarios()">Usuarios</a>
          </li>
                    <li class="nav-item">
            <a class="nav-link" href="javascript:cargarPagos()">Pagos</a>
          </li>
                    <li class="nav-item">
            <a class="nav-link" href="javascript:cargarDev()">Devoluciones</a>
          </li>
                    <li class="nav-item">
            <a class="nav-link" href="javascript:cargarNegras()">Lista Negra</a>
          </li>

                    <li class="nav-item">
            <a class="nav-link" href="javascript:cerrarSesion()" style="color:red;">Cerrar Sesion</a>
          </li>
          
        </ul>
<?php
 include "../cont/conexion.php";
                    $id_usr = $_SESSION['matricula'];
               
                    $cons=("select * from user where Ncontrol=?;");
                    $stmt = $con->prepare($cons);
                     $stmt->bind_param("i", $id_usr); 
                     $stmt->execute();
                     $rst = $stmt->get_result();
                     if($datos=$rst->fetch_object()){
?>
        <!-- Usuario -->
        <div class="d-flex align-items-center gap-2">
          <span class="fw-medium"><a id="usr" href="javascript:abrir_c()"><?=$datos-> Nombre?></a></span>
          <a href="javascript:abrir()"><img src="<?=$datos->Perfil?>" alt="User" style="height: 30px;" id="perfil"/></a>
        </div>
      </div>
    </div>
  </nav>

<?php
   }
   ?>
<!-- Vista solo en PC (pantallas grandes) -->
<div class="d-none d-lg-block">
  <div class="wrapper container-fluid p-5">
    
 <!-- Sin <form> -->
<div class="input-group">
  <input type="text" id="input-busqueda" class="form-control" placeholder="Buscar por nombre, ISBN, etc.">
  <button class="btn btn-primary" onclick="BuscarA()">
    <i class="bi bi-search"></i> Buscar
  </button>
</div>

<div id="contenedor-resultados"  style="margin-top: 50px;"></div>



    <!-- Botón Agregar libros -->
    <div class="d-flex justify-content-end my-3">
     <div class="buton">
      <button class="btn-info" onclick="btnAgr()">
        <img src="../../img/agregar.png" alt="QR"> Agregar Libro
      </button>
    </div>
    </div>

    <!-- Tabla -->
    <div class="container-fluid my-3 px-3">
      <div class="card shadow-sm w-100">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Listas</h5>
        </div>
        <div class="card-body p-0 tabla-container">
          <table class="table table-striped table-hover mb-0 w-100" id="tabla">
            <!-- Aquí se carga el contenido dinámico -->
          </table>
        </div>
      </div>
    </div>
            <!--MI CUENTA-->
  <!-- Ventana flotante -->
  <div class="modal show " tabindex="-1" id="cnta" style="z-index: 1055; background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 overflow-hidden">
        <!-- Encabezado -->
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Información de la Cuenta</h5>
          <button type="button" class="btn-close btn-close-white" onclick="cerrar_edit()" aria-label="Cerrar"></button>
        </div>

        <!-- Cuerpo -->
        <div class="modal-body text-center">
          <!-- Imagen editable -->
          <div class="position-relative d-inline-block mb-3">
            <img id="book-img" src="../../img/usuario.png" alt="Foto de Perfil" class="rounded-circle border border-3" width="120" height="120">
            <input id="file-upload" type="file" class="d-none" accept=".jpg, .jpeg, .png, image/jpeg, image/png">
          </div>

          <!-- Datos del usuario -->
          <h5 class="fw-bold" id="book-nom">NOMBRE COMPLETO</h5>
          <strong>Matrícula:<p id="book-mat"> 12345678</p></strong>
          <strong>Email:<p id="book-em"> correo@ejemplo.com</p></strong>
          <strong>Teléfono:<p id="book-tel"> 555-123-4567</p></strong>
        </div>

        <!-- Pie con botones -->
        <div class="modal-footer justify-content-between">
          <button class="btn btn-outline-primary" onclick="abr_edit()">Editar Información</button>
          <button class="btn btn-outline-warning" onclick="abr_con()">Cambiar Contraseña</button>
        </div>
      </div>
    </div>
  </div>

<!--EDITAR -->
<!-- Modal Editar Información -->
<div class="modal show" tabindex="-1" id="editar" style="z-index: 1055; background-color: rgba(0, 0, 0, 0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 overflow-hidden">

      <!-- Encabezado -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Editar Información</h5>
        <button type="button" class="btn-close btn-close-white" onclick="cerrar_edit()" aria-label="Cerrar"></button>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body">
        <div class="text-center">
          <!-- Imagen editable -->
          <div class="position-relative d-inline-block mb-4">
            <img  id="preview-img" src="../../img/usuario.png" alt="Foto de Perfil" class="rounded-circle border border-3" width="120" height="120">
            <label for="file-upload" class="btn btn-sm btn-success position-absolute bottom-0 end-0 rounded-circle p-">
              <i class="bi bi-pencil-fill">+</i>
            </label>
            <input id="file-upload" type="file" class="d-none" accept=".jpg, .jpeg, .png, image/jpeg, image/png">
          </div>
        </div>

        <!-- Formulario de edición -->
        <form id="form-editar">
          <div class="mb-3">
            <label for="nombre" class="form-label fw-bold">Nombre Completo</label>
            <input type="text" id="nombre" class="form-control" placeholder="Nombre completo">
          </div>

          <div class="mb-3">
            <label for="email" class="form-label fw-bold">Email</label>
            <input type="email" id="email" class="form-control" placeholder="correo@ejemplo.com">
          </div>

          <div class="mb-3">
            <label for="telefono" class="form-label fw-bold">Teléfono</label>
            <input type="text" id="telefono" class="form-control" placeholder="555-123-4567">
          </div>
        </form>
      </div>

      <!-- Pie con botones -->
      <div class="modal-footer justify-content-between">
        <button class="btn btn-outline-primary" onclick="edit_datos()">Guardar</button>
        <button class="btn btn-outline-warning" onclick="abr_con()">Cambiar Contraseña</button>
      </div>
    </div>
  </div>
</div>


<!-- CAMBIAR CONTRASEÑA -->
<div class="modal show" tabindex="-1" id="modal-cambiar" style="z-index: 1055; background-color: rgba(0, 0, 0, 0.5); display: none;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 overflow-hidden">

      <!-- Encabezado -->
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">Cambiar Contraseña</h5>
        <button type="button" class="btn-close" onclick="cerrar_edit()" aria-label="Cerrar"></button>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body">
        <form id="form-pass">
          <div class="mb-3">
            <label for="nuevaPass" class="form-label fw-bold">Nueva Contraseña</label>
            <input type="password" id="nuevaPass" class="form-control" placeholder="Ingresa nueva contraseña" required>
          </div>

          <div class="mb-3">
            <label for="verificaPass" class="form-label fw-bold">Confirmar Contraseña</label>
            <input type="password" id="verificaPass" class="form-control" placeholder="Repite la contraseña" required>
          </div>
        </form>
      </div>
    

      <!-- Pie con botones -->
      <div class="modal-footer justify-content-end">
        <button class="btn btn-outline-secondary me-2" onclick="cerrar_edit()">Cancelar</button>
        <button class="btn btn-warning" onclick="edit_con()">Guardar Contraseña</button>
      </div>
    </div>
  </div>
</div>

<!-- MENSAJES DE ALERTA -->
 <div id="contenedor-toast" style="position: fixed; top: 10px; right: 10px; z-index: 9999;"></div>

<!-- FORMULARIO DE NUEVA PUBLICACIÓN -->
<div class="modal show" tabindex="-1" id="nuevoLibro" style="z-index: 1055; background-color: rgba(0, 0, 0, 0.5);">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 overflow-hidden">

      <!-- Encabezado -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">📚 Nueva Libro</h5>
 <button type="button" class="btn-close" onclick="cerrar_edit()" aria-label="Cerrar"></button>      </div>

      <!-- Cuerpo del formulario -->
      <form id="form-publicacion">
        <div class="modal-body">

          <!-- Imagen editable -->
          <div class="position-relative d-inline-block mb-4 ">
            <img id="preview-por" src="../../img/usuario.png" alt="Foto de Perfil" class="rounded-circle border border-3" width="120" height="120">
            <label for="file-libro" class="btn btn-sm btn-success position-absolute bottom-0 end-0 rounded-circle">
              <i class="bi bi-pencil-fill">+</i>
            </label>
           <input id="file-libro" name="portada" type="file" class="d-none" accept=".jpg, .jpeg, .png, image/jpeg, image/png">
          </div>

          <div class="mb-3">
            <label for="titulo-libro" class="form-label fw-bold">Título del libro</label>
            <input type="text" class="form-control" id="titulo-libro" placeholder="Ej.Alicia en el Pais de las Maravillas" required />
          </div>

          <div class="mb-3">
            <label for="isbn" class="form-label fw-bold">ISBN</label>
            <input type="text" class="form-control" id="isbn" placeholder="Ej. 9783161484100" required />
          </div>

          <div class="mb-3">
            <label for="autor" class="form-label fw-bold">Autor</label>
            <input type="text" class="form-control" id="autor" placeholder="Ej. Gabriel García Márquez" required />
          </div>

          <div class="mb-3">
            <label for="tipoLibro" class="form-label fw-bold">Tipo de Libro</label>
            <select id="tipoLibro" class="form-select" required>
              <option value="">Seleccione una opción</option>
              <option value="Ciencias, Fisica, Matemáticas e Ingenieria">Ciencias, Fisica, Matemáticas e Ingenieria</option>
              <option value="Biologia,Quimica y Salud">Biologia,Quimica y Salud</option>
              <option value="Ciencias Sociales">Ciencias Sociales</option>
              <option value="Humanidad y Arte">Humanidad y Arte</option>
              <option value="Tecnología y Programación">Tecnología y Programación</option>
              <option value="Enciclopedias">Enciclopedias</option>
              <option value="Entretenimiento">Entretenimiento</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="existencias" class="form-label fw-bold">Libros en existencia</label>
            <input type="number" class="form-control" id="existencias" placeholder="Ej. 5" min="0" required />
          </div>

          <div class="mb-3">
            <label for="ubicacion" class="form-label fw-bold">Ubicación</label>
            <input type="text" class="form-control" id="ubicacion" placeholder="Ej. Estante 3B" required />
          </div>

          <div class="mb-3">
            <label for="contenido" class="form-label fw-bold">Descripcion</label>
            <textarea class="form-control" id="contenido" rows="4" placeholder="Escribe una breve descripcion del libro..." required></textarea>
          </div>

        </div>

        <!-- Pie con botón -->
        <div class="modal-footer justify-content-end">
          <button type="button" class="btn btn-primary w-100" onclick="AgregarLibro()">Agregar</button>
        </div>
      </form>

    </div>
  </div>
</div>



<!-- FORMULARIO PARA EDITAR LIBRO -->
<div class="modal show" tabindex="-1" id="editarD" style="z-index: 1055; background-color: rgba(0, 0, 0, 0.5); display:none;">
  
</div>




<!-- Final de lo que mostrara en PC -->
  </div>
</div>

<!-- Mensaje solo en móviles -->
<div class="d-block d-lg-none">
  <div id="msg-movil" class="alert alert-danger text-center mt-5" style="font-size: 1.2rem;">
    ⚠️ Este sistema no permite el acceso desde dispositivos móviles. Utiliza una computadora de escritorio o portátil.
  </div>
</div>
<!-- Footer -->
<footer class=" footer  pt-4 mt-5" id="foot">
  <div class="container">
    <div class="row text-center text-md-start">
      
      <!-- Contacto -->
      <div class="col-md-6 mb-4">
        <h5>Contáctanos</h5>
        <p><strong>Teléfono:</strong> +52 123 456 7890</p>
        <p><strong>Email:</strong> contacto@biblioteca.com</p>
        <p><strong>Dirección:</strong> Av. Universidad 123, Ciudad de México</p>
      </div>

      <!-- Redes Sociales -->
      <div class="col-md-6 mb-4">
        <h5>Síguenos</h5>
        <a href="https://es-la.facebook.com/" class="text-white me-3">
          <i class="bi bi-facebook fs-4">Facebook</i>
        </a>
        <a href="#" class="text-white me-3">
          <i class="bi bi-twitter-x fs-4">Twitter</i>
        </a>
        <a href="#" class="text-white me-3">
          <i class="bi bi-instagram fs-4">Instagram</i>
        </a>
      </div>
    </div>

    <hr class="bg-secondary">

    <!-- Pie inferior -->
    <div class="text-center pb-3">
      <p class="mb-0">&copy; 2024 Biblioteca Virtual. Todos los derechos reservados.</p>
    </div>
  </div>
</footer>
<!-- SCRIPT -->
    <script>
    const matricula = "<?php echo $_SESSION['matricula'] ?? ''; ?>";
    </script>
    <script src="../../js/alertas.js"></script>
    <script src="../../js/pg-adminis.js"></script>
    <script src="../../js/buscar.js"></script>
    <script src="../../js/cerrarsesion.js"></script>
    <script src="../../js/jquery-3.1.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap.bundle.mins.js"></script>
    <script src="../../js/menus.js"></script>   
    
</body>
</html>