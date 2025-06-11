<?php
require_once '../config/db.php';

$tipo = $_POST['tipo'] ?? '';
$valor = trim($_POST['valor'] ?? '');
$id = $_POST['id'] ?? null;

if (!in_array($tipo, ['categoria', 'marca']) || empty($valor)) {
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
    exit;
}

$tabla = $tipo === 'categoria' ? 'categorias' : 'marcas';

try {
    if ($id) {
        // Actualización
        $stmt = $pdo->prepare("UPDATE $tabla SET nombre = :nombre WHERE id = :id");
        $stmt->bindParam(':nombre', $valor);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $mensaje = ucfirst($tipo) . ' actualizada correctamente';
    } else {
        // Inserción
        $stmt = $pdo->prepare("INSERT INTO $tabla (nombre) VALUES (:nombre)");
        $stmt->bindParam(':nombre', $valor);
        $stmt->execute();
        $mensaje = ucfirst($tipo) . ' guardada correctamente';
    }

    echo json_encode(['status' => 'success', 'message' => $mensaje]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al guardar']);
}
