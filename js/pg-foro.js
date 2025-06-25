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
  document.getElementById("nuevaPublicacion").style.display="none";
   document.getElementById("editarPublicacion").style.display="none";
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

  function ab_Comentario(){
         document.getElementById("nuevaPublicacion").style.display="block";
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


function mostrarComentarios() {
  const comentarios = document.getElementById('seccion-comentarios');
  const publicacion = document.getElementById('post');
  const botones = document.getElementById('seccion');

  if (window.innerWidth < 768) {
    comentarios.classList.toggle('d-none');
    botones.classList.remove('d-none'); // Mostrar botones en móvil
    publicacion.classList.toggle('d-none');
    comentarios.scrollIntoView({ behavior: 'smooth' });
  }
}

  //Funcion para cel 
    function devComentarios() {
    const comentarios = document.getElementById('seccion-comentarios');
    const publicacion = document.getElementById('post');
    const derechos = document.getElementById('seccion');

      comentarios.classList.remove('d-none');
      publicacion.classList.remove('d-none');
       derechos.classList.add("d-none");
  }



  // comentarios
// EDITAR CONTRASEÑA
async function publicar() {
  const titul = document.getElementById("titulo").value;
  const comen = document.getElementById("contenido").value;
    // const nom = document.getElementById("user").value;
  const formData = new FormData();
  formData.append('titul', titul);
  formData.append('comen', comen);
  

  try {
    const response = await fetch('../hrmt/com-agr.php', {
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
    document.getElementById("nuevaPublicacion").style.display="none";
    }

  } catch (error) {
    console.error("Error en la solicitud:", error);

  }
}


// Eliminar cometario
async function EliminarCom() {
    let valor = document.getElementById('id_com').innerText;
    try {
        const response = await fetch('../hrmt/com-elim.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
          body: 'valor=' + encodeURIComponent(valor) 

        });

        const data = await response.json();
          agregarToast({
            tipo: data.tipo || 'info',
            titulo: data.titulo || 'Información',
            descripcion: data.descripcion || 'Sin mensaje recibido.',
            autoCierre: true
          });
          if (data.success) {    
     
    }} catch (error) {
        console.error("Error en la solicitud:", error);
    }
}


    function ed_Comentario(){
         document.getElementById("editarPublicacion").style.display="block";
         com_info();
  }

// Editarr cometario
async function EditarCom() {
    const valor = document.getElementById('id_com').innerText;
    const titul = document.getElementById("titul-ed").value;
  const conten = document.getElementById("conte-ed").value;

console.log(valor);
  const formData = new FormData();
  formData.append('valor', valor);
  formData.append('titul', titul);
  formData.append('conten',conten);

  try {
    const response = await fetch('../hrmt/com-edit.php', {
      method: 'POST',
      body: formData // ⚠️ No agregues headers manuales, FormData se encarga
    }); 

        const data = await response.json();
          agregarToast({
            tipo: data.tipo || 'info',
            titulo: data.titulo || 'Información',
            descripcion: data.descripcion || 'Sin mensaje recibido.',
            autoCierre: true
          });
          if (data.success) {    
     
    }} catch (error) {
        console.error("Error en la solicitud:", error);
    }
}


// INFORMACIÓN DE DATOS
async function com_info(){
    const valor = document.getElementById('id_com').innerText;
    console.log(valor);
  try {
    const response = await fetch('../hrmt/com-inf.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'valor=' + encodeURIComponent(valor)
    });

    const data = await response.json();
    console.log("Respuesta del servidor:", data);

    if (data.success) {
      // Datos adicionales
      document.getElementById("titul-ed").value = data.Titul;
      document.getElementById("conte-ed").value = data.com;


    }

  } catch (error) {
    console.error("Error en la solicitud:", error);
  }
}
   // MOSTRAR TODOS LOS COMENTARIOS
setInterval(() => {
    fetch('../hrmt/pg_comen.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            // console.log('Actualizando libros...');
            const container = document.getElementById('post');
            if (container) {
                container.innerHTML = html;
            } else {
                console.error('No se encontró el elemento con ID "container-libros".');
            }
        })
        .catch(error => console.error('Error al actualizar libros:', error));
}, 800); // 2 minutos

// MOSTRAR MIS COMENTARIOS
setInterval(() => {
    fetch('../hrmt/com-most.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            // console.log('Actualizando tus comentarios...');

            const container = document.getElementById('seccion-comentarios');
            if (container) {
                container.innerHTML = html;
            } else {
                console.error('No se encontró el elemento con ID "seccion-comentarios".');
            }
        })
        .catch(error => console.error('Error al actualizar libros:', error));
}, 800); // 2 minutos