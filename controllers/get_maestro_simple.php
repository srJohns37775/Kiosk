<?php
require_once '../config/db.php';

$tipo = $_GET['tipo'] ?? '';

if (!in_array($tipo, ['categoria', 'marca'])) {
    echo json_encode([]);
    exit;
}

// Mapeo de tabla según tipo
$tabla = $tipo === 'categoria' ? 'categorias' : 'marcas';

try {
    $stmt = $pdo->query("SELECT id, nombre FROM $tabla ORDER BY nombre ASC");
    $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($datos);
} catch (PDOException $e) {
    echo json_encode([]);
}