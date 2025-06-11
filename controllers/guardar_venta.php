<?php
require_once '../config/db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
  echo json_encode(['success' => false, 'message' => 'No autenticado']);
  exit;
}

$usuario = $_SESSION['usuario'];

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
$id_original = $data['id_venta_original'] ?? null;

if (empty($productos)) {
  echo json_encode(['success' => false, 'message' => 'No hay productos para guardar']);
  exit;
}

try {
  $pdo->beginTransaction();

  // Si viene una venta original, anularla y devolver stock
  if ($id_original && is_numeric($id_original)) {
    // Marcar como anulada
    $pdo->prepare("UPDATE ventas SET anulada = 1 WHERE id = ?")->execute([$id_original]);

    // Traer los productos y devolver al stock
    $stmt = $pdo->prepare("SELECT producto_id, cantidad FROM detalle_venta WHERE venta_id = ?");
    $stmt->execute([$id_original]);
    $original_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($original_items as $item) {
      $pdo->prepare("UPDATE productos SET unidades_totales = unidades_totales + ? WHERE id = ?")
          ->execute([$item['cantidad'], $item['producto_id']]);
    }
  }

  // Calcular total de la nueva venta
  $total = 0;
  foreach ($productos as $item) {
    $total += $item['precio'] * $item['cantidad'];
  }

  // Insertar nueva venta
  $stmt = $pdo->prepare("INSERT INTO ventas (usuario_id, total) VALUES (?, ?)");
  $stmt->execute([$usuario_id, $total]);
  $nueva_venta_id = $pdo->lastInsertId();

  // Insertar detalle
  $stmt = $pdo->prepare("
    INSERT INTO detalle_venta 
    (venta_id, producto_id, nombre_producto, precio_unitario, cantidad, subtotal) 
    VALUES (?, ?, ?, ?, ?, ?)
  ");

  foreach ($productos as $item) {
    $subtotal = $item['precio'] * $item['cantidad'];
    $stmt->execute([
      $nueva_venta_id,
      $item['id'],
      $item['descripcion'],
      $item['precio'],
      $item['cantidad'],
      $subtotal
    ]);

    // Descontar del stock
    $pdo->prepare("UPDATE productos SET unidades_totales = unidades_totales - ? WHERE id = ?")
        ->execute([$item['cantidad'], $item['id']]);
  }

  $pdo->commit();
  echo json_encode(['success' => true]);

} catch (Exception $e) {
  $pdo->rollBack();
  echo json_encode(['success' => false, 'message' => 'Error al guardar venta: ' . $e->getMessage()]);
}
