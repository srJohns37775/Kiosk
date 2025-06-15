<?php
require_once '../config/db.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id) {
  echo json_encode(['success' => false, 'mensaje' => 'ID no proporcionado']);
  exit;
}

try {
  $stmt = $pdo->prepare("
    SELECT id, numero_boleta, cantidad_total, precio_costo, 
           fecha_vencimiento, proveedor, fecha_ingreso
    FROM ingresos_stock
    WHERE producto_id = ?
    ORDER BY fecha_ingreso ASC
  ");
  $stmt->execute([$id]);
  $ingresos = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode(['success' => true, 'ingresos' => $ingresos]);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'mensaje' => 'Error al consultar', 'error' => $e->getMessage()]);
}
