<?php
require_once '../config/db.php';
header('Content-Type: application/json');

try {
    // Consulta para obtener productos con stock calculado y precios
    $sql = "SELECT 
                p.id,
                p.descripcion,
                p.categoria_id,
                p.marca_id,
                p.usa_pack,
                p.stock_minimo,
                c.nombre AS categoria,
                m.nombre AS marca,
                COALESCE(SUM(i.cantidad_disponible), 0) AS unidades_totales,
                (
                    SELECT i2.precio_costo 
                    FROM ingresos_stock i2 
                    WHERE i2.producto_id = p.id 
                    ORDER BY i2.fecha_ingreso DESC 
                    LIMIT 1
                ) AS precio_costo,
                (
                    SELECT AVG(i3.precio_costo)
                    FROM ingresos_stock i3
                    WHERE i3.producto_id = p.id
                ) AS precio_costo_promedio,
                p.markup,
                (
                    SELECT i2.precio_costo * (1 + p.markup/100)
                    FROM ingresos_stock i2 
                    WHERE i2.producto_id = p.id 
                    ORDER BY i2.fecha_ingreso DESC 
                    LIMIT 1
                ) AS precio_venta,
                (
                    SELECT i2.fecha_vencimiento
                    FROM ingresos_stock i2 
                    WHERE i2.producto_id = p.id 
                    ORDER BY i2.fecha_ingreso DESC 
                    LIMIT 1
                ) AS fecha_vencimiento
            FROM productos p
            INNER JOIN categorias c ON p.categoria_id = c.id
            INNER JOIN marcas m ON p.marca_id = m.id
            LEFT JOIN ingresos_stock i ON p.id = i.producto_id
            GROUP BY p.id
            ORDER BY p.descripcion";

    $stmt = $pdo->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($productos);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al obtener productos',
        'detalle' => $e->getMessage()
    ]);
}