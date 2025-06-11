<?php
require_once '../config/db.php';

header('Content-Type: application/json');

try {
  $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
  $descripcion = trim($_POST['descripcion'] ?? '');
  $categoria_id = intval($_POST['categoria_id'] ?? 0);
  $marca_id = intval($_POST['marca_id'] ?? 0);
  $usa_pack = isset($_POST['es_pack']) ? 1 : 0;
  $cantidad_packs = intval($_POST['cantidad_packs'] ?? 0);
  $unidades_por_pack = intval($_POST['unidades_pack'] ?? 0);
  $unidades_totales = intval($_POST['unidades'] ?? 0);
  $stock_minimo = intval($_POST['stock_minimo'] ?? 0);
  $precio_costo = floatval($_POST['precio_costo'] ?? 0);
  $markup = floatval($_POST['markup'] ?? 0);
  $precio_venta = floatval($_POST['precio_venta'] ?? 0);
  $fecha_vencimiento = $_POST['fecha_vencimiento'] ?: null;

  if ($descripcion === '' || $categoria_id === 0 || $marca_id === 0) {
    throw new Exception("Faltan datos obligatorios.");
  }

  if ($id > 0) {
    // UPDATE existente
    $sql = "UPDATE productos SET
              descripcion = :descripcion,
              categoria_id = :categoria_id,
              marca_id = :marca_id,
              usa_pack = :usa_pack,
              cantidad_packs = :cantidad_packs,
              unidades_por_pack = :unidades_por_pack,
              unidades_totales = :unidades_totales,
              stock_minimo = :stock_minimo,
              precio_costo = :precio_costo,
              markup = :markup,
              precio_venta = :precio_venta,
              fecha_vencimiento = :fecha_vencimiento
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':descripcion' => $descripcion,
      ':categoria_id' => $categoria_id,
      ':marca_id' => $marca_id,
      ':usa_pack' => $usa_pack,
      ':cantidad_packs' => $cantidad_packs,
      ':unidades_por_pack' => $unidades_por_pack,
      ':unidades_totales' => $unidades_totales,
      ':stock_minimo' => $stock_minimo,
      ':precio_costo' => $precio_costo,
      ':markup' => $markup,
      ':precio_venta' => $precio_venta,
      ':fecha_vencimiento' => $fecha_vencimiento ?: null,
      ':id' => $id
    ]);

    echo json_encode(['success' => true, 'mensaje' => 'Producto actualizado correctamente']);
  } else {
    // INSERT nuevo
    $sql = "INSERT INTO productos (
              descripcion, categoria_id, marca_id, usa_pack,
              cantidad_packs, unidades_por_pack, unidades_totales,
              stock_minimo, precio_costo, markup, precio_venta,
              fecha_vencimiento
            ) VALUES (
              :descripcion, :categoria_id, :marca_id, :usa_pack,
              :cantidad_packs, :unidades_por_pack, :unidades_totales,
              :stock_minimo, :precio_costo, :markup, :precio_venta,
              :fecha_vencimiento
            )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':descripcion' => $descripcion,
      ':categoria_id' => $categoria_id,
      ':marca_id' => $marca_id,
      ':usa_pack' => $usa_pack,
      ':cantidad_packs' => $cantidad_packs,
      ':unidades_por_pack' => $unidades_por_pack,
      ':unidades_totales' => $unidades_totales,
      ':stock_minimo' => $stock_minimo,
      ':precio_costo' => $precio_costo,
      ':markup' => $markup,
      ':precio_venta' => $precio_venta,
      ':fecha_vencimiento' => $fecha_vencimiento ?: null,
    ]);

    echo json_encode(['success' => true, 'mensaje' => 'Producto guardado correctamente']);
  }
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
