<?php
require_once '../config/db.php';
header('Content-Type: application/json');

try {
  $stmt = $pdo->query("SELECT id, descripcion FROM productos ORDER BY descripcion");
  $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($productos);
} catch (Exception $e) {
  echo json_encode([]);
}