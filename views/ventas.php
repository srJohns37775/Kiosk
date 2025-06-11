<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: ../index.html');
  exit;
}

$usuario = $_SESSION['usuario'];
$rol = $_SESSION['rol'];
require_once '../config/db.php';
// Consulta para obtener las ventas registradas inclusive las anuladas
$query_ventas = "SELECT v.id, v.fecha, v.total, v.anulada, u.nombre as usuario 
                 FROM ventas v 
                 JOIN usuarios u ON v.usuario_id = u.id 
                 ORDER BY v.fecha DESC";
$stmt_ventas = $pdo->query($query_ventas);
$ventas = $stmt_ventas->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ventas - Sonic Kiosco</title>
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
      <li class="nav-item"><a class="nav-link text-white" href="productos.php">📦 Stock</a></li>
      <li class="nav-item"><a class="nav-link text-white active" href="#">🧾 Ventas</a></li>
      <li class="nav-item mt-3"><a class="nav-link text-danger" href="../controllers/logout.php">🔒 Cerrar sesión</a></li>
    </ul>
  </nav>

  <main class="main-content p-4 flex-fill position-relative">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Ventas</h2>
      <span class="badge bg-primary text-dark"><?= strtoupper($rol) ?></span>
    </div>

    <div class="glass-card">
      <div class="d-flex justify-content-between align-items-center mb-2">
          <h5>Registro de Ventas</h5>
          <button class="btn btn-success btn-lg px-4 py-2 fw-bold shadow" style="font-size: 1.12rem;" onclick="abrirModalVenta()">
              ➕ Nueva Venta
          </button>
      </div>
      
      <div class="table-responsive">
          <table class="table table-dark table-hover">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Fecha</th>
                      <th>Total</th>
                      <th>Usuario</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                  </tr>
              </thead>
              <tbody>
                  <?php if (count($ventas) > 0): ?>
                      <?php foreach ($ventas as $venta): ?>
                          <tr>
                              <td><?= htmlspecialchars($venta['id']) ?></td>
                              <td><?= htmlspecialchars($venta['fecha']) ?></td>
                              <td>$<?= number_format($venta['total'], 2, ',', '.') ?></td>
                              <td><?= htmlspecialchars($venta['usuario']) ?></td>
                              <td>
                                <?php if ($venta['anulada']): ?>
                                  <span class="badge bg-danger">Venta Anulada</span>
                                <?php else: ?>
                                  <span class="badge bg-success">Activa</span>
                                <?php endif; ?>
                              </td>
                              <td>
                                  <button class="btn btn-sm btn-warning" onclick="editarVenta(<?= $venta['id'] ?>)">Editar</button>
                                  <!-- <button class="btn btn-sm btn-danger" onclick="devolverStock(<?= $venta['id'] ?>)">Devolver Stock</button> -->
                                  <?php if (!$venta['anulada']): ?>
                                    <button class="btn btn-sm btn-danger" onclick="devolverStock(<?= $venta['id'] ?>)">Anular Venta</button>
                                  <?php else: ?>
                                    <button class="btn btn-sm btn-secondary" disabled>Devuelto</button>
                                  <?php endif; ?>
                              </td>
                          </tr>
                      <?php endforeach; ?>
                  <?php else: ?>
                      <tr>
                          <td colspan="5" class="text-center">No hay ventas registradas</td>
                      </tr>
                  <?php endif; ?>
              </tbody>
          </table>
      </div>
  </div>
  </main>
</div>

<!-- Modal Venta -->
<div class="modal fade" id="modalVenta" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title">Nueva Venta</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="venta-form" onsubmit="return false;">
          <div class="row align-items-end">
            <div class="col-md-6 mb-3">
              <label class="form-label">Producto</label>
              <select id="producto" class="form-select" required autofocus>
                <option value="">Seleccionar...</option>
                <?php
                $stmt = $pdo->query("SELECT id, descripcion, precio_venta FROM productos ORDER BY descripcion");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $precio_formateado = number_format($row['precio_venta'], 2, ',', '.');
                  // echo "<option value='{$row['id']}' data-desc='{$row['descripcion']}' data-precio='{$row['precio_venta']}'>{$row['descripcion']} - \$" . number_format($row['precio_venta'], 2, ',', '.') . "</option>";
                  // echo "<option value='{$row['id']}' data-desc='{$row['descripcion']}' data-precio='{$row['precio_venta']}'>{$row['descripcion']} - \${$precio_formateado}</option>";  
                  echo "<option value='{$row['id']}' data-desc='{$row['descripcion']}' data-precio='{$row['precio_venta']}' data-stock='{$row['unidades_totales']}'>{$row['descripcion']} - \${$precio_formateado}</option>";
                }
                ?>
              </select>
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Cantidad</label>
              <input type="number" id="cantidad" class="form-control" min="1" value="1">
            </div>
            <div class="col-md-3 mb-3">
              <label class="form-label">Precio Unitario</label>
              <input type="text" id="precio_unitario" class="form-control" readonly>
            </div>
          </div>
          <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="agregar-producto">Agregar</button>
        </form>

        <h6>Detalle</h6>
        <table class="table table-dark table-sm table-bordered">
          <thead>
            <tr>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Precio</th>
              <th>Subtotal</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="tabla-detalle"></tbody>
        </table>
        <div class="text-end">
          <strong>Total: $<span id="total">0.00</span></strong>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button class="btn btn-success" id="confirmar-venta">Confirmar Venta</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
<script src="../js/particles_sonic.js"></script>
<script>
let ventaDetalle = [];

function abrirModalVenta() {
  document.getElementById('venta-form').reset();
  document.getElementById('tabla-detalle').innerHTML = '';
  document.getElementById('total').textContent = '0.00';
  ventaDetalle = [];
  const modalEl = document.getElementById('modalVenta');
  const modal = new bootstrap.Modal(modalEl, {
    backdrop: 'static', // impide cerrar con clic fuera
    keyboard: true     // Se puede cerrar con ESC
  });
  modal.show();
  // Evento que se dispara cuando el modal está completamente mostrado
  modalEl.addEventListener('shown.bs.modal', function() {
    document.getElementById('producto').focus();
    
    // Configurar el evento Enter para el campo cantidad
    document.getElementById('cantidad').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('agregar-producto').click();
      }
    });
    
    // También para el select por si acaso
    document.getElementById('producto').addEventListener('keypress', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('cantidad').focus();
      }
    });
  }, {once: true});
}
// Evitar cierre accidental con confirmación
document.getElementById('modalVenta').addEventListener('hide.bs.modal', function (event) {
  if (ventaDetalle.length > 0) {
    event.preventDefault(); // Cancela el cierre automático

    Swal.fire({
      title: '¿Cancelar venta?',
      text: 'Perderás todos los productos cargados si continuás.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, cancelar',
      cancelButtonText: 'No, continuar'
    }).then((result) => {
      if (result.isConfirmed) {
        ventaDetalle = []; // Limpiamos el detalle si confirma cancelar
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalVenta'));
        if (modal) modal.hide(); // Ahora sí cerramos
      }
    });
  }
});

document.getElementById('producto').addEventListener('change', function () {
  const precio = this.options[this.selectedIndex].dataset.precio || '';
  // document.getElementById('precio_unitario').value = precio ? `$${parseFloat(precio).toFixed(2)}` : '';
  document.getElementById('precio_unitario').value = precio ? `$${formatoPrecio(parseFloat(precio))}` : '';
});

document.getElementById('agregar-producto').addEventListener('click', () => {
  const select = document.getElementById('producto');
  const id = select.value;
  const descripcion = select.options[select.selectedIndex]?.dataset.desc;
  const precio = parseFloat(select.options[select.selectedIndex]?.dataset.precio);
  const cantidad = parseInt(document.getElementById('cantidad').value);

  if (!id || !cantidad || cantidad < 1 || isNaN(precio)) return;

  const subtotal = (cantidad * precio).toFixed(2);
  ventaDetalle.push({ id, descripcion, cantidad, precio });

  const fila = document.createElement('tr');
  fila.innerHTML = `
    <td>${descripcion}</td>
    <td>${cantidad}</td>
    <td>$${formatoPrecio(precio)}</td>
     <td>$${formatoPrecio(cantidad * precio)}</td>
    <td><button class="btn btn-sm btn-danger quitar">X</button></td>
  `;
  fila.querySelector('.quitar').addEventListener('click', () => {
    fila.remove();
    ventaDetalle = ventaDetalle.filter(p => !(p.id === id && p.cantidad === cantidad));
    calcularTotal();
  });

  document.getElementById('tabla-detalle').appendChild(fila);
  calcularTotal();
  select.value = '';
  document.getElementById('cantidad').value = 1;
  document.getElementById('precio_unitario').value = '';
  select.focus();
});

function calcularTotal() {
  let total = ventaDetalle.reduce((sum, p) => sum + p.precio * p.cantidad, 0);
  document.getElementById('total').textContent = formatoPrecio(total);
}

document.getElementById('confirmar-venta').addEventListener('click', () => {
  if (ventaDetalle.length === 0) {
    Swal.fire('Error', 'Agregá al menos un producto.', 'warning');
    return;
  }

  fetch('../controllers/guardar_venta.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      productos: ventaDetalle,
      id_venta_original: document.getElementById('modalVenta').dataset.idVentaOriginal || null
     })
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      Swal.fire('Venta registrada', '¡Venta guardada correctamente!', 'success');
      ventaDetalle = []; //limpia para evitar el dialogo de cancelacion
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalVenta'));
      if (modal) modal.hide();
    } else {
      Swal.fire('Error', data.message || 'No se pudo guardar', 'error');
    }
  })
  .catch(() => {
    Swal.fire('Error', 'Fallo en la conexión con el servidor', 'error');
  });
});
function formatoPrecio(num) {
  return new Intl.NumberFormat('es-AR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(num);
}
// funcion para traer la informacion de la venta
function editarVenta(idVenta) {
  fetch(`../controllers/obtener_venta.php?id=${idVenta}`)
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        ventaDetalle = [];
        document.getElementById('venta-form').reset();
        document.getElementById('tabla-detalle').innerHTML = '';
        document.getElementById('total').textContent = '0.00';

        data.productos.forEach(prod => {
          ventaDetalle.push({ id: prod.id, descripcion: prod.descripcion, cantidad: prod.cantidad, precio: parseFloat(prod.precio) });

          const fila = document.createElement('tr');
          fila.innerHTML = `
            <td>${prod.descripcion}</td>
            <td>${prod.cantidad}</td>
            <td>$${formatoPrecio(prod.precio)}</td>
            <td>$${formatoPrecio(prod.precio * prod.cantidad)}</td>
            <td><button class="btn btn-sm btn-danger quitar">X</button></td>
          `;
          fila.querySelector('.quitar').addEventListener('click', () => {
            fila.remove();
            ventaDetalle = ventaDetalle.filter(p => !(p.id === prod.id && p.cantidad === prod.cantidad));
            calcularTotal();
          });

          document.getElementById('tabla-detalle').appendChild(fila);
        });

        calcularTotal();

        const modalEl = document.getElementById('modalVenta');
        const modal = new bootstrap.Modal(modalEl, {
          backdrop: 'static',
          keyboard: true
        });
        modal.show();

        // Guardar el ID original para anular luego
        modalEl.dataset.idVentaOriginal = idVenta;
      } else {
        Swal.fire('Error', 'No se pudo obtener la venta', 'error');
      }
    })
    .catch(() => {
      Swal.fire('Error', 'Error al conectar con el servidor', 'error');
    });
}

// Versión mejorada con loader
function devolverStock(idVenta) {
    if (!Number.isInteger(Number(idVenta))) {
        Swal.fire('Error', 'ID de venta inválido', 'error');
        return;
    }

    Swal.fire({
        title: 'Devolver al Stock',
        text: '¿Estás seguro de devolver los productos de esta venta al stock?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, devolver',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545',
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return fetch('../controllers/devolver_stock.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${idVenta}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                return response.json();
            })
            .catch(error => {
                Swal.showValidationMessage(`Error: ${error}`);
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value.success) {
                Swal.fire('Éxito', result.value.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', result.value.message, 'error');
            }
        }
    });
}
</script>
</body>
</html>
