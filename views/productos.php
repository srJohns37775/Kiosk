<?php
session_start();
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], ['admin', 'usuario'])) {
  header('Location: ../index.html');
  exit;
}

$usuario = $_SESSION['usuario'];
$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Productos - Sonic Kiosco</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="../css/sidebar.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-dark text-white">

<div id="particles-js"></div>

<div class="d-flex">
  <nav class="sidebar">
    <h4 class="text-center fw-bold text-primary">Sonic Kiosco</h4>
    <hr class="bg-light" />
    <p class="small"><strong>Usuario:</strong> <?= htmlspecialchars($usuario) ?></p>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link text-white" href="dashboard.php">🏠 Dashboard</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="maestros.php">🛠️ Maestros</a></li>
      <li class="nav-item"><a class="nav-link text-white active" href="#">📦 Stock</a></li>
      <li class="nav-item mt-3"><a class="nav-link text-danger" href="../controllers/logout.php">🔒 Cerrar sesión</a></li>
    </ul>
  </nav>

  <main class="main-content p-4 flex-fill position-relative">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Productos</h2>
      <span class="badge bg-primary text-dark"><?= strtoupper($rol) ?></span>
    </div>

    <div class="glass-card mb-4">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h5>Listado de Productos</h5>
        <?php if ($rol === 'admin'): ?>
          <button class="btn btn-success btn-sm" onclick="abrirModalProducto()">➕ Agregar Producto</button>
        <?php endif; ?>
      </div>
      <input type="text" id="buscador" class="form-control mb-3" placeholder="🔍 Buscar producto...">
      <ul id="lista-productos" class="list-group list-group-flush"></ul>
    </div>

  </main>
</div>

<?php if ($rol === 'admin'): ?>
  <?php include '../modales/modal_producto.php'; ?>
  <?php include '../modales/modal_ingreso_stock.php'; ?>
  <?php include '../modales/modal_editar_ingresos.php'; ?>
  <?php include '../modales/modalEditarIngresoStock.php'; ?>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
<script src="../js/particles_sonic.js"></script>
<script>
  let productosData = [];
  // Abrir modal solo si existe (es decir, si el rol es admin)
  function abrirModalProducto() {
    const modalElement = document.getElementById('modalProducto');
    if (modalElement) {
      document.getElementById('formProducto').reset(); //Limpia el formulario
      document.getElementById('productoId').value = ''; //Limpia el ID
      const modal = new bootstrap.Modal(modalElement);
      modal.show();
    } else {
      console.warn('No tienes permisos para agregar productos.');
    }
  }
  //Cargar Categorias y marcas en los dropdowns
  function cargarSelect(url, selectElement) {
    fetch(url)
      .then(res => res.json())
      .then(data => {
        selectElement.innerHTML = '<option value="">Seleccione</option>';
        data.forEach(item => {
          const opt = document.createElement('option');
          opt.value = item.id;
          opt.textContent = item.nombre;
          selectElement.appendChild(opt);
        });
      })
      .catch(err => {
        console.error('Error cargando opciones:', err);
      });
  }

  const categoriaSelect = document.querySelector('[name="categoria_id"]');
  const marcaSelect = document.querySelector('[name="marca_id"]');

  if (categoriaSelect && marcaSelect) {
    cargarSelect('../controllers/get_categorias.php', categoriaSelect);
    cargarSelect('../controllers/get_marcas.php', marcaSelect);
  }
  // Calcular precio de venta automáticamente al cambiar costo o markup
  document.addEventListener('DOMContentLoaded', function () {
    const categoriaSelect = document.querySelector('[name="categoria_id"]');
    const marcaSelect = document.querySelector('[name="marca_id"]');
    const precioCostoInput = document.querySelector('[name="precio_costo"]');
    const markupInput = document.querySelector('[name="markup"]');
    const precioVentaInput = document.querySelector('[name="precio_venta"]');

    // MODAL PRODUCTO
    const esPackCheckbox = document.getElementById('esPack');
    const packFields = document.getElementById('packFields');
    const unidadesInput = document.querySelector('[name="unidades"]');
    const cantidadPacksInput = document.querySelector('[name="cantidad_packs"]');
    const unidadesPackInput = document.querySelector('[name="unidades_pack"]');

    // MODAL INGRESO STOCK
    const esPackIngresoCheckbox = document.getElementById('esPackIngreso');
    const packIngresoFields = document.getElementById('packIngresoFields');
    const cantidadPacksIngreso = document.querySelector('#packIngresoFields [name="cantidad_packs"]');
    const unidadesPackIngreso = document.querySelector('#packIngresoFields [name="unidades_pack"]');
    const cantidadFinal = document.querySelector('[name="cantidad"]');

    cargarProductos();

    if (categoriaSelect && marcaSelect) {
      cargarSelect('../controllers/get_categorias.php', categoriaSelect);
      cargarSelect('../controllers/get_marcas.php', marcaSelect);
    }

    function calcularPrecioVenta() {
      const costo = parseFloat(precioCostoInput.value) || 0;
      const markup = parseFloat(markupInput.value) || 0;
      const venta = costo + (costo * markup / 100);
      precioVentaInput.value = venta.toFixed(2);
    }

    if (precioCostoInput && markupInput && precioVentaInput) {
      precioCostoInput.addEventListener('input', calcularPrecioVenta);
      markupInput.addEventListener('input', calcularPrecioVenta);
    }

    function actualizarUnidades() {
      const cantidad = parseInt(cantidadPacksInput.value) || 0;
      const unidadesPorPack = parseInt(unidadesPackInput.value) || 0;
      const unidadesTotales = cantidad * unidadesPorPack;
      unidadesInput.value = unidadesTotales;
    }

    if (esPackCheckbox && packFields) {
      esPackCheckbox.addEventListener('change', () => {
        if (esPackCheckbox.checked) {
          packFields.classList.remove('d-none');
          unidadesInput.readOnly = true;
          actualizarUnidades();
        } else {
          packFields.classList.add('d-none');
          unidadesInput.readOnly = false;
          unidadesInput.value = '';
        }
      });

      cantidadPacksInput.addEventListener('input', actualizarUnidades);
      unidadesPackInput.addEventListener('input', actualizarUnidades);
    }

    // 🔁 Lógica ingreso con packs
    function actualizarCantidadPorPack() {
      const packs = parseInt(cantidadPacksIngreso.value) || 0;
      const unidades = parseInt(unidadesPackIngreso.value) || 0;
      const total = packs * unidades;
      if (cantidadFinal) cantidadFinal.value = total > 0 ? total : '';
    }

    if (esPackIngresoCheckbox && packIngresoFields) {
      esPackIngresoCheckbox.addEventListener('change', () => {
        if (esPackIngresoCheckbox.checked) {
          packIngresoFields.style.display = 'flex';
          actualizarCantidadPorPack();
        } else {
          packIngresoFields.style.display = 'none';
          cantidadPacksIngreso.value = '';
          unidadesPackIngreso.value = '';
          cantidadFinal.value = '';
        }
      });

      cantidadPacksIngreso.addEventListener('input', actualizarCantidadPorPack);
      unidadesPackIngreso.addEventListener('input', actualizarCantidadPorPack);
    }
  });

  //Formulario AJAX
  const formProducto = document.getElementById('formProducto');

  formProducto.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(formProducto);

    fetch('../controllers/guardar_producto.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire('¡Guardado!', data.mensaje, 'success');
          formProducto.reset();
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalProducto'));
          if (modal) modal.hide();
          cargarProductos();
        } else {
          Swal.fire('Error', data.error || 'No se pudo guardar el producto', 'error');
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Hubo un problema al guardar', 'error');
      });
  });

  const formIngresoStock = document.getElementById('formIngresoStock');

  formIngresoStock.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(formIngresoStock);

    fetch('../controllers/guardar_ingreso_stock.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire('¡Ingreso registrado!', data.mensaje, 'success');
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalIngresoStock'));
          if (modal) modal.hide();
          formIngresoStock.reset();
          cargarProductos();
        } else {
          Swal.fire('Error', data.mensaje || 'No se pudo registrar el ingreso', 'error');
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Hubo un problema al guardar', 'error');
      });
  });

  //Cargar los productos por AJAX
  function cargarProductos() {
    fetch('../controllers/listar_productos.php')
      .then(res => res.json())
      .then(data => {
        productosData = data; // Guardamos todos los productos
        renderizarProductos(productosData); // Renderizamos todos al principio
        const lista = document.getElementById('lista-productos');
        lista.innerHTML = '';

        if (!Array.isArray(data)) {
          lista.innerHTML = '<li class="list-group-item bg-dark text-danger">Error cargando productos</li>';
          return;
        }

        if (data.length === 0) {
          lista.innerHTML = '<li class="list-group-item bg-dark text-warning">No hay productos registrados</li>';
          return;
        }

        data.forEach(prod => {
          const li = document.createElement('li');
          li.className = 'list-group-item bg-dark text-white d-flex justify-content-between align-items-center';

          li.innerHTML = `
            <div>
              <strong>${prod.descripcion}</strong><br>
              <small>${prod.categoria} | ${prod.marca}</small><br>
              <small>Stock: ${prod.unidades_totales} 
              ${prod.stock_minimo && prod.unidades_totales < prod.stock_minimo ? `<span class="text-danger">(bajo)</span>` : ''}
              </small>
              <small>Min: ${prod.stock_minimo}</small>
            </div>
            <div class="d-flex align-items-center gap-2">
              <span class="badge bg-success">$${parseFloat(prod.precio_venta).toFixed(2)}</span>
              ${'<?= $rol ?>' === 'admin' ? `
                <button class="btn btn-sm btn-warning" onclick='editarProducto(${JSON.stringify(prod)})'>✏️</button>
                <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${prod.id})">🗑️</button>
                <button class="btn btn-sm btn-info" onclick="abrirModalIngreso(${prod.id}, '${prod.descripcion}')">📥 Ingreso</button>
                <button class="btn btn-sm btn-secondary" onclick="mostrarIngresos(${prod.id}, '${prod.descripcion}')">🧾 Ver Ingresos</button>

              ` : ''}
            </div>
          `;


          lista.appendChild(li);
        });
      })
      .catch(err => {
        console.error(err);
        document.getElementById('lista-productos').innerHTML =
          '<li class="list-group-item bg-dark text-danger">Error al cargar productos</li>';
      });
  }
  // Filtro de búsqueda
  document.getElementById('buscador').addEventListener('input', function () {
    const filtro = this.value.trim().toLowerCase();

    const filtrados = productosData.filter(prod =>
      prod.descripcion.toLowerCase().includes(filtro) ||
      prod.categoria.toLowerCase().includes(filtro) ||
      prod.marca.toLowerCase().includes(filtro)
    );

    renderizarProductos(filtrados);
  });

  function editarProducto(prod) {
    const modal = new bootstrap.Modal(document.getElementById('modalProducto'));
    modal.show();

    document.getElementById('productoId').value = prod.id;
    formProducto.descripcion.value = prod.descripcion;
    formProducto.categoria_id.value = prod.categoria_id;
    formProducto.marca_id.value = prod.marca_id;
    formProducto.unidades.value = prod.unidades_totales;
    formProducto.stock_minimo.value = prod.stock_minimo;
    formProducto.precio_costo.value = prod.precio_costo;
    formProducto.markup.value = prod.markup;
    formProducto.precio_venta.value = prod.precio_venta;

    // Pack: solo activa el checkbox, no intenta cargar campos que ya no existen
    if (prod.usa_pack === "1" || prod.usa_pack === 1) {
      document.getElementById('esPack').checked = true;
      document.getElementById('packFields').classList.remove('d-none');
      formProducto.unidades.readOnly = true;
    } else {
      document.getElementById('esPack').checked = false;
      document.getElementById('packFields').classList.add('d-none');
      formProducto.unidades.readOnly = false;
    }
  }

  function eliminarProducto(id) {
    Swal.fire({
      title: '¿Eliminar producto?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('../controllers/eliminar_producto.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id })
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            Swal.fire('Eliminado', data.mensaje, 'success');
            cargarProductos();
          } else {
            Swal.fire('Error', data.error || 'No se pudo eliminar', 'error');
          }
        })
        .catch(() => Swal.fire('Error', 'No se pudo eliminar el producto', 'error'));
      }
    });
  }
  function renderizarProductos(listaFiltrada) {
      const lista = document.getElementById('lista-productos');
      lista.innerHTML = '';

      if (!Array.isArray(listaFiltrada)) {
          lista.innerHTML = '<li class="list-group-item bg-dark text-danger">Error: datos inválidos</li>';
          console.error("La lista recibida no es un array:", listaFiltrada);
          return;
      }

      if (listaFiltrada.length === 0) {
          lista.innerHTML = '<li class="list-group-item bg-dark text-warning">No se encontraron productos</li>';
          return;
      }

      listaFiltrada.forEach(prod => {
          const li = document.createElement('li');
          li.className = 'list-group-item bg-dark text-white d-flex justify-content-between align-items-center';

          // Mostrar precio de costo más reciente y stock total
          li.innerHTML = `
              <div>
                  <strong>${prod.descripcion}</strong><br>
                  <small>${prod.categoria} | ${prod.marca}</small><br>
                  <small>Stock: ${prod.unidades_totales} | Costo: $${(parseFloat(prod.precio_costo) || 0).toFixed(2)}</small>
                  
              </div>
              <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-success">$${(parseFloat(prod.precio_venta) || 0).toFixed(2)}</span>
                  ${'<?= $rol ?>' === 'admin' ? `
                      <button class="btn btn-sm btn-warning" onclick='editarProducto(${JSON.stringify(prod)})'>✏️</button>
                      <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${prod.id})">🗑️</button>
                      <button class="btn btn-sm btn-info" onclick="abrirModalIngreso(${prod.id}, '${prod.descripcion}')">📥 Ingreso</button>
                  ` : ''}
              </div>
          `;
          lista.appendChild(li);
      });
  }
  function abrirModalIngreso(id, descripcion) {
      document.getElementById('formIngresoStock').reset();

      // Setear valores en el modal
      const productoInput = document.getElementById('producto_id_ingreso');
      const descripcionInput = document.getElementById('descripcionProductoSeleccionado');

      productoInput.value = id;
      descripcionInput.value = descripcion;
      descripcionInput.textContent = descripcion;

      // Mostrar campos pack si corresponde
      const prod = productosData.find(p => p.id == id);
      const usaPack = prod && prod.usa_pack == 1;

      document.getElementById('esPackIngreso').checked = false;
      const packFields = document.getElementById('packIngresoFields');
      if (usaPack) {
        document.getElementById('esPackIngreso').parentElement.style.display = 'block';
      } else {
        document.getElementById('esPackIngreso').parentElement.style.display = 'none';
        packFields.style.display = 'none';
      }

      // Mostrar modal
      const modal = new bootstrap.Modal(document.getElementById('modalIngresoStock'));
      modal.show();
    }

    function cargarSelectProductos() {
    const select = document.getElementById('producto_id_ingreso');
    if (!select) return;

    fetch('../controllers/get_productos_basicos.php')
      .then(res => res.json())
      .then(data => {
        select.innerHTML = '<option value="">Seleccione un producto</option>';
        data.forEach(prod => {
          const option = document.createElement('option');
          option.value = prod.id;
          option.textContent = prod.descripcion;
          select.appendChild(option);
        });
      })
      .catch(err => {
        console.error("Error al cargar productos:", err);
      });
  }

  // Ejecutar al cargar DOM
  document.addEventListener('DOMContentLoaded', cargarSelectProductos);

    document.getElementById('producto_id_ingreso').addEventListener('change', function () {
    const id = this.value;
    if (!id) return;

    // Buscar si el producto usa pack
    const prod = productosData.find(p => p.id == id);
    if (prod && prod.usa_pack == 1) {
      document.getElementById('packIngresoFields').style.display = 'flex';
    } else {
      document.getElementById('packIngresoFields').style.display = 'none';
    }
  });
  //FUNCION PARA ABRIR EL MODAL DE INGRESO
  function abrirModalEditarIngreso(ingreso) {
    document.getElementById('id_ingreso').value = ingreso.id;
    document.getElementById('producto_id_edit').value = ingreso.producto_id;
    document.getElementById('numero_boleta_edit').value = ingreso.numero_boleta || '';
    document.getElementById('proveedor_edit').value = ingreso.proveedor || '';
    document.getElementById('precio_costo_edit').value = ingreso.precio_costo;
    document.getElementById('cantidad_total_edit').value = ingreso.cantidad_total;
    document.getElementById('fecha_vencimiento_edit').value = ingreso.fecha_vencimiento || '';

    const modal = new bootstrap.Modal(document.getElementById('modalEditarIngresoStock'));
    modal.show();
  }

  //FUNCION PARA MOSTRAR LOS INGRESOS EN EL MODAL DE EDICION
  function mostrarIngresos(producto_id, descripcion) {
    const modal = new bootstrap.Modal(document.getElementById('modalVerIngresos'));
    document.getElementById('nombreProductoIngresos').textContent = descripcion;
    const tablaBody = document.getElementById('tablaIngresosBody');

    tablaBody.innerHTML = `<tr><td colspan="7" class="text-center text-warning">Cargando...</td></tr>`;

    fetch(`../controllers/ingresos_por_producto.php?id=${producto_id}`)
      .then(res => res.json())
      .then(data => {
        if (!data.success || !Array.isArray(data.ingresos)) {
          tablaBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">No se pudieron obtener los ingresos</td></tr>`;
          return;
        }

        if (data.ingresos.length === 0) {
          tablaBody.innerHTML = `<tr><td colspan="7" class="text-center text-info">Sin ingresos registrados</td></tr>`;
          return;
        }

        tablaBody.innerHTML = '';
        data.ingresos.forEach(ing => {
          const row = document.createElement('tr');
          row.innerHTML = `
            <td>${ing.numero_boleta || '-'}</td>
            <td>${ing.fecha_ingreso.split(' ')[0]}</td>
            <td>${ing.cantidad_total}</td>
            <td>$${parseFloat(ing.precio_costo).toFixed(2)}</td>
            <td>${ing.proveedor || '-'}</td>
            <td>${ing.fecha_vencimiento || '-'}</td>
            <td>
              <button class="btn btn-sm btn-warning" onclick="editarIngresoStock(${ing.id})">✏️</button>
              <button class="btn btn-sm btn-danger" onclick="eliminarIngresoStock(${ing.id})">🗑️</button>
            </td>
          `;
          tablaBody.appendChild(row);
        });

        modal.show();
      })
      .catch(err => {
        console.error(err);
        tablaBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Error cargando ingresos</td></tr>`;
      });
  }
  //FUNCION PARA EDITAR PRODUCTO STOCK
  function editarIngresoStock(id) {
    fetch(`../controllers/get_ingreso_stock.php?id=${id}`)
      .then(res => res.json())
      .then(data => {
        if (!data.success || !data.ingreso) {
          Swal.fire('Error', 'No se pudo obtener el ingreso', 'error');
          return;
        }

        abrirModalEditarIngreso(data.ingreso);
      })
      .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Hubo un problema al cargar el ingreso', 'error');
      });
  }
  //envio al formulario de edicion
  const formEditarIngresoStock = document.getElementById('formEditarIngresoStock');
  if (formEditarIngresoStock) {
    formEditarIngresoStock.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(formEditarIngresoStock);

      fetch('../controllers/editar_ingreso_stock.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Actualizado', data.mensaje, 'success');
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarIngresoStock'));
          if (modal) modal.hide();
          cargarProductos();
        } else {
          Swal.fire('Error', data.mensaje || 'No se pudo actualizar', 'error');
        }
      })
      .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Error al guardar los cambios', 'error');
      });
    });
  }


</script>
</body>
</html>
