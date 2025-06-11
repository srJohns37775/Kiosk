<?php
require_once '../config/db.php';

try {
  $stmt = $pdo->query("SELECT id, nombre FROM marcas ORDER BY nombre");
  $marcas = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($marcas);
} catch (Exception $e) {
  echo json_encode([]);
}
