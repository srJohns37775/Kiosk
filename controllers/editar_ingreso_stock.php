<?php
require_once '../config/db.php';
header('Content-Type: application/json');

try {
  $id = $_POST['id_ingreso'] ?? null;
  $producto_id = $_POST['producto_id'] ?? null;
  $numero_boleta = $_POST['numero_boleta'] ?? null;
  $proveedor = $_POST['proveedor'] ?? null;
  $cantidad_total = $_POST['cantidad_total'] ?? null;
  $precio_costo = $_POST['precio_costo'] ?? null;
  $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? null;

  if (!$id || !$producto_id || !$cantidad_total || !$precio_costo) {
    throw new Exception('Faltan datos obligatorios.');
  }

  $stmt = $pdo->prepare("
    UPDATE ingresos_stock SET 
      producto_id = :producto_id,
      numero_boleta = :numero_boleta,
      proveedor = :proveedor,
      cantidad_total = :cantidad_total,
      cantidad_disponible = :cantidad_total, -- reset cantidad disponible al editar
      precio_costo = :precio_costo,
      fecha_vencimiento = :fecha_vencimiento
    WHERE id = :id
  ");

  $stmt->execute([
    ':producto_id' => $producto_id,
    ':numero_boleta' => $numero_boleta,
    ':proveedor' => $proveedor,
    ':cantidad_total' => $cantidad_total,
    ':precio_costo' => $precio_costo,
    ':fecha_vencimiento' => $fecha_vencimiento ?: null,
    ':id' => $id
  ]);

  echo json_encode(['success' => true, 'mensaje' => 'Ingreso actualizado correctamente']);
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
