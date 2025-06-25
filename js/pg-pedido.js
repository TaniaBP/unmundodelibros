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


       document.getElementById("modalPaypal").style.display="none";
           // ✅ Limpia PayPal al cerrar
    document.getElementById("paypal-button-container").innerHTML = '';
      document.getElementById('codigo').value="";

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

function abrir_li() {
  document.querySelector('.ver').classList.remove('d-none');
}

function cerrar_li() {
  document.querySelector('.ver').classList.add('d-none');
  document.getElementById('codigo').value="";
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
        // Añadir mensaje enviado al chat
        const sentMessage = document.createElement('div');
        sentMessage.className = 'message sent';
        sentMessage.textContent = userMessage;
        chatBody.appendChild(sentMessage);

        // Simular respuesta automática
        const receivedMessage = document.createElement('div');
        receivedMessage.className = 'message received';
        receivedMessage.textContent = 'Estamos procesando tu consulta...';
        chatBody.appendChild(receivedMessage);

        chatInput.value = '';
        chatBody.scrollTop = chatBody.scrollHeight; // Scroll automático
    }
}

function handleKeyPress(event) {
    if (event.key === 'Enter') {
        sendMessage();
    }
}

// ELIMINAR QR 
setInterval(() => {

    fetch('../hrmt/eliminarQR_pre.php')
        .then(response => response.text())
        .then(data => {
            console.log('Verificación realizada:', data);
           // location.reload(); // Recarga la página
        });
}, 12000); 

// cargar los puros catalogo 
setInterval(() => {
    fetch(`../hrmt/pg-pres-mos.php?id_usr=${id_usr}`)
        .then(response => response.text())
        .then(html => {
              console.log('Verificación los campos:');
            document.getElementById('container-libros').innerHTML = html;
        })
        .catch(error => console.error('Error al actualizar libros:', error));
}, 800); // Cada 2 minutos


// Mostrar qr en prestamo
function MostrarQR(id_pres) {

    console.log("Enviando id_pres:", id_pres);

    fetch('../hrmt/mostrarQR_pe.php', {  
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'id_pres=' + encodeURIComponent(id_pres)
    })
    .then(response => response.json())  // Esperamos un JSON
    .then(data => {
      
        agregarToast({
      tipo: data.tipo || 'info',
      titulo: data.titulo || 'Información',
      descripcion: data.descripcion || 'Sin mensaje recibido.',
      autoCierre: true
    });
        // Mostrar los datos en el div "mos"
        console.log("URL de la imagen QR:", data.qr); // Verifica la URL
        document.getElementById('book-imge').src = data.qr;
        document.getElementById('mos').style.display = 'block';

    })
    .catch(error => console.error('Error en la solicitud:', error));
}


  async function devlib() {
            const id = document.getElementById("book-id").innerText;
            //console.log("id de préstamo:", id);

            try {
                const response = await fetch('../hrmt/pg-dev-us.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + encodeURIComponent(id)
                });

                const data = await response.json();
                console.log("Respuesta del servidor:", data);

                if (data.error) {
                    alert("Error: " + data.error);
                    return;
                }
            document.getElementById('book-mon').innerText="$" +data.Cuotas;
            } catch (error) {
                console.error("Error en la solicitud:", error);
            }
        }
//funcion para verificar
  async function devverf() {
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
                console.log("Respuesta del servidor:", data);

                if (data.error) {
                    alert("Error: " + data.error);
                    return;
                }
            document.getElementById('book-mon').innerText="$" +data.Cuotas;
            } catch (error) {
                console.error("Error en la solicitud:", error);
            }
        }


// Función para cerrar la sección de QR
function cerrar_li() {
    document.querySelector(".mos").style.display = "none";
    document.querySelector(".ver").style.display = "none";
}

//funcion  para abrir verificacion desde el boton
function verif() {
    document.querySelector(".mos").style.display = "none";
    document.querySelector(".ver").style.display = "block";
}

async function verificarCodigo() {
    const codigo = document.getElementById('codigo').value;
    console.log("Código ingresado:", codigo);

    try {
        const response = await fetch('../hrmt/pg-verif.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'codigo=' + encodeURIComponent(codigo)
        });

        const data = await response.json();
        console.log("Respuesta del servidor:", data);

  
         const Cuota = data.cuota;
    
            const dev = data.dev?.toLowerCase(); // aseguramos que esté en minúsculas
            const cuota = parseFloat(data.cuota); // convertimos cuota a número por si viene como string
        //Devoluciones de libros
            if (dev === 'entrega' && cuota === 0) {
              DevConLib(data.idpres);
 
        // Devoluciones sin entrega de libro
            } else if (dev === 'entrega sin libro') {
           paypalSN(cuota, data.idpres);

    
        // Devolución de pagos 
            } else if (dev === 'paypal') {
    
              paypalCN(cuota, data.idpres)
            }
        

    } catch (error) {
        console.error("Error en la solicitud:", error);
        alert("Ocurrió un error al verificar. Intenta de nuevo.");
    }
}


async function DevConLib(idpres) {
  console.log("Eliminar préstamo ID:", idpres);

  try {
    const response = await fetch("../hrmt/devol-eliPr.php", {
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
     // Recarga los préstamos si se eliminó
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

// PAYPAL PARA DEVOLUCION SIN LIBROS
let Cuota = null;
function paypalSN(cuota, idpres) {
 // ✅ Mostrar el modal
    const paypalDiv = document.getElementById('modalPaypal');
    paypalDiv.style.display = 'block';
    document.getElementById("Verificar").style.display = "none";
    document.getElementById('paypal-button-container').innerHTML = '';

    paypal.Buttons({
        style: {
            layout: 'vertical',
            color: 'blue',
            shape: 'pill',
            label: 'pay',
            height: 55
        },
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: cuota.toString()
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            actions.order.capture().then(function(detalles) {
                console.log("✅ Pago aprobado:", detalles);

                // ✅ Enviar los datos al servidor (PHP)
                fetch('../hrmt/devol-Snlib.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        cuota: cuota,
                        id_pres: idpres,
                        order_id: detalles.id,
                        payer_email: detalles.payer.email_address,
                        status:detalles.status,
                        fcha: detalles.update_time
                    })
                })
                .then(response => response.json())
                .then(data => {                  
                agregarToast({
                  tipo: data.tipo || 'info',
                  titulo: data.titulo || 'Información',
                  descripcion: data.descripcion || 'Sin mensaje recibido.',
                  autoCierre: true
                });
                  // console.log("id_pres",idpres);
                  //   console.log("🔁 Respuesta de PHP:", data);
                    // Puedes cerrar el modal si quieres:
                    document.getElementById('modalPaypal').style.display = 'none';
                })
                .catch(error => {
                    console.error("❌ Error al enviar al servidor:", error);
                    // alert("Ocurrió un error al guardar el pago.");
                });
            });
        },
        onCancel: function(data) {
             agregarToast({
      tipo: 'error',
      titulo: 'No procesado',
      descripcion: 'Pago cancelado.',
      autoCierre: true
    });
            // alert("❌ Pago cancelado");
            console.log("Cancel data:", data);
        }
    }).render('#paypal-button-container');
}




// PAYPAL PARA DEVOLUCION CON LIBRO 
function paypalCN(cuota, idpres) {
    // ✅ Mostrar el modal
    const paypalDiv = document.getElementById('modalPaypal');
    paypalDiv.style.display = 'block';
    document.getElementById("Verificar").style.display = "none";
    document.getElementById('paypal-button-container').innerHTML = '';

    paypal.Buttons({
        style: {
            layout: 'vertical',
            color: 'blue',
            shape: 'pill',
            label: 'pay',
            height: 55
        },
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: cuota.toString()
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            actions.order.capture().then(function(detalles) {
                console.log("✅ Pago aprobado:", detalles);

                // ✅ Enviar los datos al servidor (PHP)
                fetch('../hrmt/devol-Cnlib.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        cuota: cuota,
                        id_pres: idpres,
                        order_id: detalles.id,
                        payer_email: detalles.payer.email_address,
                        status:detalles.status,
                        fcha: detalles.update_time
                    })
                })
                .then(response => response.json())
                .then(data => {                  
                agregarToast({
                  tipo: data.tipo || 'info',
                  titulo: data.titulo || 'Información',
                  descripcion: data.descripcion || 'Sin mensaje recibido.',
                  autoCierre: true
                });
                  // console.log("id_pres",idpres);
                  //   console.log("🔁 Respuesta de PHP:", data);
                    // Puedes cerrar el modal si quieres:
                    document.getElementById('modalPaypal').style.display = 'none';
                })
                .catch(error => {
                    console.error("❌ Error al enviar al servidor:", error);
                    // alert("Ocurrió un error al guardar el pago.");
                });
            });
        },
        onCancel: function(data) {
             agregarToast({
      tipo: 'error',
      titulo: 'No procesado',
      descripcion: 'Pago cancelado.',
      autoCierre: true
    });
            // alert("❌ Pago cancelado");
            console.log("Cancel data:", data);
        }
    }).render('#paypal-button-container');
}

