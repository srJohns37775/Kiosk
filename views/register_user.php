<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="manifest" href="manifest.json" />
  <meta name="theme-color" content="#121212" />
  <title>Registro de Usuario</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
  <link rel="stylesheet" href="../css/styles.css" />
</head>
<body class="bg-dark text-white">

  <div id="particles-js"></div>

  <div class="d-flex justify-content-center align-items-center vh-100 position-relative">
    <form class="card login-card p-4 text-white" id="formRegistro" method="POST">
      <h2 class="text-center text-primary mb-4">Registro</h2>
      
      <input type="text" name="nombre" class="form-control input-field mb-2 capitalizar" placeholder="Nombre" required />
      <input type="text" name="apellido" class="form-control input-field mb-2 capitalizar" placeholder="Apellido" required />
      <input type="text" name="contacto" class="form-control input-field mb-2" placeholder="Contacto (sólo números)" required pattern="\d+" />
      <input type="text" name="usuario" class="form-control input-field mb-2 capitalizar" placeholder="Usuario" required />
      <input type="password" name="contrasena" class="form-control input-field mb-2" placeholder="Contraseña" required />
      <input type="password" name="confirmar" class="form-control input-field mb-3" placeholder="Repetir Contraseña" required />

      <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
  <script src="../js/form_utils.js"></script>
  <script src="../js/particles_sonic.js"></script>
  <script>
    document.getElementById("formRegistro").addEventListener("submit", function(e) {
      e.preventDefault(); // Previene recarga

      const form = e.target;
      const data = new FormData(form);

      fetch("../controllers/registrar_usuario.php", {
        method: "POST",
        body: data
      })
      .then(res => res.json())
      .then(res => {
        Swal.fire({
          icon: res.status === "success" ? "success" : "error",
          title: res.status === "success" ? "¡Registro exitoso!" : "Error",
          text: res.mensaje,
          confirmButtonColor: '#00a2ff'
        }).then(() => {
          if (res.status === "success") {
            form.reset(); // Limpia los campos
            window.location.href = "../index.html"; // O redirigí a donde quieras
          }
        });
      })
      .catch(() => {
        Swal.fire({
          icon: "error",
          title: "Error de conexión",
          text: "No se pudo conectar con el servidor.",
          confirmButtonColor: '#00a2ff'
        });
      });
    });
    //SERVICE-WORKER
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.register('service-worker.js')
        .then(reg => console.log("✅ Service Worker registrado"))
        .catch(err => console.error("❌ Error al registrar Service Worker", err));
    }
  </script>
</body>
</html>
