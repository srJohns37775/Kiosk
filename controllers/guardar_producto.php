<?php
require_once '../config/db.php';
header('Content-Type: application/json');

try {
  $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
  $descripcion = trim($_POST['descripcion'] ?? '');
  $categoria_id = intval($_POST['categoria_id'] ?? 0);
  $marca_id = intval($_POST['marca_id'] ?? 0);
  $usa_pack = isset($_POST['es_pack']) ? 1 : 0;
  $stock_minimo = intval($_POST['stock_minimo'] ?? 0);

  if ($descripcion === '' || $categoria_id === 0 || $marca_id === 0) {
    throw new Exception("Faltan datos obligatorios.");
  }

  if ($id > 0) {
    // UPDATE
    $sql = "UPDATE productos SET
              descripcion = :descripcion,
              categoria_id = :categoria_id,
              marca_id = :marca_id,
              usa_pack = :usa_pack,
              stock_minimo = :stock_minimo
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':descripcion' => $descripcion,
      ':categoria_id' => $categoria_id,
      ':marca_id' => $marca_id,
      ':usa_pack' => $usa_pack,
      ':stock_minimo' => $stock_minimo,
      ':id' => $id
    ]);

    echo json_encode(['success' => true, 'mensaje' => 'Producto actualizado correctamente']);
  } else {
    // INSERT
    $sql = "INSERT INTO productos (
              descripcion, categoria_id, marca_id, usa_pack, stock_minimo
            ) VALUES (
              :descripcion, :categoria_id, :marca_id, :usa_pack, :stock_minimo
            )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':descripcion' => $descripcion,
      ':categoria_id' => $categoria_id,
      ':marca_id' => $marca_id,
      ':usa_pack' => $usa_pack,
      ':stock_minimo' => $stock_minimo
    ]);

    echo json_encode(['success' => true, 'mensaje' => 'Producto guardado correctamente']);
  }
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
