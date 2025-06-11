<?php
require_once '../config/db.php';

// Verificamos si los datos llegaron por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $contacto = trim($_POST['contacto']);
    $usuario = trim($_POST['usuario']);
    $contrasena = $_POST['contrasena'];
    $confirmar = $_POST['confirmar'];

    // Validaciones básicas
    if (empty($nombre) || empty($apellido) || empty($contacto) || empty($usuario) || empty($contrasena) || empty($confirmar)) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Todos los campos son obligatorios.']);
        exit;
    }

    if (!preg_match('/^\d+$/', $contacto)) {
        echo json_encode(['status' => 'error', 'mensaje' => 'El contacto debe contener solo números.']);
        exit;
    }

    if ($contrasena !== $confirmar) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Las contraseñas no coinciden.']);
        exit;
    }

    // Validar si el usuario ya existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);

    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'mensaje' => 'El nombre de usuario ya está registrado.']);
        exit;
    }

    // Insertar nuevo usuario con contraseña hasheada
    $hash = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, contacto, usuario, contrasena) VALUES (?, ?, ?, ?, ?)");

    try {
        $stmt->execute([$nombre, $apellido, $contacto, $usuario, $hash]);
        echo json_encode(['status' => 'success', 'mensaje' => 'Usuario registrado correctamente.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Error al registrar usuario.']);
    }
}
