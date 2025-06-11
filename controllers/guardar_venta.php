<?php
require_once '../config/db.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
  echo json_encode(['success' => false, 'message' => 'No autenticado']);
  exit;
}

$usuario = $_SESSION['usuario'];

// Buscar ID del usuario en base al nombre de usuario de sesión
$stmt = $pdo->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt->execute([$usuario]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
  exit;
}

$usuario_id = $user['id'];

$data = json_decode(file_get_contents('php://input'), true);
$productos = $data['productos'] ?? [];

if (empty($productos)) {
  echo json_encode(['success' => false, 'message' => 'No hay productos para guardar']);
  exit;
}

try {
  $pdo->beginTransaction();

  // Calcular total de la venta
  $total = 0;
  foreach ($productos as $item) {
    $total += $item['precio'] * $item['cantidad'];
  }

  // Insertar en tabla ventas
  $stmt = $pdo->prepare("INSERT INTO ventas (usuario_id, total) VALUES (?, ?)");
  $stmt->execute([$usuario_id, $total]);
  $venta_id = $pdo->lastInsertId();

  // Insertar en tabla detalle_venta
  $stmt = $pdo->prepare("
    INSERT INTO detalle_venta 
    (venta_id, producto_id, nombre_producto, precio_unitario, cantidad, subtotal) 
    VALUES (?, ?, ?, ?, ?, ?)
  ");

  foreach ($productos as $item) {
    $subtotal = $item['precio'] * $item['cantidad'];
    $stmt->execute([
      $venta_id,
      $item['id'],
      $item['descripcion'],
      $item['precio'],
      $item['cantidad'],
      $subtotal
    ]);

    // Descontar del stock correcto
    $stockUpdate = $pdo->prepare("UPDATE productos SET unidades_totales = unidades_totales - ? WHERE id = ?");
    $stockUpdate->execute([$item['cantidad'], $item['id']]);
  }

  
  $pdo->commit();
  echo json_encode(['success' => true]);

} catch (Exception $e) {
  $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => 'Error al guardar venta: ' . $e->getMessage()]);
}
