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
    <link rel="stylesheet" type="text/css" href="../../css/pg-pedidos.css">
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

 <!-- Contenido principal -->
<div class="wrapper container-fluid p-5">
  <div class="d-flex justify-content-end my-3">
    <div class="buton">
      <button class="btn-info" onclick="verif()">
        <img src="../../img/devolver.png" alt="QR"> Devolver
      </button>
    </div>
  </div>
  <div class="container-libros" id="container-libros"></div>
</div>

<!-- MOSTRAR QR -->
<div class="container mos shadow p-3 bg-white rounded position-fixed top-50 start-50 translate-middle w-100" id="mos" style="display: none; max-width: 400px; z-index: 1060;">
  <div class="cerrar position-absolute top-0 end-0 p-2">
    <a href="javascript:cerrar_li()" class="text-danger fw-bold fs-4 text-decoration-none">X</a>
  </div>
  <h4 class="text-center mt-4 mb-3">QR</h4>
  <div class="QR text-center">
    <img id="book-imge" src="../../img/sen_ley.png" alt="QR" class="img-fluid mb-2" style="max-height: 250px;">
    <p id="in" class="text-muted mb-3"></p>
  </div>
  <div class="text-center">
    <button class="bn-inf bg-transparent border-0" onclick="CargarInfoDesdeQR()">
      <img src="../../img/informacion.png" alt="Info" style="width: 24px; height: 24px;">
    </button>
  </div>
</div>

<!-- MI CUENTA -->
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
        <strong>Matrícula:<p id="book-mat">12345678</p></strong>
        <strong>Email:<p id="book-em">correo@ejemplo.com</p></strong>
        <strong>Teléfono:<p id="book-tel">555-123-4567</p></strong>
      </div>
      <div class="modal-footer justify-content-between">
        <button class="btn btn-outline-primary" onclick="abr_edit()">Editar Información</button>
        <button class="btn btn-outline-warning" onclick="abr_con()">Cambiar Contraseña</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL EDITAR INFORMACION -->
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
            <label for="file-upload" class="btn btn-sm btn-success position-absolute bottom-0 end-0 rounded-circle p-">
              <i class="bi bi-pencil-fill">+</i>
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

<!-- MODAL CAMBIAR CONTRASEÑA -->
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

<!-- VERIFICAR CODIGO -->
<div class="ver position-fixed top-50 start-50 translate-middle shadow" style="display:none;" id="Verificar">
  <div class="cerrar">
    <a href="javascript:cerrar_li()" class="btn-close" aria-label="Cerrar"></a>
  </div>
  <div class="ver-di text-center mb-4">
    <h2>VERIFICAR DEVOLUCIÓN</h2>
  </div>
  <form class="text-center">
    <div class="mb-3">
      <span>Código de verificación:</span><br>
      <input type="text" name="cod" class="form-control mt-2" id="codigo" required>
    </div>
    <input type="button" value="VERIFICAR" id="verif" onclick="verificarCodigo()">
  </form>
</div>

<!-- MODAL PAYPAL -->
<div class="modal show" tabindex="-1" id="modalPaypal" style="z-index: 1055; background-color: rgba(0, 0, 0, 0.5); display: none;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">💳 Pago con PayPal</h5>
        <button type="button" class="btn-close" onclick="cerrar_edit()" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body text-center">
        <p class="mb-4">Para continuar, realiza el pago correspondiente usando PayPal.</p>
        <div class="d-flex justify-content-center">
          <div id="paypal-button-container" class="w-100 px-2" style="max-width: 420px;"></div>
        </div>
      </div>
      <div class="modal-footer justify-content-center">
        <small class="text-muted">Tu información de pago está segura 🔐</small>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="footer pt-4 mt-5  text-black" style="position: absolute; bottom: 0; width: 100%;">
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
        <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
        <a href="#" class="text-white me-3"><i class="bi bi-twitter-x fs-4"></i></a>
        <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
      </div>
    </div>
    <hr class="bg-secondary">
    <div class="text-center pb-3">
      <p class="mb-0">&copy; 2024 Biblioteca Virtual. Todos los derechos reservados.</p>
    </div>
  </div>
</footer>

   <script>
    const matricula = "<?php echo $_SESSION['matricula'] ?? ''; ?>";
    </script>
    <script>
    const id_usr = <?= $id_usr ?>;
    </script>  
        <script src="https://www.paypal.com/sdk/js?client-id=AXl2hC5zlNarRgnXa6_U7WpkSDTdNze11oqtefH0fJKtlUYXFZKCbC9XMir_Un4ipy9Z0EKRsL1rb5PI&currency=MXN"></script> 
     <script src="../../js/alertas.js"></script>
    <script src="../../js/pg-pedido.js"></script>
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