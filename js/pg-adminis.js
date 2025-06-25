 // Función para cargar usuarios desde el PHP
  function cargarUsuarios() {
    fetch('../hrmt/tab-use-admin.php') // Cambia por el nombre real del archivo PHP
      .then(response => {
        if (!response.ok) {
          throw new Error('Error al obtener los datos');
        }
        return response.text();
      })
      .then(data => {
        document.getElementById('tabla').innerHTML = data;
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }
//   Funcion para mostrar libros 
  function cargarLibros() {
    fetch('../hrmt/tab-lib-admin.php') // Cambia por el nombre real del archivo PHP
      .then(response => {
        if (!response.ok) {
          throw new Error('Error al obtener los datos');
        }
        return response.text();
      })
      .then(data => {
        document.getElementById('tabla').innerHTML = data;
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }
// Funcion para  mostrar prestamos 
    function cargarPrestamos() {
    fetch('../hrmt/tab-prest-admin.php') // Cambia por el nombre real del archivo PHP
      .then(response => {
        if (!response.ok) {
          throw new Error('Error al obtener los datos');
        }
        return response.text();
      })
      .then(data => {
        document.getElementById('tabla').innerHTML = data;
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }
//   Funcion para mostra pagos
    function cargarPagos() {
    fetch('../hrmt/tab-pag-admin.php') // Cambia por el nombre real del archivo PHP
      .then(response => {
        if (!response.ok) {
          throw new Error('Error al obtener los datos');
        }
        return response.text();
      })
      .then(data => {
        document.getElementById('tabla').innerHTML = data;
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }
//   Funcion para mostrar Devoluciones
  function cargarDev() {
    fetch('../hrmt/tab-dev-admin.php') // Cambia por el nombre real del archivo PHP
      .then(response => {
        if (!response.ok) {
          throw new Error('Error al obtener los datos');
        }
        return response.text();
      })
      .then(data => {
        document.getElementById('tabla').innerHTML = data;
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

  // Funcion para  mostrar prestamos 
    function cargarNegras() {
    fetch('../hrmt/tab-neg-admin.php') // Cambia por el nombre real del archivo PHP
      .then(response => {
        if (!response.ok) {
          throw new Error('Error al obtener los datos');
        }
        return response.text();
      })
      .then(data => {
        document.getElementById('tabla').innerHTML = data;
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }

// MI CUENTA


//Informacion de cuenta
function abrir_c(){
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
     document.getElementById("nuevoLibro").style.display="none";

      document.getElementById("editarD").style.display="none";
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
         const prefi = document.getElementById("perfil");
        prefi.src = data.imagen + "?t=" + new Date().getTime(); // cache-busting
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

async function AgregarLibro() {


  const titulo = document.getElementById("titulo-libro").value;
  const isbn = document.getElementById("isbn").value;
  const autor = document.getElementById("autor").value;
  const tipoLibro = document.getElementById("tipoLibro").value;
  const existencias = document.getElementById("existencias").value;
  const ubicacion = document.getElementById("ubicacion").value;
  const contenido = document.getElementById("contenido").value;
  const fileInput = document.getElementById("file-libro");
  const file = fileInput.files[0];

  const formData = new FormData();
  formData.append('titulo', titulo);
  formData.append('isbn', isbn);
  formData.append('autor', autor);
  formData.append('tipoLibro', tipoLibro);
  formData.append('existencias', existencias);
  formData.append('ubicacion', ubicacion);
  formData.append('contenido', contenido);

  if (file) {
    formData.append('portada', file);

  }
  // console.log("Ruta de la imagen orig:", fileInput.files[0]);

  try {
    const response = await fetch('../hrmt/pg-editli-admin.php', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();
    console.log("Editamos los datos:", data);


    agregarToast({
      tipo: data.tipo || 'info',
      titulo: data.titulo || 'Información',
      descripcion: data.descripcion || 'Sin mensaje recibido.',
      
      autoCierre: true
    });
  limp_cam();
    if (data.success) {
      document.getElementById("nuevoLibro").style.display="none";
      cargarLibros();
    

      if (data.imagen) {
        const preview = document.getElementById("preview-por");
        preview.src = data.imagen + "?t=" + new Date().getTime();
      }

    }

  } catch (error) {
    console.error("Error en la solicitud:", error);
  }
}


function btnAgr(){
  document.getElementById('nuevoLibro').style.display="block";
}

// EDITAR LIBROS
let isbnSeleccionado = null;

function abrirLibro(isbn) {
  isbnSeleccionado = isbn; // Guarda el ISBN para usar después
  console.log("Formulario abierto para ISBN:", isbn);
  document.getElementById("editarD").style.display = "block";
  EditarLibros(isbn);
}
async function EditarLibros(isbn) {
  try {
    const response = await fetch("../hrmt/admin-editL.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `isbn=${encodeURIComponent(isbn)}`
    });

    const html = await response.text();

    // Insertar contenido en el modal
    const modalBody = document.getElementById("editarD");
    modalBody.innerHTML = html;


  } catch (error) {
    console.error("Error en la solicitud:", error);
    agregarToast({
      tipo: 'error',
      titulo: 'Error',
      descripcion: 'No se pudo cargar el contenido del usuario.',
      autoCierre: true
    });
  }
}


// Eliminar Libro
async function EliminarLibro(isbn) {
  const isbnSeleccionado = isbn;
  console.log("Usando ISBN guardado:", isbnSeleccionado);

  try {
    const response = await fetch("../hrmt/admin-elimL.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `isbn=${encodeURIComponent(isbnSeleccionado)}`
    });

    const data = await response.json();
    console.log("Respuesta del servidor:", data);

    agregarToast({
      tipo: data.tipo || 'info',
      titulo: data.titulo || 'Información',
      descripcion: data.descripcion || 'Sin mensaje recibido.',
      
      autoCierre: true
    });
      cargarLibros();
    if (data.success) {
 // Recarga los datos de libros
    }

  } catch (error) {
    console.error("Error en la solicitud:", error);
    agregarToast({
      tipo: 'error',
      titulo: 'Error',
      descripcion: 'No se pudo eliminar el libro.',
      autoCierre: true
    });
  }
}



// USUARIOS

function abUser(ncontrol) {
  console.log("Formulario abierto para Ncontrol:", ncontrol);
  
    document.getElementById('editarD').style.display="block";
  agregarUsuario(ncontrol); // <<-- Aquí sí pasas ncontrol correctamente

}



async function agregarUsuario(ncontrol) {
  try {
    const response = await fetch("../hrmt/admin-editU.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `ncontrol=${encodeURIComponent(ncontrol)}`
    });

    const html = await response.text();

    // Insertar contenido en el modal
    const modalBody = document.getElementById("editarD");
    modalBody.innerHTML = html;


  } catch (error) {
    console.error("Error en la solicitud:", error);
    agregarToast({
      tipo: 'error',
      titulo: 'Error',
      descripcion: 'No se pudo cargar el contenido del usuario.',
      autoCierre: true
    });
  }
}


async function guardarCambiosUsuario() {


  const ncon = document.getElementById("ncontrol").value;
  const nom = document.getElementById("nombre-editar").value;
  const email = document.getElementById("email-editar").value;
  const tel = document.getElementById("telefono").value;
  const tipo = document.getElementById("tipoUsuario").value;


  const formData = new FormData();
  formData.append('ncon', ncon);
  formData.append('nom', nom);
  formData.append('email', email);
  formData.append('tel', tel);
  formData.append('tipo', tipo);
  // console.log("Ruta de la imagen orig:", fileInput.files[0]);

  try {
    const response = await fetch('../hrmt/pg-euse-admin.php', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();
    console.log("Editamos los datos:", data);


    agregarToast({
      tipo: data.tipo || 'info',
      titulo: data.titulo || 'Información',
      descripcion: data.descripcion || 'Sin mensaje recibido.',
      
      autoCierre: true
    });
          cargarUsuarios();
  limp_cam();
    if (data.success) {
      document.getElementById("editarD").style.display="none";
      cargar();
    

      if (data.imagen) {
        const preview = document.getElementById("preview-por");
        preview.src = data.imagen + "?t=" + new Date().getTime();
      }

    }

  } catch (error) {
    console.error("Error en la solicitud:", error);
  }
}

async function EliminarUser(ncontrol) {
  const isbnSeleccionado = ncontrol;
  console.log("Usando Ncontrol guardado:", isbnSeleccionado);

  try {
    const response = await fetch("../hrmt/admin-elimU.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `ncontrol=${encodeURIComponent(isbnSeleccionado)}`
    });

    const data = await response.json();
    console.log("Respuesta del servidor:", data);

    agregarToast({
      tipo: data.tipo || 'info',
      titulo: data.titulo || 'Información',
      descripcion: data.descripcion || 'Sin mensaje recibido.',
      
      autoCierre: true
    });
      cargarUsuarios();
    if (data.success) {
 // Recarga los datos de libros
    }

  } catch (error) {
    console.error("Error en la solicitud:", error);
    agregarToast({
      tipo: 'error',
      titulo: 'Error',
      descripcion: 'No se pudo eliminar el libro.',
      autoCierre: true
    });
  }
}


// PRESTAMOS

function abPrest(idpres) {
  console.log("Formulario abierto para Ncontrol:", idpres);
  
    document.getElementById('editarD').style.display="block";
  agregarPres(idpres); // <<-- Aquí sí pasas ncontrol correctamente

}



async function agregarPres(idpres) {
  try {
    const response = await fetch("../hrmt/admin-editPr.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `idpres=${encodeURIComponent(idpres)}`
    });

    const html = await response.text();

    // Insertar contenido en el modal
    const modalBody = document.getElementById("editarD");
    modalBody.innerHTML = html;


  } catch (error) {
    console.error("Error en la solicitud:", error);
    agregarToast({
      tipo: 'error',
      titulo: 'Error',
      descripcion: 'No se pudo cargar el contenido del usuario.',
      autoCierre: true
    });
  }
}


// Guardar cambios de edicion Prestamo
// Guardar cambios de edición Préstamo
async function guardarCambiosPres(idpres) {
  const pres = document.getElementById("prestamo").value;
  const dev = document.getElementById("devolucion").value;
  const tipo = document.getElementById("tipopag").value;
  const pag = document.getElementById("pag").value;
  const cuota = parseFloat(pag);

  // 🚫 No se permite "entrega" si hay cuota pendiente
  if (tipo === "entrega" && cuota > 0) {
    agregarToast({
      tipo: "error",
      titulo: "Devolución inválida",
      descripcion: "No se puede seleccionar 'entrega' si hay una cuota pendiente.",
      autoCierre: true
    });
    return;
  }

  // 🚫 No se permite "paypal" si la cuota es 0
  if (tipo === "paypal" && cuota === 0) {
    agregarToast({
      tipo: "error",
      titulo: "Pago inválido",
      descripcion: "No puedes seleccionar 'paypal' si no hay ninguna cuota pendiente.",
      autoCierre: true
    });
    return;
  }

  const formData = new FormData();
  formData.append('idpres', idpres);
  formData.append('pres', pres);
  formData.append('dev', dev);
  formData.append('tipo', tipo);
  formData.append('pag', pag);

  try {
    const response = await fetch('../hrmt/pg-epres-admin.php', {
      method: 'POST',
      body: formData
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
      document.getElementById("editarD").style.display = "none";
      cargarPrestamos();
    }

  } catch (error) {
    console.error("Error al guardar cambios:", error);
    agregarToast({
      tipo: 'error',
      titulo: 'Error',
      descripcion: 'No se pudo guardar los cambios.',
      autoCierre: true
    });
  }
}


async function EliminarPres(idpres) {
  console.log("Eliminar préstamo ID:", idpres);

  try {
    const response = await fetch("../hrmt/admin-elimPr.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `idpres=${encodeURIComponent(idpres)}`
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
      cargarPrestamos(); // Recarga los préstamos si se eliminó
    }

  } catch (error) {
    console.error("Error en la solicitud:", error);
    agregarToast({
      tipo: 'error',
      titulo: 'Error',
      descripcion: 'No se pudo eliminar el préstamo.',
      autoCierre: true
    });
  }
}


// DEVOLUCIONES
function abDev(iddev) {
  console.log("Formulario abierto para Dev:", iddev);
  document.getElementById('editarD').style.display = "block";
  agregardev(iddev);
}

async function agregardev(iddev) {
  try {
    const response = await fetch("../hrmt/admin-info.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `iddev=${encodeURIComponent(iddev)}`
    });

    const html = await response.text();
    console.log("Respuesta del servidor:", html); // 👈 depuración

    const modalBody = document.getElementById("editarD");
    modalBody.innerHTML = html;

  } catch (error) {
    console.error("Error en la solicitud:", error);
    agregarToast({
      tipo: 'error',
      titulo: 'Error',
      descripcion: 'No se pudo cargar el contenido del usuario.',
      autoCierre: true
    });
  }
}



// DEVOLUCIONES

function abPag(idpag) {
  console.log("Formulario abierto para Dev:", idpag);
  
    document.getElementById('editarD').style.display="block";
  agregarPag(idpag); // <<-- Aquí sí pasas ncontrol correctamente

}



async function agregarPag(idpag) {
  try {
    const response = await fetch("../hrmt/admin-info-p.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `idpag=${encodeURIComponent(idpag)}`
    });

    const html = await response.text();

    // Insertar contenido en el modal
    const modalBody = document.getElementById("editarD");
    modalBody.innerHTML = html;


  } catch (error) {
    console.error("Error en la solicitud:", error);
    agregarToast({
      tipo: 'error',
      titulo: 'Error',
      descripcion: 'No se pudo cargar el contenido del usuario.',
      autoCierre: true
    });
  }
}
// Guardar cambios de edicion
async function guardarEdicionLibro() {


  const titulo = document.getElementById("titulo-libro-edit").value;
  const isbn = document.getElementById("isbn-edit").value;
  const autor = document.getElementById("autor-edit").value;
  const tipoLibro = document.getElementById("tipoLibro-edit").value;
  const existencias = document.getElementById("existencias-edit").value;
  const ubicacion = document.getElementById("ubicacion-edit").value;
  const contenido = document.getElementById("contenido-edit").value;
  const fileInput = document.getElementById("file-libro-edit");
  const file = fileInput.files[0];

  const formData = new FormData();
  formData.append('titulo', titulo);
  formData.append('isbn', isbn);
  formData.append('autor', autor);
  formData.append('tipoLibro', tipoLibro);
  formData.append('existencias', existencias);
  formData.append('ubicacion', ubicacion);
  formData.append('contenido', contenido);

  if (file) {
    formData.append('portada_edit', file);

  }
  // console.log("Ruta de la imagen orig:", fileInput.files[0]);

  try {
    const response = await fetch('../hrmt/pg-elibro-admin.php', {
      method: 'POST',
      body: formData
    });

    const data = await response.json();
    console.log("Editamos los datos:", data);


    agregarToast({
      tipo: data.tipo || 'info',
      titulo: data.titulo || 'Información',
      descripcion: data.descripcion || 'Sin mensaje recibido.',
      
      autoCierre: true
    });
      document.getElementById("editarD").style.display="none";
          cargarLibros();
  limp_cam();
    if (data.success) {

      cargarLibros();
    

      if (data.imagen) {
        const preview = document.getElementById("preview-por");
        preview.src = data.imagen + "?t=" + new Date().getTime();
      }

    }

  } catch (error) {
    console.error("Error en la solicitud:", error);
  }
}


// LISTA NEGRA

async function EliminarNeg(idneg) {
  console.log("Eliminar préstamo ID:", idneg);

  try {
    const response = await fetch("../hrmt/admin-elimN.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `idneg=${encodeURIComponent(idneg)}`
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
      cargarNegras(); // Recarga los préstamos si se eliminó
    }

  } catch (error) {
    console.error("Error en la solicitud:", error);
    agregarToast({
      tipo: 'error',
      titulo: 'Error',
      descripcion: 'No se pudo eliminar el préstamo.',
      autoCierre: true
    });
  }
}



function limp_cam() {

//LIMPIAR AGREGAR LIBROS
        document.getElementById('titulo-libro').value= "";
        document.getElementById('isbn').value = "";
        document.getElementById('autor').value = "";
        document.getElementById('tipoLibro').value = "";
        document.getElementById('existencias').value = "";
        document.getElementById('ubicacion').value = "";
        document.getElementById('contenido').value = ""; 
        document.getElementById("file-libro").value = "";
const select = document.getElementById("tipoLibro");
select.selectedIndex = 0; // Reinicia a la primera opción
select.classList.remove("is-invalid", "is-valid"); // Limpia estados de validación visual si usas Bootstrap



}



  // Llama a la función al cargar la página
  window.addEventListener('DOMContentLoaded', cargarLibros());
