<?php
require_once '../config/db.php';

$datos = json_decode(file_get_contents('php://input'), true);

$tipo = $datos['tipo'] ?? '';
$id = $datos['id'] ?? 0;

if (!in_array($tipo, ['categoria', 'marca']) || !is_numeric($id)) {
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
    exit;
}

$tabla = $tipo === 'categoria' ? 'categorias' : 'marcas';

try {
    $stmt = $pdo->prepare("DELETE FROM $tabla WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => ucfirst($tipo) . ' eliminada correctamente']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar']);
}
