-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-06-2025 a las 22:34:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sonic_kiosco`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `creado_en`) VALUES
(4, 'Snacks', '2025-06-04 19:45:36'),
(5, 'Golosinas', '2025-06-04 19:45:45'),
(6, 'Gaseosas', '2025-06-04 19:46:10'),
(8, 'Bebidas Alcoholicas', '2025-06-04 20:05:50'),
(10, 'Bijuteri', '2025-06-10 17:47:54'),
(11, 'Jugos', '2025-06-10 17:48:03'),
(12, 'Cigarrillos', '2025-06-10 17:48:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `nombre_producto` varchar(255) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `venta_id`, `producto_id`, `nombre_producto`, `precio_unitario`, `cantidad`, `subtotal`) VALUES
(1, 1, 4, 'Philips Morris x20', 4900.00, 2, 9800.00),
(2, 2, 4, 'Philips Morris x20', 4900.00, 2, 9800.00),
(3, 3, 1, 'Coca cola 2lts', 12264.00, 1, 12264.00),
(4, 4, 1, 'Coca cola 2lts', 12264.00, 1, 12264.00),
(5, 5, 1, 'Coca cola 2lts', 12264.00, 1, 12264.00),
(6, 6, 1, 'Coca cola 2lts', 12264.00, 1, 12264.00),
(7, 7, 1, 'Coca cola 2lts', 12264.00, 1, 12264.00),
(8, 8, 4, 'Philips Morris x20', 4900.00, 10, 49000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `nombre`, `creado_en`) VALUES
(2, 'Pepsi', '2025-06-04 20:12:40'),
(3, 'Pepsi Light', '2025-06-04 20:12:47'),
(4, 'Cocacola', '2025-06-04 20:12:55'),
(5, 'Lays', '2025-06-04 20:13:01'),
(6, 'Krachitos', '2025-06-04 20:13:12'),
(7, 'Lucky Strike 12', '2025-06-10 17:48:20'),
(8, 'Marlboro x20', '2025-06-10 17:48:28'),
(9, 'Philip Morris x20', '2025-06-10 17:48:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `marca_id` int(11) NOT NULL,
  `usa_pack` tinyint(1) NOT NULL DEFAULT 0,
  `cantidad_packs` int(11) DEFAULT NULL,
  `unidades_por_pack` int(11) DEFAULT NULL,
  `unidades_totales` int(11) NOT NULL,
  `stock_minimo` int(11) NOT NULL,
  `precio_costo` decimal(10,2) NOT NULL,
  `markup` decimal(5,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `fecha_registro` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `descripcion`, `categoria_id`, `marca_id`, `usa_pack`, `cantidad_packs`, `unidades_por_pack`, `unidades_totales`, `stock_minimo`, `precio_costo`, `markup`, `precio_venta`, `fecha_vencimiento`, `fecha_registro`) VALUES
(1, 'Coca cola 2lts', 6, 4, 1, 10, 6, 55, 10, 8760.00, 40.00, 12264.00, NULL, '2025-06-05'),
(2, 'Lays 250g', 4, 5, 0, 5, 30, 150, 20, 8600.00, 40.00, 12040.00, NULL, '2025-06-05'),
(3, 'Krachitos 200g', 4, 6, 1, 30, 15, 450, 50, 5000.00, 40.00, 7000.00, NULL, '2025-06-05'),
(4, 'Philips Morris x20', 12, 9, 1, 5, 10, 36, 20, 3500.00, 40.00, 4900.00, NULL, '2025-06-10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `contacto` varchar(15) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp(),
  `rol` enum('admin','usuario') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `contacto`, `usuario`, `contrasena`, `creado_en`, `rol`) VALUES
(1, 'Jonathan', 'Marini', '2984814001', 'John', '$2y$10$4VttdmMg4Ou9G.pTa/Xz9uM5Hctg6lU/YojahiWi8/6gCNc6sOhci', '2025-05-18 22:12:10', 'admin'),
(2, 'Walter', 'Gonzales', '2984778855', 'Wally', '$2y$10$CGoxNf9qA6LXpudA3en5seKlmxUdrqC9XspuzcOXdDj/Rs.LGG5Em', '2025-05-18 22:20:30', 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `usuario_id`, `total`, `fecha`) VALUES
(1, 1, 9800.00, '2025-06-10 18:01:45'),
(2, 1, 9800.00, '2025-06-10 18:01:48'),
(3, 1, 12264.00, '2025-06-10 18:01:59'),
(4, 1, 12264.00, '2025-06-10 18:02:11'),
(5, 1, 12264.00, '2025-06-10 18:02:18'),
(6, 1, 12264.00, '2025-06-10 18:05:16'),
(7, 1, 12264.00, '2025-06-10 18:08:48'),
(8, 1, 49000.00, '2025-06-10 19:23:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `marca_id` (`marca_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
