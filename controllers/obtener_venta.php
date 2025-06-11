<?php
require_once '../config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit;
}

$venta_id = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("SELECT dv.producto_id AS id, dv.nombre_producto AS descripcion, dv.precio_unitario AS precio, dv.cantidad
                           FROM detalle_venta dv
                           WHERE dv.venta_id = ?");
    $stmt->execute([$venta_id]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'productos' => $productos]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al obtener venta']);
}
