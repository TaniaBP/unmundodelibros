 
  // Llama a la función al cargar la página
  window.addEventListener('DOMContentLoaded', cargarUsuarios);



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



//chat

function toggleChat() {
const chatBox = document.getElementById('chatBox');
const isVisible = chatBox.style.display === 'block';
chatBox.style.display = isVisible ? 'none' : 'block';
}

function sendMessage(event) {
event.preventDefault();
const chatInput = document.getElementById('chatInput');
const chatBody = document.getElementById('chatBody');
const userMessage = chatInput.value.trim();

if (userMessage) {
    // Mostrar mensaje enviado
    const sentMessage = document.createElement('div');
    sentMessage.className = 'message sent';
    sentMessage.textContent = userMessage;
    chatBody.appendChild(sentMessage);

    // Mostrar mensaje de carga
    const receivedMessage = document.createElement('div');
    receivedMessage.className = 'message received';
    receivedMessage.textContent = 'Estamos procesando tu consulta...';
    chatBody.appendChild(receivedMessage);

    // Enviar mensaje al servidor mediante AJAX
    fetch('../hmt/chat.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `message=${encodeURIComponent(userMessage)}`,
    })
        .then((response) => response.text())
        .then((data) => {
            // Reemplazar el mensaje de "procesando" con la respuesta real
            receivedMessage.textContent = data;
            chatBody.scrollTop = chatBody.scrollHeight; // Desplazamiento automático al final
        })
        .catch((error) => {
            // Mostrar mensaje de error si falla la solicitud
            receivedMessage.textContent = 'Lo sentimos, ocurrió un error.';
            console.error('Error:', error);
        });

    // Limpiar el input
    chatInput.value = '';
}
}


function handleKeyPress(event) {
if (event.key === 'Enter') {
    sendMessage();
}
}
//funcion  para abrir verificacion desde el boton
function verif() {
    document.querySelector(".lector").style.display = "block";

}

// Función para leer QR y mostrar información
async function leerQR() {
    const qrValue = document.getElementById("lec-qr").value.trim();
    console.log("QR leído:", qrValue);

    if (!qrValue) {
        agregarToast({
            tipo: 'warning',
            titulo: 'Campo vacío',
            descripcion: 'Por favor ingresa un código QR.',
            autoCierre: true
        });
        return;
    }

    try {
        const response = await fetch('../hrmt/pg-bibli-QR.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'qrValue=' + encodeURIComponent(qrValue)
        });

        const data = await response.json();
        console.log("Respuesta del servidor:", data);

        // Mostrar notificación
        agregarToast({
            tipo: data.tipo || 'info',
            titulo: data.titulo || 'Información',
            descripcion: data.descripcion || 'Sin mensaje recibido.',
            autoCierre: true
        });

        // Si no se encontró el QR, no mostrar la ventana
        if (!data.success) {
       
        }

        // Mostrar los datos si todo fue exitoso
        document.getElementById('book-id').innerText = data.id_pres;
        document.getElementById('book-name').innerText = data.Nombre;
        document.getElementById('book-autor').innerText = data.Autor;
        document.getElementById('book-dev').innerText = data.Fcha_dev;
        document.getElementById('book-sto').innerText=data.stock;
        document.getElementById('book-imgen').src=data.Portada;
        document.getElementById('book-cuota').innerText = "$" + data.Cuota;

        document.getElementById('info-qr').style.display = 'block';
        document.getElementById('leer').style.display = 'none';

        // Estado del préstamo
        let estadoPrestamo = "";
        if (data.Prestamo == 0) {
            estadoPrestamo = "LIBRO DISPONIBLE";
            document.getElementById('book-prestamo').style.color = "red";
            document.getElementById('btn-dev').style.display = "none";
            document.getElementById('btn-info').style.display = "block";

            // cerrar las etiquetas de devolucion y mostrar peticion
            document.getElementById('book-de').innerText="Fecha maxima de pretamo:";
            document.getElementById('book-dev').innerText=data.Peticion;
            // No mostrar cuota por que es prestamo 
            document.getElementById('book-cuo').style.display="none";
            document.getElementById('book-cuota').style.display="none";
        } else if (data.Prestamo == 1) {
            estadoPrestamo = "LIBRO PRESTADO";
            document.getElementById('book-prestamo').style.color = "green";
            document.getElementById('btn-info').style.display = "none";
            document.getElementById('btn-dev').style.display = "block";

            document.getElementById('book-de').innerText="Fecha maxima de devolución:";
            document.getElementById('book-dev').innerText=data.Fcha_dev;
                   // No mostrar cuota por que es prestamo 
            document.getElementById('book-stock').style.display="none";
            document.getElementById('book-sto').style.display="none";


               // Mostrar cuota por que es prestamo 
            document.getElementById('book-cuo').style.display="block";
            document.getElementById('book-cuota').style.display="block";
            document.getElementById('book-cuota').innerText = "$" + data.Cuota;

        } else {
            estadoPrestamo = "Estado desconocido";
        }

        document.getElementById('book-prestamo').innerText = estadoPrestamo;

    } catch (error) {
        console.error("Error en la solicitud:", error);
        agregarToast({
            tipo: 'error',
            titulo: 'Error de conexión',
            descripcion: 'No se pudo conectar al servidor.',
            autoCierre: true
        });
    }
}

// Modificacion en del pretamos del libro
async function prestamo_Li() {
    const id = document.getElementById("book-id").innerText;
    console.log("ID de préstamo:", id);

    try {
        const response = await fetch('../hrmt/pg-biblio-pres.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + encodeURIComponent(id)
        });

        const data = await response.json();

        // Mostrar notificación principal
        agregarToast({
            tipo: data.tipo || 'info',
            titulo: data.titulo || 'Información',
            descripcion: data.descripcion || 'Sin mensaje recibido.',
            autoCierre: true
        });

        console.log("Respuesta del servidor:", data);

        if (!data.success) return;

        const estado = parseInt(data.Prestamo);

     
            // Libro prestado
            document.getElementById('book-prestamo').innerText = "LIBRO PRESTADO";
            document.getElementById('book-prestamo').style.color = "green";

            document.getElementById('book-cuota').innerText = `$${data.Cuota || '0.00'}`;
            document.getElementById('book-cuo').style.display = "block";
            document.getElementById('book-cuota').style.display = "block";

            document.getElementById('book-de').innerText = "Fecha máxima de devolución:";
            document.getElementById('book-dev').innerText = data.Fcha_dev;
            document.getElementById('btn-info').style.display = "none";
            document.getElementById('btn-dev').style.display = "block";
               // No mostrar cuota por que es prestamo 
            document.getElementById('book-stock').style.display="none";
            document.getElementById('book-sto').style.display="none";

        
        

    } catch (error) {
        console.error("Error en la solicitud:", error);
 
    }
}


//DEVOLUCION DE LIBROS 


                // VERIFICADOR DE COSTOS
                setInterval(() => {

                    fetch('../hrmt/pg-costo.php')
                        .then(response => response.text())
                        .then(data => {
                            console.log('Verificación realizada:', data);
                        // location.reload(); // Recarga la página
                        });
                },14400000); 

                // Ejecutar inmediatamente al cargar Pagos
fetch('../hrmt/pg-costo.php')
    .then(response => response.text())
    .then(data => {
         console.log('Verificación inicial realizada:', data);
        // location.reload(); // Recarga la página si quieres
    });
// CARGAR TABLAS 
                //   Funcion para mostrar usuarios
  function cargarUsuarios() {
    fetch('../hrmt/tab-use-bibli.php') // Cambia por el nombre real del archivo PHP
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

                  //   Funcion para mostrar Prestamos
  function cargarPrestamos() {
    fetch('../hrmt/tab-pres-bibli.php') // Cambia por el nombre real del archivo PHP
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
                  //   Funcion para mostrar Libros
  function cargarLibros() {
    fetch('../hrmt/tab-book-biblio.php') // Cambia por el nombre real del archivo PHP
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
            

//Validar devolucion del Libro
        // Detectar checkbox
        const checkbox = document.getElementById("checkBook");
console.log(checkbox);
        // Escuchar el cambio de estado
        checkbox.addEventListener("change", function () {
            if (checkbox.checked) {
                    devlib(); // Llama a la función solo si está marcado
            }else{
                    devli();
            }
        });
                async function devlib() {
            const id = document.getElementById("book-id").innerText;
            //console.log("id de préstamo:", id);

            try {
                const response = await fetch('../hrmt/pg-devlib.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(id)
                });

                const data = await response.json();
                  // Mostrar notificación principal
        agregarToast({
            tipo: data.tipo || 'info',
            titulo: data.titulo || 'Información',
            descripcion: data.descripcion || 'Sin mensaje recibido.',
            autoCierre: true
        });
                console.log("Respuesta del servidor:", data);

                if (data.success) {
                    
                }
            document.getElementById('book-cuota').innerText="$" +data.Cuotas;
            } catch (error) {
                console.error("Error en la solicitud:", error);
            }
        }

        //FUNCION DE COBRAR EL COSTO SI DEVUELVE EL LIBRO
           async function devli() {
            const id = document.getElementById("book-id").innerText;
            const cos = document.getElementById("txt-mon").innerText;
            //console.log("id de préstamo:", id);

            try {
                const response = await fetch('../hrmt/pg-deli.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                   body: 'id=' + encodeURIComponent(id) + '&cos=' + encodeURIComponent(cos)

                });

                const data = await response.json();
                console.log("Respuesta del servidor:", data);

                if (data.error) {
                    alert("Error: " + data.error);
                    return;
                }
            document.getElementById('book-cuota').innerText= "$" + data.Cuotas;
            } catch (error) {
                console.error("Error en la solicitud:", error);
            }
        }


                //fucion de pago por Pay-pal
                async function devopay() {
                         const id = document.getElementById("book-id").innerText;
    console.log("id de préstamo:", id);

    try {
        const response = await fetch('../hrmt/pg-tip.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + encodeURIComponent(id)
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
                document.getElementById('lector').style.display ="none";
                document.getElementById('modal-verif').style.display="flex";
                document.getElementById('book-cod').innerText=data.validar;
                }

            } catch (error) {
                console.error("Error en la solicitud:", error);
            }
                }



//cerrar la ventana de lector de QR 
function cerrar_li() {
    document.querySelector(".lector").style.display = "none";
    document.getElementById('book-id').src ="";

        document.getElementById('book-name').innerText= "";
        document.getElementById('book-autor').innerText = "";
       // document.getElementById('book-prestamo').innerText = data.Prestamo;
       // document.getElementById('book-devolucion').innerText = data.Devolucion;
        document.getElementById('book-dev').innerText = "";
        document.getElementById('book-cuota').innerText = "";
        document.getElementById('book-prestamo').innerText = "";
        document.getElementById('lec-qr').value="";
                // Abrir el bloque de busqueda
        document.getElementById('info-qr').style.display = 'none';
        document.getElementById('leer').style.display = 'block';
        // cerrar codigo verificacion
         document.getElementById('modal-verif').style.display ="none";

}




