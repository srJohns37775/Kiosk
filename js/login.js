document.getElementById("formLogin").addEventListener("submit", function (e) {
  e.preventDefault();

  const usuario = document.getElementById('usuario').value.trim();
  const clave = document.getElementById('clave').value.trim();

  if (!usuario || !clave) {
    Swal.fire({
      icon: 'warning',
      title: 'Campos vacíos',
      text: 'Por favor, completa todos los campos.',
      confirmButtonColor: '#00a2ff'
    });
    return;
  }

  const datos = new FormData();
  datos.append("usuario", usuario);
  datos.append("clave", clave);

  fetch("controllers/login.php", {
    method: "POST",
    body: datos
  })
  .then(res => res.json())
  .then(res => {
    Swal.fire({
      icon: res.status === "success" ? "success" : "error",
      title: res.status === "success" ? "¡Bienvenido!" : "Error",
      text: res.mensaje,
      confirmButtonColor: '#00a2ff'
    }).then(() => {
      if (res.status === "success") {
        window.location.href = "views/dashboard.php";
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
