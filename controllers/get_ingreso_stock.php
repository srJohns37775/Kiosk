<?php
require_once '../config/db.php';
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
  echo json_encode(['success' => false, 'mensaje' => 'ID inválido']);
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM ingresos_stock WHERE id = ?");
$stmt->execute([$id]);
$ingreso = $stmt->fetch(PDO::FETCH_ASSOC);

if ($ingreso) {
  echo json_encode(['success' => true, 'ingreso' => $ingreso]);
} else {
  echo json_encode(['success' => false, 'mensaje' => 'Ingreso no encontrado']);
}
