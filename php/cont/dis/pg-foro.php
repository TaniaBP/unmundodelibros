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
    <title>ChatRead</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Chat - WhatApp,una sala para compartir mensajes, audios, imagenes, videos entre muchascosas mas.">
    <meta name="author" content="Isaac">
    <meta name="keyword" content="Web Developer Isaac">
    <link rel="stylesheet" type="text/css" href="../../css/pg-foro.css">
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

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="menuNavbar">
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
          
                    <li class="nav-item d-md-none">
          <a class="nav-link" href="javascript:void(0);" onclick="mostrarComentarios()">Mis Comentarios</a>
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

<div class="wrapper ">

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


<!--DISEÑO DEL FORO -->
<!-- CONTENIDO -->
<div class="container-fluid px-5 mt-5 ">
  <div class="row">
    <!-- Publicaciones -->
    <div class="col-xl-8">
      <h2 class="mb-4 fw-bold text-primary">📚 Foro de Lectores</h2>

      <!-- Publicación -->
      <div class="post bg-white rounded p-4 mb-4 shadow-sm" id="post">
        <div class="d-flex align-items-center mb-2">
          <img src="https://i.pravatar.cc/40" class="rounded-circle me-2" width="40" height="40" />
          <span class="text-muted small">u/Any_Challenge · hace 7 h</span>
        </div>
        <h5 class="fw-bold mb-1">Libros para empezar a leer.</h5>
        <span class="badge bg-warning text-dark mb-2">Comentario</span>
        <p class="text-secondary">
          Hola! Tengo 22 años, solía leer mucho entre los 13 y 15 años pero me desconecté. ¿Qué libros me recomiendan para volver a empezar?
        </p>
      </div>
    </div>

    <!-- Columna derecha: formulario y comentarios personales -->
    <div class="col-xl-4">
      <!-- Formulario flotante -->

      <!-- Encabezado de mis comentarios -->
   <!-- Botones visibles solo después de llamada -->
<div id="seccion" class="bg-white shadow p-3 rounded d-none d-md-block">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold text-primary mb-0">📌 Mis Comentarios</h6>
    <button class="btn btn-success btn-sm" onclick="ab_Comentario()">
      <i class="bi bi-plus-circle"></i> Agregar Comentario
    </button>
  </div>
</div>


      <!-- Comentarios del usuario -->
      <div  id="seccion-comentarios" class="bg-white shadow p-3 rounded d-none d-md-block" >
          

      </div>
    </div>
  </div>
</div>
<!-- FORMULARIO DE NUEVA PUBLICACIÓN -->
<div class="modal show" tabindex="-1" id="nuevaPublicacion" style="z-index: 1055; background-color: rgba(0, 0, 0, 0.5);">
<?php
 $cons=("select Nombre from user where Ncontrol=?;");
                    $stmt = $con->prepare($cons);
                     $stmt->bind_param("i", $id_usr); 
                     $stmt->execute();
                     $rst = $stmt->get_result();
                     if($datos=$rst->fetch_object()){?> 
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 overflow-hidden">

      <!-- Encabezado -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">📝 Nueva Publicación</h5>
        <button type="button" class="btn-close btn-close-white" onclick="cerrar_edit()" aria-label="Cerrar"></button>
      </div>

      <!-- Cuerpo del formulario -->
      <div class="modal-body">
        <form id="form-publicacion">
          <div class="mb-3">
            <label for="usuario" class="form-label fw-bold">Usuario</label>
            <h5 id="user"><?=$datos->Nombre?></h5>
          </div>
          <div class="mb-3">
            <label for="titulo" class="form-label fw-bold">Título</label>
            <input type="text" class="form-control" id="titulo" placeholder="Ej. Recomendaciones de libros" required/>
          </div>
          <div class="mb-3">
            <label for="contenido" class="form-label fw-bold">Comentario</label>
            <textarea class="form-control" id="contenido" rows="4" placeholder="Escribe tu opinión o pregunta..." required ></textarea>
          </div>
        </form>
      </div>

      <!-- Pie con botón -->
      <div class="modal-footer justify-content-end">
        <button class="btn btn-primary w-100" onclick="publicar()">Publicar</button>
      </div>

    </div>
  </div>
  <?php
  }
  ?>
</div>
 
</div>



<!-- FORMULARIO DE editar -->
<div class="modal editar" tabindex="-1" id="editarPublicacion" style="z-index: 1055; background-color: rgba(0, 0, 0, 0.5);">
<?php
 $cons=("select Nombre from user where Ncontrol=?;");
                    $stmt = $con->prepare($cons);
                     $stmt->bind_param("i", $id_usr); 
                     $stmt->execute();
                     $rst = $stmt->get_result();
                     if($datos=$rst->fetch_object()){?> 
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg rounded-4 overflow-hidden">

      <!-- Encabezado -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">📝 Editar Publicación</h5>
        <button type="button" class="btn-close btn-close-white" onclick="cerrar_edit()" aria-label="Cerrar"></button>
      </div>

      <!-- Cuerpo del formulario -->
      <div class="modal-body">
        <form id="form-edit">
          <div class="mb-3">
            <label for="usuario" class="form-label fw-bold">Usuario</label>
            <h5 id="user"><?=$datos->Nombre?></h5>
          </div>
          <div class="mb-3">
            <label for="titulo" class="form-label fw-bold">Título</label>
            <input type="text" class="form-control" id="titul-ed" placeholder="Ej. Recomendaciones de libros" required/>
          </div>
          <div class="mb-3">
            <label for="contenido" class="form-label fw-bold">Comentario</label>
            <textarea class="form-control" id="conte-ed" rows="4" placeholder="Escribe tu opinión o pregunta..." required ></textarea>
          </div>
        </form>
      </div>

      <!-- Pie con botón -->
      <div class="modal-footer justify-content-end">
        <button class="btn btn-primary w-100" onclick="EditarCom()">Actualizar</button>
      </div>

    </div>
  </div>
  <?php
  }
  ?>
</div>
 
</div>
</div>

<!-- Footer -->
<footer class="footer pt-4 mt-5" id="foot">
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
    <script src="../../js/pg-foro.js"></script>
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