//Informacion de cuenta
function abrir(){
    var div = document.getElementById("cnta");
    // var fond=document.getElementById("fondoModal");
    if (div.style.display === "none" || div.style.display === "") {
        div.style.display = "block"; // Mostrar el div
        // fond.style.display="block";
        cuenta();
    }
}
function cerrar_edit(){
    document.getElementById("cnta").style.display = "none";
 document.getElementById("modal-cambiar").style.display = "none";
     document.getElementById("editar").style.display="none";
}

function abr_edit(){
   document.getElementById("editar").style.display = "block";
   document.getElementById("cnta").style.display = "none"; // ocultar la ventana de cuenta
}
function abr_con(){
   document.getElementById("editar").style.display = "none";
   document.getElementById("cnta").style.display = "none";
   document.getElementById("modal-cambiar").style.display="block";
}
// Función para cerrar la sección de QR
function cerrar_li() {
    document.querySelector(".mos").style.display = "none";
    document.getElementById("lib").style.display = "none";
}
// INFORMACIÓN DE DATOS
async function cuenta() {
  try {
    const response = await fetch('../cont/inf-cuen.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'matricula=' + encodeURIComponent(matricula)
    });

    const data = await response.json();
    console.log("Respuesta del servidor:", data);

    if (data.success) {
      // Datos adicionales
      document.getElementById("book-nom").innerText=data.nombre;
      document.getElementById("book-img").src = data.perfil;
      document.getElementById("book-mat").innerText = data.mat;
      document.getElementById("book-em").innerText = data.email;
      document.getElementById("book-tel").innerText = data.tel;

      // label de editar 
      document.getElementById("preview-img").src = data.perfil;
      document.getElementById("nombre").value = data.nombre;
      document.getElementById("email").value = data.email;
      document.getElementById("telefono").value = data.tel;


    }

  } catch (error) {
    console.error("Error en la solicitud:", error);
  }
}

// EDITAR INFORMACIÓN
async function edit_datos() {
  const nombre = document.getElementById("nombre").value;
  const email = document.getElementById("email").value;
  const telefono = document.getElementById("telefono").value;
  const fileInput = document.getElementById("file-upload");
  const file = fileInput.files[0]; // Imagen seleccionada (si existe)

  const formData = new FormData();
  formData.append('nombre', nombre);
  formData.append('email', email);
  formData.append('telefono', telefono);

  // Solo si el usuario seleccionó una imagen
  if (file && file.type === "image/jpeg") {
    formData.append('perfil', file);
  }

  try {
    const response = await fetch('../cont/inf-edit.php', {
      method: 'POST',
      body: formData // ⚠️ No agregues headers manuales, FormData se encarga
    }); 

    const data = await response.json();
    
    console.log("Editamos los datos:", data);

        agregarToast({
      tipo: data.tipo || 'info',
      titulo: data.titulo || 'Información',
      descripcion: data.descripcion || 'Sin mensaje recibido.',
      autoCierre: true
    });
    if (data.success) {
      cuenta(); // Tu función personalizada

      // ✅ Si se recibió nueva ruta de imagen, actualízala en el <img>
      if (data.imagen) {
        const preview = document.getElementById("preview-img");
        preview.src = data.imagen + "?t=" + new Date().getTime(); // cache-busting
      }
    }

  } catch (error) {
    console.error("Error en la solicitud:", error);
    // agregarToast({
    //   tipo: 'error',
    //   titulo: 'Error de red',
    //   descripcion: 'No se pudo conectar al servidor.',
    //   autoCierre: true
    // });
  }
}

// EDITAR CONTRASEÑA
async function edit_con() {
  const contr = document.getElementById("nuevaPass").value;
  const verif = document.getElementById("verificaPass").value;
  const formData = new FormData();
  formData.append('contr', contr);
  formData.append('verif', verif);
  

  try {
    const response = await fetch('../cont/cambiar-con.php', {
      method: 'POST',
      body: formData // ⚠️ No agregues headers manuales, FormData se encarga
    }); 

    const data = await response.json();
    console.log("Editamos los datos:", data);

        agregarToast({
      tipo: data.tipo || 'info',
      titulo: data.titulo || 'Información',
      descripcion: data.descripcion || 'Sin mensaje recibido.',
      autoCierre: true
    });
    if (data.success) {
      
    }

  } catch (error) {
    console.error("Error en la solicitud:", error);

  }
}

// INFORMACIÓN DE LIBROS
async function CargarInfo(isbn) {
    try {
        const response = await fetch(`../hrmt/mostrarL.php?isbn=${isbn}`, {
            method: 'POST'
        });

        const data = await response.json();

        if (data.error) {
            alert(data.error);
        } else {
            console.log(data.nombre);

            // Actualizar la información en el modal del libro
            document.getElementById('book-name').innerText = data.nombre;
            document.getElementById('book-type').innerText = data.tipoti;
            document.getElementById('book-author').innerText = data.autor;
            document.getElementById('book-description').innerText = data.descripcion;
            document.getElementById('book-image').src = data.imagen;
            document.getElementById('qr-isbn').innerText = data.isbn;
            document.getElementById('lib').style.display = 'block';


        }
    } catch (error) {
        console.error("Error en la solicitud:", error);
    }
}
// Función para mostrar la sección del QR
async function MostrarQR() {
    let valor = document.getElementById('qr-isbn').innerText;
    try {
        const response = await fetch('../hrmt/mostrarQR.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
          body: 'valor=' + encodeURIComponent(valor) + '&matricula=' + encodeURIComponent(matricula)

        });

        const data = await response.json();
          agregarToast({
            tipo: data.tipo || 'info',
            titulo: data.titulo || 'Información',
            descripcion: data.descripcion || 'Sin mensaje recibido.',
            autoCierre: true
          });
          if (data.success) {    
        document.getElementById("preview-img").src = data.perfil;
        document.getElementById('book-imge').src = data.qr;
        document.getElementById('in').innerText = "ID de préstamo: " + data.id_pres;

        document.getElementById('mos').style.display = 'block';
        document.getElementById('lib').style.display = 'none';
    }} catch (error) {
        console.error("Error en la solicitud:", error);
    }
}



// REACCIONES
function cambiarImagen(event) {
    let btn = event.currentTarget;
    let imagen = btn.querySelector(".btn-fav-img");
    let isbn = btn.getAttribute("data-isbn");

    // Verifica qué imagen está actualmente
    if (imagen.src.includes("Cora-va.png")) {
        imagen.src = "../../img/corazon.png"; // marcado como favorito
        guardarFavorito(isbn, true); // guardar en base de datos como favorito
    } else {
        imagen.src = "../../img/Cora-va.png"; // desmarcado
        guardarFavorito(isbn, false); // eliminar de favoritos
    }
}

function guardarFavorito(isbn, esFavorito) {
    fetch('../cont/pg-reac.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ isbn: isbn, favorito: esFavorito })
    })
.then(response => response.text())  // <-- temporalmente
.then(text => {
    console.log('Respuesta cruda:', text);
    const data = JSON.parse(text); // ahora intentas parsear tú
    console.log('Respuesta parseada:', data);
})

    .catch(error => {
        console.error('Error al guardar favorito:', error);
    });
}

setInterval(() => {
    fetch('../hrmt/pg-recc.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            console.log('Actualizando libros...');
            const container = document.getElementById('container-libros');
            if (container) {
                container.innerHTML = html;
            } else {
                console.error('No se encontró el elemento con ID "container-libros".');
            }
        })
        .catch(error => console.error('Error al actualizar libros:', error));
}, 800); // 2 minutos