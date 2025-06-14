<?php
require_once '../config/db.php';
header('Content-Type: application/json');

$producto_id = $_POST['producto_id'] ?? null;
$cantidad = $_POST['cantidad'] ?? null; // Este será usado solo si no se calculan packs
$cantidad_packs = $_POST['cantidad_packs'] ?? null;
$unidades_pack = $_POST['unidades_pack'] ?? null;

$precio_costo = $_POST['precio_costo'] ?? null;
$numero_boleta = $_POST['numero_boleta'] ?? null;
$proveedor = $_POST['proveedor'] ?? null;
$fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;

// Validar campos obligatorios
if (!$producto_id || !$precio_costo) {
  http_response_code(400);
  echo json_encode(['success' => false, 'mensaje' => 'Faltan datos obligatorios']);
  exit;
}

// Procesar cantidad total
$producto_id = intval($producto_id);
$precio_costo = floatval($precio_costo);
$cantidad_total = 0;

// Si vienen packs, usar esos valores
if ($cantidad_packs && $unidades_pack) {
  $cantidad_total = intval($cantidad_packs) * intval($unidades_pack);
  $cantidad_packs = intval($cantidad_packs);
  $unidades_pack = intval($unidades_pack);
} elseif ($cantidad) {
  $cantidad_total = intval($cantidad);
  $cantidad_packs = null;
  $unidades_pack = null;
} else {
  http_response_code(400);
  echo json_encode(['success' => false, 'mensaje' => 'Debes ingresar cantidad o packs']);
  exit;
}

if ($cantidad_total <= 0 || $precio_costo <= 0) {
  http_response_code(400);
  echo json_encode(['success' => false, 'mensaje' => 'Cantidad y precio deben ser mayores a cero']);
  exit;
}

try {
  // Verificar existencia del producto
  $check = $pdo->prepare("SELECT id FROM productos WHERE id = ?");
  $check->execute([$producto_id]);
  if (!$check->fetch()) {
    http_response_code(404);
    echo json_encode(['success' => false, 'mensaje' => 'Producto no encontrado']);
    exit;
  }

  // Insertar ingreso
  $stmt = $pdo->prepare("
    INSERT INTO ingresos_stock (
      producto_id, cantidad_total, cantidad_disponible,
      precio_costo, numero_boleta, proveedor, 
      fecha_vencimiento, fecha_ingreso,
      cantidad_packs, unidades_pack
    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)
  ");

  $stmt->execute([
    $producto_id,
    $cantidad_total,
    $cantidad_total,
    $precio_costo,
    $numero_boleta,
    $proveedor,
    $fecha_vencimiento ?: null,
    $cantidad_packs,
    $unidades_pack
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
