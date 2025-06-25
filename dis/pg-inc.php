
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Un Mundo de Libros</title>
  <link rel="stylesheet" href="css/pg-inc.css" />
    <link rel="stylesheet" href="css/alerta.css" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
</head>
<body>

<!-- Encabezado -->
 
  <!-- Encabezado -->
  <header class="header py-1 bg-light border-bottom">
    <div class="container-fluid">
      <div class="row align-items-center">
        <div class="col-md-9 col-12 d-flex flex-wrap align-items-center gap-3">
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
        <img src="img/logo.png" alt="Logo" style="height: 40px;" class="me-2" />
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
          <li class="nav-item">
            <a class="nav-link" href="#bienve">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#biblio">Bibliotecarios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#ser">Servicios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#foot">Contacto</a>
          </li>
        </ul>

        <!-- Usuario -->
        <div class="d-flex align-items-center gap-2">
          <span class="fw-medium"><a href="javascript:abrir()">REGISTRATE</a></span>
          <a href="javascript:abrir()"><img src="img/usuario.png" alt="User" style="height: 30px;" /></a>
        </div>
      </div>
    </div>
  </nav>


  <!-- Bienvenida -->
  <section class="welcome-section text-center" id="bienve">
    <div class="container">
      <h1>¡Bienvenido a la Biblioteca!</h1>
      <p>Explora nuestros recursos y servicios disponibles en línea.</p>
      <a href="javascript:abrir()" class="btn" onclick="">Conocennos</a>
    </div>
  </section>

  <!-- Sección Bibliotecarios -->
  <section class="librarians-section" id="biblio">
    <div class="container">
      <h2>Conoce a Nuestros Bibliotecarios</h2>
      <div class="librarians-container">
        <div class="librarian-card">
          <img src="img/usuario.png" alt="Bibliotecario 1">
          <h3>Lic. Juan Pérez</h3>
          <p>Encargado General</p>
        </div>
        <div class="librarian-card">
          <img src="img/usuario.png" alt="Bibliotecario 2">
          <h3>Mtra. Ana López</h3>
          <p>Especialista Digital</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Sección Material de Apoyo -->
  <section class="support-material-section">
    <div class="container">
      <h2>Material de Apoyo</h2>
      <div class="material-container">
        <div class="material-card">
          <h3>Solicitud de libros </h3>
          <p>Accede a documentos de apoyo, tutoriales y más.</p>
          <a href="#" class="btn">Ver más</a>
        </div>
        <div class="material-card">
          <h3>Tutorial de Búsqueda</h3>
          <p>Aprende a utilizar el catálogo en línea.</p>
          <a href="#" class="btn">Ver más</a>
        </div>
      </div>
    </div>
  </section>

  


  <!-- Sección con botón -->
  <section class="section my-5"id="ser">
    <div class="container text-center">
      <h2 class="titu">Explora Nuestros Servicios</h2>
      <p class="txt">Ofrecemos acceso a libros, revistas, bases de datos académicas y más.</p>
      <h3 class="sub">Disponible 24/7</h3>
      <a href="#" class="buton">Consultar Catálogo</a>
    </div>
  </section>
  
 

<!-- Login (oculto por defecto) -->
            <!-- Fondo oscuro con el formulario centrado -->
            <div id="overlayLogin" class="position-fixed top-0 start-0 w-100 h-100  justify-content-center align-items-center"
                style="background-color: rgba(0,0,0,0.5); z-index: 1040; display:none;" onclick="cerrar_recuperar()">

              <div class="bg-white p-4 shadow-lg rounded-4 position-relative"
                  style="max-width: 400px; width: 90%;" id="loginForm" onclick="event.stopPropagation()">

                <!-- Botón para cerrar -->
                <button type="button" class="btn-close position-absolute top-0 end-0 m-1" aria-label="Cerrar" onclick="cerrar_login()"></button>

                <!-- Encabezado -->
                <div class="text-center mb-3 bg-primary text-white p-2 rounded-3 shadow-sm">
                  <h4 class="m-0">Iniciar Sesión</h4>
                </div>

                <!-- Formulario -->
                <form>
                  <div class="mb-3">
                    <label for="matriz" class="form-label fw-semibold">Matrícula</label>
                    <input type="text" class="form-control" id="matri" placeholder="Ej. 123456789" required>
                  </div>

                  <div class="mb-3">
                    <label for="passwordt" class="form-label fw-semibold">Contraseña</label>
                    <input type="password" class="form-control" id="password" placeholder="Contraseña" required>
                  </div>

                  <button type="button" class="btn btn-primary w-100 fw-bold" onclick="Login()">Ingresar</button>

                  <div class="text-center mt-3">
                    <a href="javascript:abrirrec()" class="text-decoration-none small text-danger">¿Olvidaste tu contraseña?</a>
                  </div>

                  <div class="text-center mt-2">
                    <span class="small">¿No tienes una cuenta? <a href="javascript:abrirreg()" class="text-decoration-none text-success">Regístrate</a></span>
                  </div>
                </form>
              </div>
            </div>



<!--REGISTRARTE-->
                      <!-- Registro flotante y responsivo -->
                    <!-- Registro (oculto por defecto) -->
          <div id="overlayRegistro" class="position-fixed top-0 start-0 w-100 h-100 justify-content-center align-items-center"
              style="background-color: rgba(0,0,0,0.5); z-index: 1040; display: none;" onclick="cerrar_recuperar()">

            <div class="bg-white p-4 shadow-lg rounded-4 position-relative"
                style="max-width: 400px; width: 90%;" id="registroForm" onclick="event.stopPropagation()">

              <!-- Botón para cerrar -->
              <button type="button" class="btn-close position-absolute top-0 end-0 m-1" aria-label="Cerrar" onclick="cerrar_login()"></button>

              <!-- Encabezado -->
              <div class="text-center mb-3 bg-primary text-white p-2 rounded-3 shadow-sm">
                <h4 class="m-0">REGISTRAR</h4>
              </div>

              <!-- Formulario -->
              <form method="POST" id="registro" class="w-100">

                <div class="mb-3">
                  <label for="matri-2" class="form-label fw-semibold">Matrícula</label>
                  <input type="text" name="matri" id="matri-2" class="form-control" placeholder="000000001" required>
                </div>

                <div class="mb-3">
                  <label for="email-re" class="form-label fw-semibold">Email</label>
                  <input type="email" name="email-re" id="email-re" class="form-control" placeholder="unmundodelibros@gmail.com" required>
                </div>

                <div class="mb-3">
                  <label for="password-re" class="form-label fw-semibold">Contraseña</label>
                  <input type="password" name="password-re" id="password-re" class="form-control" placeholder="Password" required>
                </div>

                <button type="button" class="btn btn-primary w-100 fw-bold" onclick="Registrar()">Registrar</button>
            
                <div class="text-center mt-3">
                  <a href="javascript:abrirrec()" class="text-decoration-none small text-danger">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="text-center mt-3">
                  <span class="small">¿Ya tienes una cuenta? <a href="javascript:abrir()" class="text-decoration-none text-success">Inicia sesión</a></span>
                </div>
              </form>
            </div>
          </div>


<!--RECUPERAR CONTRASEÑA-->
         
          <!-- Fondo oscuro que envuelve el formulario -->
          <div id="overlayRecuperar" class="position-fixed top-0 start-0 w-100 h-100  justify-content-center align-items-center"
              style="background-color: rgba(0,0,0,0.5); z-index: 1040; display: none;" >

            <!-- Formulario de recuperación centrado -->
            <div class="bg-white p-4 shadow-lg rounded-4 position-relative"
                style="max-width: 400px; width: 90%;" id="recuperarForm">

              <!-- Botón para cerrar -->
              <button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Cerrar" onclick="cerrar_login()"></button>
                
              <!-- Formulario -->
              <form method="POST" id="recuperar">
           

                <div class="text-center mb-3 bg-warning text-dark p-2 rounded-3 shadow-sm">
                  <h4 class="m-0">RECUPERAR CONTRASEÑA</h4>
                </div>

                <div class="mb-3">
                  <label for="email" class="form-label fw-semibold">Email</label>
                  <input type="email" name="email_or" id="email-ver" class="form-control" placeholder="unmundodelibros@ejemplo.com" required>
                </div>

                <div class="mb-3">
                  <label for="verificar" class="form-label fw-semibold">Verificar Email</label>
                  <input type="email" name="veri-email" id="verificar-em" class="form-control" placeholder="Repite el correo" required>
                </div>
                      

                <button type="button" name="olv" class="btn btn-warning w-100 fw-bold" onclick="Contraseña()">Recuperar</button>

                      <div class="text-center mt-5">
                  <span class="small">¿Ya tienes una cuenta? <a href="javascript:abrir()" class="text-decoration-none text-success">Inicia sesión</a></span>
                </div>

                <div class="text-center mt-3">
                  <span class="small">¿No tienes una cuenta? <a href="javascript:abrirreg()" class="text-decoration-none text-primary">Regístrate</a></span>
                </div>
              </form>

            </div>
          </div>
 <div id="contenedor-toast" style="position: fixed; top: 10px; right: 10px; z-index: 9999;"></div>

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

    <!--FUCIONES DE LOGIN-->
        <script src="js/alertas.js"></script>
    <script src="js/pg-incio.js"></script>

    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap.bundle.mins.js"></script>
    <script src="js/menus.js"></script> 
  </body>
</html>