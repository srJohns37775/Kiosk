<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
  header('Location: ../index.html');
  exit;
}

$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Categorias y Marcas - Sonic Kiosco</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="manifest" href="../manifest.json" />
  <meta name="theme-color" content="#121212" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="../css/sidebar.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-dark text-white">

<div id="particles-js"></div>

<div class="d-flex">
  <!-- Sidebar -->
  <nav class="sidebar">
    <h4 class="text-center fw-bold text-primary">Sonic Kiosco</h4>
    <hr class="bg-light" />
    <p class="small"><strong>Usuario:</strong> <?= htmlspecialchars($usuario) ?></p>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link text-white" href="dashboard.php">🏠 Dashboard</a></li>
      <li class="nav-item"><a class="nav-link text-white active" href="#">🛠️ Categorias y marcas</a></li>
      <li class="nav-item mt-3"><a class="nav-link text-danger" href="../controllers/logout.php">🔒 Cerrar sesión</a></li>
    </ul>
  </nav>

  <!-- Contenido principal -->
  <main class="main-content p-4 flex-fill position-relative">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Categorías y marcas</h2>
      <span class="badge bg-primary text-dark"><?= strtoupper($rol) ?></span>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#categorias">📂 Categorías</a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#marcas">🏷️ Marcas</a></li>
    </ul>

    <!-- Secciones -->
    <div class="tab-content">
      <div class="tab-pane fade show active" id="categorias">
        <div class="glass-card mb-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5>Categorías</h5>
            <button class="btn btn-success btn-sm" onclick="agregarMaestro('categoria')">➕ Agregar</button>
          </div>
          <ul id="lista-categorias" class="list-group list-group-flush"></ul>
        </div>
      </div>

      <div class="tab-pane fade" id="marcas">
        <div class="glass-card mb-4">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h5>Marcas</h5>
            <button class="btn btn-success btn-sm" onclick="agregarMaestro('marca')">➕ Agregar</button>
          </div>
          <ul id="lista-marcas" class="list-group list-group-flush"></ul>
        </div>
      </div>      
    </div>
  </main>
</div>

<!-- Modal -->
<div class="modal fade" id="modalMaestro" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="modalTitle">Agregar</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="formMaestro" class="modal-body">
        <div class="mb-3">
          <input type="hidden" name="id" id="idCampo">
          <label class="form-label" id="labelCampo">Nombre</label>
          <input type="text" name="valor" class="form-control input-field" required>
        </div>
        <input type="hidden" name="tipo" id="tipoCampo">
        <div class="text-end">
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
<script src="../js/particles_sonic.js"></script>
<script>
  function agregarMaestro(tipo) {
    document.getElementById("idCampo").value = "";
    document.getElementById("modalTitle").textContent = "Agregar " + tipo;
    document.getElementById("tipoCampo").value = tipo;
    document.getElementById("formMaestro").reset();
    new bootstrap.Modal(document.getElementById("modalMaestro")).show();
  }

  document.getElementById("formMaestro").addEventListener("submit", function (e) {
    e.preventDefault();
    const form = new FormData(this);
    fetch("../controllers/guardar_maestro_simple.php", {
      method: "POST",
      body: form
    })
    .then(res => res.json())
    .then(data => {
      Swal.fire({
        title: data.status === "success" ? "¡Guardado!" : "Error",
        text: data.message,
        icon: data.status,
        confirmButtonText: "OK",
        allowEnterKey: true,
        didOpen: () => {
          const confirmButton = Swal.getConfirmButton();
          if (confirmButton) confirmButton.focus();
        }
      });
      if (data.status === "success") {
        document.querySelector(".btn-close").click();
        cargarListas();
      }
    });
  });

  function cargarListas() {
    ["categoria", "marca"].forEach(tipo => {
      fetch("../controllers/get_maestro_simple.php?tipo=" + tipo)
        .then(res => res.json())
        .then(data => {
          const lista = document.getElementById("lista-" + tipo + (tipo === "producto" ? "s" : "s"));
          lista.innerHTML = "";
          if (data.length === 0) {
            lista.innerHTML = "<li class='list-group-item bg-dark text-white'>Sin registros.</li>";
          } else {
            data.forEach(item => {
              lista.innerHTML += `<li class='list-group-item bg-dark text-white d-flex justify-content-between'>
                ${item.nombre}
                <div>
                  <span class="me-3 text-warning" style="cursor:pointer;" onclick="editarMaestro('${tipo}', ${item.id}, '${item.nombre}')">✏️</span>
                  <span class="text-danger" style="cursor:pointer;" onclick="eliminarMaestro('${tipo}', ${item.id})">🗑️</span>
                </div>
              </li>`;
            });
          }
        });
    });
  }
  function editarMaestro(tipo, id, nombre) {
    document.getElementById("modalTitle").textContent = "Editar " + tipo;
    document.getElementById("tipoCampo").value = tipo;
    document.getElementById("idCampo").value = id;
    document.querySelector("input[name='valor']").value = nombre;
    new bootstrap.Modal(document.getElementById("modalMaestro")).show();
  }

  function eliminarMaestro(tipo, id) {
    Swal.fire({
      title: "¿Eliminar?",
      text: "Esta acción no se puede deshacer.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#00a2ff",
      cancelButtonColor: "#888",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar"
    }).then(result => {
      if (result.isConfirmed) {
        fetch("../controllers/delete_maestro_simple.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ tipo, id })
        })
        .then(res => res.json())
        .then(data => {
          Swal.fire({
            title: data.status === "success" ? "Eliminado" : "Error",
            text: data.message,
            icon: data.status,
            confirmButtonText: "OK",
            allowEnterKey: true,
            didOpen: () => {
              const confirmButton = Swal.getConfirmButton();
              if (confirmButton) confirmButton.focus();
            }
          });

          if (data.status === "success") cargarListas();
        });
      }
    });
  }

  // Inicialización
  document.addEventListener("DOMContentLoaded", () => {
    cargarListas();
  });
</script>
</body>
</html>
