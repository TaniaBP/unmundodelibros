
// BUSCAR
function BuscarL() {
  const query = document.getElementById('input-busqueda').value.trim();
  const contenedor = document.getElementById('contenedor-resultados');

  if (query === '') {
    contenedor.innerHTML = '<div class="alert alert-warning text-center">Ingresa una búsqueda.</div>';

    return;
  }

  fetch('../cont/buscar-libro.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `query=${encodeURIComponent(query)}`
  })
  .then(res => res.json())
  .then(data => {
    contenedor.innerHTML = '';

    if (data.success && data.resultados.length > 0) {
      data.resultados.forEach(libro => {
        contenedor.innerHTML += `
          <!-- Vista móvil -->
          <div class="col-12 d-md-none mb-3">
            <div class="p-3 rounded shadow-sm bg-white">
              <h2 class="titulo-libro">${libro.Nombre}</h2>
              <p class="autor-libro">${libro.Autor}</p>
              <p class="autor-libro">Tipo: ${libro.Tipo}</p>
              <div class="d-flex justify-content-center gap-2 mt-3">
                <button class="btn-info p-0 border-0 bg-transparent" onclick="CargarInfo('${libro.ISBN}')">
                  <img src="../../img/informacion.png" alt="Info" style="width: 20px; height: 20px;">
                </button>
                <button class="btn-fav p-0 border-0 bg-transparent" onclick="cambiarImagen(event)" data-isbn="${libro.ISBN}">
                  <img class="btn-fav-img" src="../../img/Cora-va.png" alt="Favorito" style="width: 20px; height: 20px;">
                </button>
              </div>
            </div>
          </div>

          <!-- Vista PC -->
          <div class="col-md-3 d-none d-md-flex flex-column align-items-center cont-libros mb-4">
            <div class="img">
              <img src="${libro.Portada}" alt="Portada del libro" class="img-fluid">
            </div>
            <div class="dtos text-center">
              <h2 class="titulo-libro">${libro.Nombre}</h2>
              <p class="autor-libro">${libro.Autor}</p>
            </div>
            <div class="buton d-flex gap-2">
              <button class="btn-info" onclick="CargarInfo('${libro.ISBN}')">
                <img src="../../img/informacion.png" alt="Info">
              </button>
              <button class="btn-fav p-0 border-0 bg-transparent" onclick="cambiarImagen(event)" data-isbn="${libro.ISBN}">
                <img class="btn-fav-img" src="../../img/Cora-va.png" alt="Favorito" style="width: 20px; height: 20px;">
              </button>
            </div>
          </div>
        `;
      });
    } else {
      contenedor.innerHTML = '<div class="alert alert-info text-center">No se encontraron resultados.</div>';
    }
  })
  .catch(err => {
    console.error('Error:', err);
    contenedor.innerHTML = '<div class="alert alert-danger text-center">Ocurrió un error al buscar.</div>';
  });
}

async function BuscarA() {
  const query = document.getElementById("input-busqueda").value.trim();
  const tabla = document.getElementById("tabla");

  if (query === '') {
    tabla.innerHTML = '<div class="alert alert-warning text-center">Ingresa un término de búsqueda.</div>';
    return;
  }

  try {
    const res = await fetch("../cont/buscarad.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `query=${encodeURIComponent(query)}`
    });

    const data = await res.json();
    let html = '';

    if (!data.success) {
      tabla.innerHTML = `<div class="alert alert-danger text-center">${data.descripcion}</div>`;
      return;
    }

    if (data.usuarios.length === 0 && data.prestamos.length === 0 && data.libros.length === 0 && data.pagos.length === 0 && data.devoluciones.length === 0) {
      tabla.innerHTML = '<div class="alert alert-info text-center">No se encontraron resultados.</div>';
      return;
    }

    // Usuarios
    if (data.usuarios.length > 0) {
      html += `
        <thead class="table-info">
          <tr><th colspan="6">Usuarios encontrados</th></tr>
          <tr><th>Ncontrol</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Tipo</th><th>Acciones</th></tr>
        </thead><tbody>`;
      data.usuarios.forEach(u => {
        html += `
          <tr>
            <td>${u.Ncontrol}</td>
            <td>${u.Nombre}</td>
            <td>${u.Email}</td>
            <td>${u.Telefono}</td>
            <td>${u.Tipo}</td>
          <td>
            <a href=\"javascript:abUser('${u.Ncontrol}')\" class='btn btn-sm btn-warning' title='Editar'>
                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' viewBox='0 0 24 24'>
                    <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                    <path d='M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1'/>
                    <path d='M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z'/>
                    <path d='M16 5l3 3'/>
                </svg>
            </a>
        </td>
        <td>
            <a href=\"javascript:EliminarUser('${u.Ncontrol}')\" class='btn btn-sm btn-danger' title='Eliminar' >
                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' viewBox='0 0 24 24'>
                    <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                    <path d='M18.333 6a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-3.333 -4c1.094 0 1.828 .533 2.374 1.514a1 1 0 1 1 -1.748 .972c-.221 -.398 -.342 -.486 -.626 -.486h-10c-.548 0 -1 .452 -1 1v9.998c0 .32 .154 .618 .407 .805l.1 .065a1 1 0 1 1 -.99 1.738a3 3 0 0 1 -1.517 -2.606v-10c0 -1.652 1.348 -3 3 -3zm.8 8.786l-1.837 1.799l-1.749 -1.785a1 1 0 0 0 -1.319 -.096l-.095 .082a1 1 0 0 0 -.014 1.414l1.749 1.785l-1.835 1.8a1 1 0 0 0 -.096 1.32l.082 .095a1 1 0 0 0 1.414 .014l1.836 -1.8l1.75 1.786a1 1 0 0 0 1.319 .096l.095 -.082a1 1 0 0 0 .014 -1.414l-1.75 -1.786l1.836 -1.8a1 1 0 0 0 .096 -1.319l-.082 -.095a1 1 0 0 0 -1.414 -.014'/>
                </svg>
            </a>
        </td>
          </tr>`;
      });
      html += '</tbody>';
    }

    // Préstamos
    if (data.prestamos.length > 0) {
      html += `
        <thead class="table-info mt-5">
          <tr><th colspan="9">Préstamos encontrados</th></tr>
          <tr>
            <th>Ncontrol</th><th>ISBN</th><th>Fecha préstamo</th><th>Fecha devolución</th><th>QR</th><th>Estado</th><th>Cuotas</th><th colspan="2">Acciones</th>
          </tr>
        </thead><tbody>`;
      data.prestamos.forEach(p => {
        html += `
          <tr>
            <td>${p.Ncontrol}</td>
            <td>${p.ISBN}</td>
            <td>${p.Fcha_pres}</td>
            <td>${p.Fcha_dev}</td>
            <td>${p.Nom_qr}</td>
            <td>${p.Estado}</td>
            <td>$${p.Cuota}.00</td>
         <td>
            <a href=\"javascript:abPrest('${p.id_pres}')\" class='btn btn-sm btn-warning' title='Editar'>
                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' viewBox='0 0 24 24'>
                    <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                    <path d='M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1'/>
                    <path d='M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z'/>
                    <path d='M16 5l3 3'/>
                </svg>
            </a>
        </td>
        <td>
            <a href=\"javascript:EliminarPres('${p.id_pres}')\" class='btn btn-sm btn-danger' title='Eliminar'>
                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' viewBox='0 0 24 24'>
                    <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                    <path d='M18.333 6a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-3.333 -4c1.094 0 1.828 .533 2.374 1.514a1 1 0 1 1 -1.748 .972c-.221 -.398 -.342 -.486 -.626 -.486h-10c-.548 0 -1 .452 -1 1v9.998c0 .32 .154 .618 .407 .805l.1 .065a1 1 0 1 1 -.99 1.738a3 3 0 0 1 -1.517 -2.606v-10c0 -1.652 1.348 -3 3 -3zm.8 8.786l-1.837 1.799l-1.749 -1.785a1 1 0 0 0 -1.319 -.096l-.095 .082a1 1 0 0 0 -.014 1.414l1.749 1.785l-1.835 1.8a1 1 0 0 0 -.096 1.32l.082 .095a1 1 0 0 0 1.414 .014l1.836 -1.8l1.75 1.786a1 1 0 0 0 1.319 .096l.095 -.082a1 1 0 0 0 .014 -1.414l-1.75 -1.786l1.836 -1.8a1 1 0 0 0 .096 -1.319l-.082 -.095a1 1 0 0 0 -1.414 -.014'/>
                </svg>
            </a>
        </td>
          </tr>`;
      });
      html += '</tbody>';
    }

    // Libros
    if (data.libros.length > 0) {
      html += `
        <thead class="table-info mt-5">
          <tr><th colspan="7">Libros encontrados</th></tr>
          <tr>
            <th>ISBN</th><th>Nombre</th><th>Autor</th><th>Tipo</th><th>Existencias</th><th>Ubicación</th><th>Acciones</th>
          </tr>
        </thead><tbody>`;
      data.libros.forEach(l => {
        html += `
          <tr>
            <td>${l.ISBN}</td>
            <td>${l.Nombre}</td>
            <td>${l.Autor}</td>
            <td>${l.Tipo}</td>
            <td>${l.Existencia}</td>
            <td>${l.Ubicacion}</td>
             <td>
            <a href=\"javascript:abrirLibro('{$datos->ISBN}')\" class='btn btn-sm btn-warning' title='Editar'>
                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' viewBox='0 0 24 24'>
                    <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                    <path d='M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1'/>
                    <path d='M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z'/>
                    <path d='M16 5l3 3'/>
                </svg>
            </a>
        </td>
        <td>
            <a href=\"javascript:EliminarLibro('${l.ISBN}')\" class='btn btn-sm btn-danger' title='Eliminar' >
                <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='currentColor' viewBox='0 0 24 24'>
                    <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                    <path d='M18.333 6a3.667 3.667 0 0 1 3.667 3.667v8.666a3.667 3.667 0 0 1 -3.667 3.667h-8.666a3.667 3.667 0 0 1 -3.667 -3.667v-8.666a3.667 3.667 0 0 1 3.667 -3.667zm-3.333 -4c1.094 0 1.828 .533 2.374 1.514a1 1 0 1 1 -1.748 .972c-.221 -.398 -.342 -.486 -.626 -.486h-10c-.548 0 -1 .452 -1 1v9.998c0 .32 .154 .618 .407 .805l.1 .065a1 1 0 1 1 -.99 1.738a3 3 0 0 1 -1.517 -2.606v-10c0 -1.652 1.348 -3 3 -3zm.8 8.786l-1.837 1.799l-1.749 -1.785a1 1 0 0 0 -1.319 -.096l-.095 .082a1 1 0 0 0 -.014 1.414l1.749 1.785l-1.835 1.8a1 1 0 0 0 -.096 1.32l.082 .095a1 1 0 0 0 1.414 .014l1.836 -1.8l1.75 1.786a1 1 0 0 0 1.319 .096l.095 -.082a1 1 0 0 0 .014 -1.414l-1.75 -1.786l1.836 -1.8a1 1 0 0 0 .096 -1.319l-.082 -.095a1 1 0 0 0 -1.414 -.014'/>
                </svg>
            </a>
        </td>
          </tr>`;
      });
      html += '</tbody>';
    }

    // Pagos
    if (data.pagos.length > 0) {
      html += `
        <thead class="table-info mt-5">
          <tr><th colspan="7">Pagos encontrados</th></tr>
          <tr>
            <th>ID Préstamo</th><th>Ncontrol</th><th>Tipo Devolución</th><th>Fecha Pago</th><th>Pago</th><th>Cuota</th><th>Acciones</th>
          </tr>
        </thead><tbody>`;
      data.pagos.forEach(pg => {
        html += `
          <tr>
            <td>${pg.id_pres}</td>
            <td>${pg.Ncontrol}</td>
            <td>${pg.tipo_dev}</td>
            <td>${pg.Fcha_pag}</td>
            <td>${pg.EstadoPago}</td>
            <td>$${pg.Cuota}</td>
                <td>
    <a href=\"javascript:abPag('${pg.id_pag}')\" class=\"btn btn-sm btn-warning\" title=\"Información\">
        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"icon icon-tabler icons-tabler-outline icon-tabler-file-info\">
            <path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/>
            <path d=\"M14 3v4a1 1 0 0 0 1 1h4\" />
            <path d=\"M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z\" />
            <path d=\"M11 14h1v4h1\" />
            <path d=\"M12 11h.01\" />
        </svg>
    </a>
</td>
          </tr>`;
      });
      html += '</tbody>';
    }

    // Devoluciones
    if (data.devoluciones.length > 0) {
      html += `
        <thead class="table-info mt-5">
          <tr><th colspan="6">Devoluciones encontradas</th></tr>
          <tr><th>ID Préstamo</th><th>Ncontrol</th><th>Tipo</th><th>Fecha</th><th>Devolución</th><th>Acciones</th></tr>
        </thead><tbody>`;
      data.devoluciones.forEach(dev => {
        html += `
          <tr>
            <td>${dev.id_pres}</td>
            <td>${dev.Ncontrol}</td>
            <td>${dev.tipo_dev}</td>
            <td>${dev.Fcha_dev}</td>
            <td>${dev.Devolucion}</td>
                 <td>
    <a href=\"javascript:abDev('${dev.id_dev})\" class=\"btn btn-sm btn-warning\" title=\"Información\">
        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"icon icon-tabler icons-tabler-outline icon-tabler-file-info\">
            <path stroke=\"none\" d=\"M0 0h24v24H0z\" fill=\"none\"/>
            <path d=\"M14 3v4a1 1 0 0 0 1 1h4\" />
            <path d=\"M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z\" />
            <path d=\"M11 14h1v4h1\" />
            <path d=\"M12 11h.01\" />
        </svg>
    </a>
</td>
          </tr>`;
      });
      html += '</tbody>';
    }

    tabla.innerHTML = html;

  } catch (error) {
    console.error("Error:", error);
    tabla.innerHTML = '<div class="alert alert-danger text-center">Error al realizar la búsqueda.</div>';
  }
}


async function BuscarB() {
  const query = document.getElementById("input-busqueda").value.trim();
  const tabla = document.getElementById("tabla");

  if (query === '') {
    tabla.innerHTML = '<div class="alert alert-warning text-center">Ingresa un término de búsqueda.</div>';
    return;
  }

  try {
    const res = await fetch("../cont/buscarad.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `query=${encodeURIComponent(query)}`
    });

    const data = await res.json();
    let html = '';

    if (!data.success) {
      tabla.innerHTML = `<div class="alert alert-danger text-center">${data.descripcion}</div>`;
      return;
    }

    if (data.usuarios.length === 0 && data.prestamos.length === 0 && data.libros.length === 0 && data.pagos.length === 0 && data.devoluciones.length === 0) {
      tabla.innerHTML = '<div class="alert alert-info text-center">No se encontraron resultados.</div>';
      return;
    }

    // Usuarios
    if (data.usuarios.length > 0) {
      html += `
        <thead class="table-info">
          <tr><th colspan="6">Usuarios encontrados</th></tr>
          <tr><th>Ncontrol</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Tipo</th></tr>
        </thead><tbody>`;
      data.usuarios.forEach(u => {
        html += `
          <tr>
            <td>${u.Ncontrol}</td>
            <td>${u.Nombre}</td>
            <td>${u.Email}</td>
            <td>${u.Telefono}</td>
            <td>${u.Tipo}</td>

          </tr>`;
      });
      html += '</tbody>';
    }

    // Préstamos
    if (data.prestamos.length > 0) {
      html += `
        <thead class="table-info mt-5">
          <tr><th colspan="9">Préstamos encontrados</th></tr>
          <tr>
            <th>Ncontrol</th><th>ISBN</th><th>Fecha préstamo</th><th>Fecha devolución</th><th>QR</th><th>Estado</th><th>Cuotas</th>
          </tr>
        </thead><tbody>`;
      data.prestamos.forEach(p => {
        html += `
          <tr>
            <td>${p.Ncontrol}</td>
            <td>${p.ISBN}</td>
            <td>${p.Fcha_pres}</td>
            <td>${p.Fcha_dev}</td>
            <td>${p.Nom_qr}</td>
            <td>${p.Estado}</td>
            <td>$${p.Cuota}.00</td>
      
          </tr>`;
      });
      html += '</tbody>';
    }

    // Libros
    if (data.libros.length > 0) {
      html += `
        <thead class="table-info mt-5">
          <tr><th colspan="7">Libros encontrados</th></tr>
          <tr>
            <th>ISBN</th><th>Nombre</th><th>Autor</th><th>Tipo</th><th>Existencias</th><th>Ubicación</th>
          </tr>
        </thead><tbody>`;
      data.libros.forEach(l => {
        html += `
          <tr>
            <td>${l.ISBN}</td>
            <td>${l.Nombre}</td>
            <td>${l.Autor}</td>
            <td>${l.Tipo}</td>
            <td>${l.Existencia}</td>
            <td>${l.Ubicacion}</td>
    
          </tr>`;
      });
      html += '</tbody>';
    }





    tabla.innerHTML = html;

  } catch (error) {
    console.error("Error:", error);
    tabla.innerHTML = '<div class="alert alert-danger text-center">Error al realizar la búsqueda.</div>';
  }
}
