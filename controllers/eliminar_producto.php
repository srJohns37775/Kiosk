<?php
require_once '../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !is_numeric($data['id'])) {
  echo json_encode(['success' => false, 'error' => 'ID inválido']);
  exit;
}

$id = intval($data['id']);

try {
  $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
  $stmt->execute([$id]);

  if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true, 'mensaje' => 'Producto eliminado correctamente']);
  } else {
    echo json_encode(['success' => false, 'error' => 'No se encontró el producto']);
  }
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'error' => 'Error en la base de datos']);
}
