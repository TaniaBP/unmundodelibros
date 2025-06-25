
function abrir(){
        var div = document.getElementById("overlayLogin");
        var div2 = document.getElementById("overlayRegistro");
        var div3 = document.getElementById("overlayRecuperar");
        if (div.style.display === "none" || div.style.display === "") {
            div.style.display = "flex"; // Mostrar el div
            div2.style.display="none";
            div3.style.display="none";
        } else {
            div.style.display = "none"; // Ocultar el div
        }
    }
    function abrirreg(){
                var div = document.getElementById("overlayRegistro");
                var div2 = document.getElementById("overlayLogin");
                var div3 = document.getElementById("overlayRecuperar");
        if (div.style.display === "none" || div.style.display === "") {
            div.style.display = "flex"; // Mostrar el div
            div2.style.display="none";
            div3.style.display="none";
        } else {
            div.style.display = "none"; // Ocultar el div
        }
    }
    function abrirrec(){
        var div = document.getElementById("overlayRecuperar");
        var div2 = document.getElementById("overlayRegistro");
        var div3 = document.getElementById("overlayLogin");
        if (div.style.display === "none" || div.style.display === "") {
            div.style.display = "flex"; // Mostrar el div
            div2.style.display="none";
            div3.style.display="none";
        } else {
            div.style.display = "none"; // Ocultar el div
        }
    }
// cerrar desde el fondo
function cerrar_recuperar() {
  document.getElementById("overlayRecuperar").style.display = "none";
   document.getElementById("overlayRegistro").style.display = "none";
    document.getElementById("overlayLogin").style.display = "none";
}

// cerrar desde el la "X"
function cerrar_login(){
  document.getElementById("overlayRecuperar").style.display = "none";
   document.getElementById("overlayRegistro").style.display = "none";
    document.getElementById("overlayLogin").style.display = "none";
}

//Logeo de los usuarios

async function Login() {
  const matricula = document.getElementById("matri").value;
  const password = document.getElementById("password").value;

  try {
    const response = await fetch('php/hrmt/user.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'matricula=' + encodeURIComponent(matricula) +
            '&password=' + encodeURIComponent(password)
    });

    const data = await response.json();
    console.log("Respuesta del servidor:", data);
         agregarToast({
      tipo: data.tipo || 'info',
      titulo: data.titulo || 'Información',
      descripcion: data.descripcion || 'Sin mensaje recibido.',
      autoCierre: true
    });

    if (data.success) {
       setTimeout(() => {
        const tipoUsuario = data.tipo_usuario?.trim();
        if (tipoUsuario === "Administrador") {
          window.location.href = "php/dis/pg-admin.php";
        } else if (tipoUsuario === "Bibliotecario") {
          window.location.href = "php/dis/pg-bibliotecario.php";
        } else if (tipoUsuario === "Usuario") {
          window.location.href = "php/dis/pg-libros.php";
        }
      }, 1000);
    }
  }catch (error) {
    console.error("Error en la solicitud:", error);
    // agregarToast({
    //   tipo: 'error',
    //   titulo: 'Error de red',
    //   descripcion: 'No se pudo conectar con el servidor.',
    //   autoCierre: true
    // });
 //   mostrarToast('error', 'Fallo de red o problema en la solicitud');
  }}

//Registrarte
async function Registrar() {
  const matricula = document.getElementById("matri-2").value;
  const email = document.getElementById("email-re").value;
  const password = document.getElementById("password-re").value;

  try {
    const response = await fetch('php/hrmt/sesion.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'matricula=' + encodeURIComponent(matricula) +
      '&email=' + encodeURIComponent(email) +
      '&password=' + encodeURIComponent(password)
    });

    const data = await response.json();
    console.log("Respuesta del registro:", data);
         agregarToast({
      tipo: data.tipo || 'info',
      titulo: data.titulo || 'Información',
      descripcion: data.descripcion || 'Sin mensaje recibido.',
      autoCierre: true
    }); 
    if (data.success) {      
      //mostrarToast('exito', 'Ya estás registrado');
      // Puedes redirigir o realizar otra acción aquí
    }
  } catch (error) {
    console.error("Error en la solicitud:", error);
   // mostrarToast('error', 'Fallo de red o problema en la solicitud');
  }
}

//Recuperar contraseña 
async function Contraseña() {
  const email = document.getElementById("email-ver").value;
  const verif = document.getElementById("verificar-em").value;

  try {
    const response = await fetch('php/hrmt/email.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'email=' + encodeURIComponent(email) +
            '&verif=' + encodeURIComponent(verif)
    });

    const data = await response.json();
    console.log("Respuesta del servidor:", data);
   agregarToast({
      tipo: data.tipo || 'info',
      titulo: data.titulo || 'Información',
      descripcion: data.descripcion || 'Sin mensaje recibido.',
      autoCierre: false
    }); 
    if (data.success) {
     
    }

  } catch (error) {
    console.error("Error en la solicitud:", error);
  }
}

