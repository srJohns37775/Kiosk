<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $clave = $_POST['clave'];

    if (empty($usuario) || empty($clave)) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Todos los campos son obligatorios.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($clave, $user['contrasena'])) {
        // Crear sesión
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'] ?? 'usuario'; // Si no hay rol, default a 'usuario'

        echo json_encode(['status' => 'success', 'mensaje' => 'Inicio de sesión exitoso.']);
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => 'Usuario o contraseña incorrectos.']);
    }
}
