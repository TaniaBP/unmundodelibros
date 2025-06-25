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
    <link rel="stylesheet" type="text/css" href="../../css/pg-bookes.css">
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
        <div class="col-md-3 col-12 text-md-end text-center mt-1 mt-md-0">
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

                         
   <div class="wrapper container-fluid p-5">
      
<!-- Sin <form> -->
<div class="input-group">
  <input type="text" id="input-busqueda" class="form-control" placeholder="Buscar por nombre, ISBN, etc.">
  <button class="btn btn-primary" onclick="BuscarL()">
    <i class="bi bi-search"></i> Buscar
  </button>
</div>

<div id="contenedor-resultados"  style="margin-top: 50px;"></div>



<!--CONTENIDO DE LOS LIBROS-->
<div class="container-libros container" style="margin-top: 50px;" id="containerbb">
      
<?php
                     
                        $sql=$con->query("select * from book");
                        while($datos=$sql->fetch_object()){
                            $isbn=$datos-> ISBN;
                 ?>
   
  <!-- Vista móvil (xs a md) -->
  <div class="row d-md-none w-100 mb-4">
    <div class="col-12">
      <div class="p-3 rounded shadow-sm" style="background-color: white;">
        <h2 class="titulo-libro"><?=$datos->Nombre?></h2>
        <p class="autor-libro"><?=$datos->Autor?></p>
        <p class="autor-libro">Tipo: <?=$datos->Tipo?></p>
  <div class="d-flex justify-content-center gap-2 mt-3">
  <button class="btn-info p-0 border-0 bg-transparent" onclick="CargarInfo('<?=$datos->ISBN?>')">
    <img src="../../img/informacion.png" alt="Info" style="width: 20px; height: 20px;">
  </button>
<button class="btn-fav p-0 border-0 bg-transparent" onclick="cambiarImagen(event)" data-isbn="<?=$datos->ISBN?>">
  <img class="btn-fav-img" src="../../img/Cora-va.png" alt="Favorito" style="width: 20px; height: 20px;">
</button>

</div>

      </div>
    </div>
  </div>

  <!-- Vista de PC (md en adelante) -->
  <div class="d-none d-md-flex cont-libros flex-column align-items-center">
    <div class="img">
      <img src="<?=$datos->Portada?>" alt="Portada del libro">
    </div>
    <div class="dtos">
      <h2 class="titulo-libro"><?=$datos->Nombre?></h2>
      <p class="autor-libro"><?=$datos->Autor?></p>
    </div>
    <div class="buton">
      <button class="btn-info" onclick="CargarInfo('<?=$datos->ISBN?>')">
        <img src="../../img/informacion.png" alt="Info">
      </button>
 <button class="btn-fav p-0 border-0 bg-transparent" onclick="cambiarImagen(event)" data-isbn="<?=$datos->ISBN?>">
  <img class="btn-fav-img" src="../../img/Cora-va.png" alt="Favorito" style="width: 20px; height: 20px;">
</button>

    </div>
  </div>
  <?php
                }
                ?>
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

  <!--CHATBOT-->
  <div class="chat" onclick="toggleChat()">
    <img src="../../img/live-chat.ico" alt="Chat">
</div>

<!-- Contenedor del Chat -->
<form action="" method="POST">
    <div class="chat-box" id="chatBox">
        <div class="chat-header">
            <h3>Soporte en Línea</h3>
            <button class="close-btn" onclick="toggleChat()">X</button>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="message received">¡Hola! ¿En qué podemos ayudarte?</div>
        </div>
        <div class="chat-footer">
            <input type="text" placeholder="Escribe un mensaje..." id="chatInput" onkeypress="handleKeyPress(event)">
<button type="submit" class="send-btn" onclick="sendMessage(event)">Enviar</button>
            </div>
    </div>
</form>

<!-- Contenedor principal con Bootstrap y tu clase personalizada -->
<div class="container cont-info mt-2" id="lib">

  <!-- Encabezado del bloque -->
  <div class="row align-items-center justify-content-between info p-4 bg-light border rounded-top">
    <div class="col">
      <h1 class="h5 m-0">INFORMACION DEL LIBRO</h1>
    </div>
    <div class="col-auto cerrar">
      <a href="javascript:cerrar_li()" class="text-danger fs-4 fw-bold text-decoration-none">X</a>
    </div>
  </div>

  <!-- Cuerpo del contenido -->
  <div class="row g-3 bg-white border border-top-0 rounded-bottom p-3">

    <!-- Imagen del libro -->
    <div class="col-12 text-center">
      <img src="../../img/animales_fant.jpeg" alt="Foto de Perfil" class="picture img-fluid rounded mb-3" id="book-image" style="max-height: 300px;">
    </div>

    <!-- Detalles del libro -->
    <div class="col-12 infor text-center">
      <h2 id="book-name" class="mb-2"></h2>
      <p id="book-type" class="mb-1 text-muted"></p>
      <p id="book-author" class="mb-1"></p>
      <p id="book-description" class="mb-1"></p>
      <p id="book-isbn" class="mb-1 fw-semibold"></p>
    </div>

    <!-- Botón de acción -->
    <div class="col-12 but text-center">
      <input type="button" id="btn-generar-qr" value="Generar QR" class="btn btn-primary" onclick="MostrarQR()">
    </div>

  </div>
</div>

<!-- MOSTRAR QR -->
<div class="container mos shadow p-3 bg-white rounded position-fixed top-50 start-50 translate-middle w-100" id="mos" style="display: none; max-width: 400px; z-index: 1060;">
  <!-- Botón de cierre -->
  <div class="cerrar position-absolute top-0 end-0 p-2">
    <a href="javascript:cerrar_li()" class="text-danger fw-bold fs-4 text-decoration-none">X</a>
  </div>

  <!-- Título -->
  <h4 class="text-center mt-4 mb-3">QR</h4>

  <!-- Imagen y datos del QR -->
  <div class="QR text-center">
    <img id="book-imge" src="../../img/sen_ley.png" alt="QR" class="img-fluid mb-2" style="max-height: 250px;">
    <p id="qr-isbn" class="fw-semibold mb-1">ISBN no seleccionado</p>
    <p id="in" class="text-muted mb-3"></p>
  </div>

  <!-- Botón información -->
  <div class="text-center">
    <button class="bn-inf bg-transparent border-0 p-0" onclick="CargarInfoDesdeQR()">
      <img src="../../img/informacion.png" alt="Info" style="width: 24px; height: 24px;">
    </button>
  </div>
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
    <script src="../../js/pg-bookes.js"></script>
    <script src="../../js/buscar.js"></script>
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