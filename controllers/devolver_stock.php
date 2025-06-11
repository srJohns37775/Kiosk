<?php
session_start();
require_once '../config/db.php';

// Verificar autenticación y rol de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    die(json_encode(['success' => false, 'message' => 'No autorizado']));
}

// Verificar que sea una petición POST y que tenga el ID
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    die(json_encode(['success' => false, 'message' => 'Petición inválida']));
}

// Validar y obtener el ID
$venta_id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
if (!$venta_id) {
    die(json_encode(['success' => false, 'message' => 'ID inválido']));
}

try {
    $pdo->beginTransaction();
    
    // 1. Obtener los productos de la venta
    $stmt = $pdo->prepare("SELECT producto_id, cantidad FROM ventas_detalle WHERE venta_id = ?");
    $stmt->execute([$venta_id]);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($productos)) {
        throw new Exception("No se encontraron productos para esta venta");
    }
    
    // 2. Devolver cada producto al stock
    foreach ($productos as $producto) {
        $update = $pdo->prepare("UPDATE productos SET stock = stock + ? WHERE id = ?");
        $update->execute([$producto['cantidad'], $producto['producto_id']]);
    }
    
    // 3. Marcar la venta como anulada
    $update_venta = $pdo->prepare("UPDATE ventas SET anulada = 1 WHERE id = ?");
    $update_venta->execute([$venta_id]);
    
    $pdo->commit();
    
    echo json_encode(['success' => true, 'message' => 'Productos devueltos al stock correctamente']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al devolver el stock: ' . $e->getMessage()]);
}