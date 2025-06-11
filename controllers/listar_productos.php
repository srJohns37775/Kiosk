<?php
require_once '../config/db.php';

try {
  $sql = "SELECT 
            p.id,
            p.descripcion,
            p.categoria_id,
            p.marca_id,
            p.usa_pack,
            p.cantidad_packs,
            p.unidades_por_pack,
            p.unidades_totales,
            p.stock_minimo,
            p.precio_costo,
            p.markup,
            p.precio_venta,
            p.fecha_vencimiento,
            c.nombre AS categoria,
            m.nombre AS marca
          FROM productos p
          INNER JOIN categorias c ON p.categoria_id = c.id
          INNER JOIN marcas m ON p.marca_id = m.id
          ORDER BY p.descripcion";

  $stmt = $pdo->query($sql);
  $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($productos);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al obtener productos']);
}
