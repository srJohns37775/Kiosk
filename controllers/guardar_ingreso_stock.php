<?php
require_once '../config/db.php';
header('Content-Type: application/json');

$producto_id = $_POST['producto_id'] ?? null;
$cantidad = $_POST['cantidad'] ?? null;
$precio_costo = $_POST['precio_costo'] ?? null;
$numero_boleta = $_POST['numero_boleta'] ?? null;
$proveedor = $_POST['proveedor'] ?? null;
$fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;

if (!$producto_id || !$cantidad || !$precio_costo) {
  http_response_code(400);
  echo json_encode(['success' => false, 'mensaje' => 'Faltan datos obligatorios']);
  exit;
}

// Convertir y validar
$cantidad = intval($cantidad);
$precio_costo = floatval($precio_costo);

if ($cantidad <= 0 || $precio_costo <= 0) {
  http_response_code(400);
  echo json_encode(['success' => false, 'mensaje' => 'Cantidad y precio deben ser mayores a cero']);
  exit;
}

try {
  // Verificar que el producto exista
  $check = $pdo->prepare("SELECT id FROM productos WHERE id = ?");
  $check->execute([$producto_id]);
  if (!$check->fetch()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'mensaje' => 'Producto no encontrado']);
    exit;
  }

  // Insertar el ingreso
  $stmt = $pdo->prepare("
    INSERT INTO ingresos_stock (
      producto_id, cantidad_total, cantidad_disponible, precio_costo, 
      numero_boleta, proveedor, fecha_vencimiento
    ) VALUES (?, ?, ?, ?, ?, ?, ?)
  ");

  $stmt->execute([
    $producto_id,
    $cantidad,
    $cantidad,
    $precio_costo,
    $numero_boleta,
    $proveedor,
    $fecha_vencimiento ?: null
  ]);

  echo json_encode(['success' => true, 'mensaje' => 'Ingreso de stock registrado correctamente']);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'mensaje' => 'Error en la base de datos',
    'error' => $e->getMessage()
  ]);
}
