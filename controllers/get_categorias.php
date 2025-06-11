<?php
require_once '../config/db.php';

try {
  $stmt = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre");
  $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($categorias);
} catch (Exception $e) {
  echo json_encode([]);
}