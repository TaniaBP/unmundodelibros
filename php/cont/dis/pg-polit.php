
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
    <link rel="stylesheet" type="text/css" href="../../css/pg-polit.css">
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
            <a class="nav-link" href="pg-libros.php">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pg-reaccion.php">Mis Favoritos </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pg-pedidos-u.php">Prestamos</a>
          </li>
                    <li class="nav-item">
            <a class="nav-link" href="pg-foro.php">Ideas en Papel</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pg-polit.php">Politicas</a>
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
          <span class="fw-medium"><a id="usr" href="javascript:abrir()"><?=$datos-> Nombre?></a></span>
          <a href="javascript:abrir()"><img src="<?=$datos->Perfil?>" alt="User" style="height: 30px;" id="perfil"/></a>
        </div>
      </div>
    </div>
  </nav>

<?php
   }
   ?>

  <div class="wrapper container-fluid p-4">

  <!-- MODALES -->
  <!-- Mi Cuenta -->
  <div class="modal show" tabindex="-1" id="cnta" style="z-index: 1055; background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Información de la Cuenta</h5>
          <button type="button" class="btn-close btn-close-white" onclick="cerrar_edit()" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body text-center">
          <div class="position-relative d-inline-block mb-3">
            <img id="book-img" src="../../img/usuario.png" alt="Foto de Perfil" class="rounded-circle border border-3" width="120" height="120">
            <input id="file-upload" type="file" class="d-none" accept=".jpg, .jpeg, .png, image/jpeg, image/png">
          </div>
          <h5 class="fw-bold" id="book-nom">NOMBRE COMPLETO</h5>
          <strong>Matrícula:</strong><p id="book-mat">12345678</p>
          <strong>Email:</strong><p id="book-em">correo@ejemplo.com</p>
          <strong>Teléfono:</strong><p id="book-tel">555-123-4567</p>
        </div>
        <div class="modal-footer justify-content-between">
          <button class="btn btn-outline-primary" onclick="abr_edit()">Editar Información</button>
          <button class="btn btn-outline-warning" onclick="abr_con()">Cambiar Contraseña</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Editar Información -->
  <div class="modal show" tabindex="-1" id="editar" style="z-index: 1055; background-color: rgba(0, 0, 0, 0.5);">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Editar Información</h5>
          <button type="button" class="btn-close btn-close-white" onclick="cerrar_edit()" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <div class="position-relative d-inline-block mb-4">
              <img id="preview-img" src="../../img/usuario.png" alt="Foto de Perfil" class="rounded-circle border border-3" width="120" height="120">
              <label for="file-upload" class="btn btn-sm btn-success position-absolute bottom-0 end-0 rounded-circle">
                <i class="bi bi-pencil-fill"></i>
              </label>
              <input id="file-upload" type="file" class="d-none" accept=".jpg, .jpeg, .png, image/jpeg, image/png">
            </div>
          </div>
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
        <div class="modal-footer justify-content-between">
          <button class="btn btn-outline-primary" onclick="edit_datos()">Guardar</button>
          <button class="btn btn-outline-warning" onclick="abr_con()">Cambiar Contraseña</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Cambiar Contraseña -->
  <div class="modal show" tabindex="-1" id="modal-cambiar" style="z-index: 1055; background-color: rgba(0, 0, 0, 0.5); display: none;">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Cambiar Contraseña</h5>
          <button type="button" class="btn-close" onclick="cerrar_edit()" aria-label="Cerrar"></button>
        </div>
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
        <div class="modal-footer justify-content-end">
          <button class="btn btn-outline-secondary me-2" onclick="cerrar_edit()">Cancelar</button>
          <button class="btn btn-warning" onclick="edit_con()">Guardar Contraseña</button>
        </div>
      </div>
    </div>
  </div>
  <!-- MENSAJES DE ALERTA -->
 <div id="contenedor-toast" style="position: fixed; top: 10px; right: 10px; z-index: 9999;"></div>


  <!-- Botones adicionales -->
<!-- Botones solo visibles en dispositivos móviles -->
<div class="d-flex justify-content-end gap-2 mt-5 d-md-none">
  <button class="btn btn-primary" onclick="mostrarTerminos()">
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
      stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-teams">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M3 7h10v10h-10z" />
      <path d="M6 10h4" />
      <path d="M8 10v4" />
      <path d="M8.104 17c.47 2.274 2.483 4 4.896 4a5 5 0 0 0 5 -5v-7h-5" />
      <path d="M18 18a4 4 0 0 0 4 -4v-5h-4" />
      <path d="M13.003 8.83a3 3 0 1 0 -1.833 -1.833" />
      <path d="M15.83 8.36a2.5 2.5 0 1 0 .594 -4.117" />
    </svg>
    Términos y Condiciones
  </button>

  <button class="btn btn-warning" onclick="mostrarDerechos()">
    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
      fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
      stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-copyright">
      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
      <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
      <path d="M14 9.75a3.016 3.016 0 0 0 -4.163 .173a2.993 2.993 0 0 0 0 4.154a3.016 3.016 0 0 0 4.163 .173" />
    </svg>
    Derechos de Autor
  </button>
</div>

  <!-- Contenido Términos -->
  <div class="row mt-5" id="terminos" style="font-size: 2.2rem;">
     <div class="col-12 col-md-10 offset-md-1" style="font-size: 1.2rem;">
      <div class="d-none d-md-block" style="font-size: 2.2rem;">
        <!-- Se aplica en pantallas medianas o más -->
      </div>
       <h2 class="mb-4 text-center fs-1">Términos y Condiciones de la Biblioteca</h2>

    <p class="text-justify">
      <strong>1. Uso del Software</strong><br>
      El sistema <strong>"Un Mundo de Libros"</strong> es una plataforma digital destinada a la gestión de préstamos, devoluciones y control de inventario de libros. Todo usuario registrado acepta utilizar el software únicamente con fines académicos y conforme a las políticas institucionales. El mal uso del sistema o intento de alteración será motivo de suspensión del servicio.
    </p>

    <p class="text-justify">
      <strong>2. Registro de Usuarios</strong><br>
      Para acceder a los servicios de préstamo es obligatorio estar registrado con nombre completo, matrícula o número de identificación institucional, correo electrónico y teléfono vigente.
    </p>

    <p class="text-justify">
      <strong>3. Préstamos de Libros</strong><br>
      Cada usuario podrá solicitar préstamos de libros con una duración máxima de <strong>8 días hábiles</strong>, salvo excepciones establecidas por la administración de la biblioteca. El número máximo de libros prestados simultáneamente es de <strong>3 unidades</strong>.
    </p>

    <p class="text-justify">
      <strong>4. Penalizaciones</strong><br>
      En caso de no devolver un libro en la fecha establecida, se aplicará una penalización de <strong>$20.00 MXN</strong> por cada día de retraso, sin excepción. El monto acumulado deberá ser cubierto antes de realizar nuevos préstamos.
    </p>

    <p class="text-justify">
      <strong>5. Extravío o Daños</strong><br>
      Si el usuario pierde el libro, lo daña irreparablemente o no lo entrega por estar "extraviado", "en casa", o fuera de su control, deberá pagar una <strong>comisión de reposición de $215.00 MXN</strong> por cada ejemplar no devuelto. Esta penalización no exime de las sanciones académicas si aplican.
    </p>

    <p class="text-justify">
      <strong>6. Restricciones</strong><br>
      Los libros deben conservarse en buen estado. Está prohibido subrayar, rayar, doblar páginas, romper o modificar cualquier contenido del material. El incumplimiento podrá ocasionar la suspensión temporal o definitiva del servicio.
    </p>

    <p class="text-justify">
      <strong>7. Contacto</strong><br>
      Para aclaraciones, dudas o soporte técnico del software, comuníquese a:<br>
      <strong>Email:</strong> teamstesci@gmail.com<br>
      <strong>Sitio web:</strong> Un Mundo de Libros
    </p>
    </div>
  </div> 

<!-- Contenido Derechos de autor -->
<div class="row mt-5 d-none d-md-flex" id="derechos" style="font-size: 2.2rem;">
    <div class="col-12 col-md-10 offset-md-1" style="font-size: 1.2rem;">
    <div class="d-none d-md-block" style="font-size: 2.2rem;"></div>
    <h2 class="mb-4 text-center fs-1">Derechos de Autor</h2>
    
       <p class="text-justify">
        <strong>Derechos de Autor</strong><br>
        <strong>Nombre del Software:</strong>Un Mundo de Libros<br>
      </p>
      <p class="text-justify">Todos los derechos reservados.</p>
      <p class="text-justify">
        Este software y su código fuente, interfaces gráficas, diseño, funcionalidades, documentación técnica y cualquier otro contenido asociado son propiedad intelectual de [Nombre del Autor o Empresa], y están protegidos por las leyes nacionales e internacionales de propiedad intelectual y derechos de autor.
      </p>
      <p class="text-justify">
        Está prohibida la reproducción, distribución, comunicación pública, transformación, ingeniería inversa o cualquier uso no autorizado de este software total o parcialmente, sin el consentimiento expreso y por escrito del titular de los derechos.
      </p>
      <p class="text-justify">
        Este software ha sido desarrollado con fines de gestión bibliotecaria, permitiendo el control de préstamos, catálogo de libros, registros de usuarios, y demás funcionalidades específicas. Cualquier uso fuera del ámbito autorizado podrá constituir una infracción de los derechos del titular.
      </p>

      <p class="text-justify">
        <strong>Para obtener permisos, soporte técnico o informes de errores, comuníquese con:</strong><br>
        <strong>Correo electrónico:</strong> teamstesci@gmail.com<br>
        <strong>Sitio web:</strong> Un mundo de Libros
      </p>
  </div>
  </div>

</div>

<!-- Footer -->
<footer class="footer pt-4 mt-5 ">
  <div class="container">
    <div class="row text-center text-md-start">
      <div class="col-md-6 mb-4">
        <h5>Contáctanos</h5>
        <p><strong>Teléfono:</strong> +52 123 456 7890</p>
        <p><strong>Email:</strong> contacto@biblioteca.com</p>
        <p><strong>Dirección:</strong> Av. Universidad 123, Ciudad de México</p>
      </div>
      <div class="col-md-6 mb-4">
        <h5>Síguenos</h5>
        <a href="https://es-la.facebook.com/" class="text-white me-3">
          <i class="bi bi-facebook fs-4"></i>
        </a>
        <a href="#" class="text-white me-3">
          <i class="bi bi-twitter-x fs-4"></i>
        </a>
        <a href="#" class="text-white me-3">
          <i class="bi bi-instagram fs-4"></i>
        </a>
      </div>
    </div>
    <hr class="bg-secondary">
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
    <script src="../../js/pg-polit.js"></script>
    <script src="../../js/cerrarsesion.js"></script>
    <script src="../../js/jquery-3.1.1.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/bootstrap.bundle.mins.js"></script>
    <script src="../../js/menus.js"></script>  
    
 <!-- chatbot -->
    <script type="text/javascript">
  (function(d, t) {
      var v = d.createElement(t), s = d.getElementsByTagName(t)[0];
      v.onload = function() {
        window.voiceflow.chat.load({
          verify: { projectID: '685902ec210563ada942d39e' },
          url: 'https://general-runtime.voiceflow.com',
          versionID: '685902ec210563ada942d39f',
          voice: {
            url: "https://runtime-api.voiceflow.com"
          }
        });
      }
      v.src = "https://cdn.voiceflow.com/widget-next/bundle.mjs"; v.type = "text/javascript"; s.parentNode.insertBefore(v, s);
  })(document, 'script');
</script>
    
</body>
</html>