<?php
session_start();
if (!isset($_SESSION['usuario'])) {
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
  <link rel="manifest" href="../manifest.json" />
  <meta name="theme-color" content="#121212" />
  <title>Dashboard - Sonic Kiosco</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="../css/sidebar.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <meta name="theme-color" content="#121212" />
</head>
<body class="bg-dark text-white">

<!-- Partículas de fondo -->
<div id="particles-js"></div>

<div class="d-flex position-relative">
  <!-- Sidebar -->
  <nav class="sidebar">
    <h4 class="text-center fw-bold text-primary">Sonic Kiosco</h4>
    <hr class="bg-light" />
    <p class="small"><strong>Usuario:</strong> <?= htmlspecialchars($usuario) ?></p>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link text-white active" href="productos.php">📦 Stock</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="ventas.php">🧾 Ventas</a></li>
      <!-- hay que ser administrador para poder ver estos elementos -->
      <?php if ($rol==='admin'): ?>
      <li class="nav-item"><a class="nav-link text-white" href="maestros.php">🏪 Categorias y Marcas</a></li>
      <li class="nav-item"><a class="nav-link text-white" href="#">👥 Usuarios</a></li>
      <?php endif; ?>

      <li class="nav-item mt-3"><a class="nav-link text-danger" href="../controllers/logout.php">🔒 Cerrar sesión</a></li>
    </ul>
  </nav>

  <!-- Contenido -->
  <main class="main-content p-4 flex-fill position-relative">
    <header class="d-flex justify-content-between align-items-center mb-4">
      <h2>Bienvenido, <?= htmlspecialchars($nombre) ?></h2>
      <span class="badge bg-primary text-dark"><?= strtoupper($rol) ?></span>
    </header>

    <div class="login-card p-4 mb-4 text-white">
      <h4>Resumen del Sistema</h4>
      <p>Desde aquí podrás gestionar tu depósito, controlar productos y registrar movimientos.</p>
    </div>

    <div class="login-card p-4 text-white">
      <h5>Próximamente: Módulo de Reportes 📊</h5>
      <p>Visualización de estadísticas, exportación a PDF/Excel y más funcionalidades.</p>
    </div>
  </main>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
<script src="../js/particles_sonic.js"></script>
<script>
  window.addEventListener("load", () => {
    document.body.classList.add("loaded");
  });
  //SERVICE-WORKER
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('/kiosco_sonic/service-worker.js')
        .then(reg => console.log("✅ Service Worker registrado"))
        .catch(err => console.error("❌ Error al registrar Service Worker", err));
    }
</script>
</body>
</html>
