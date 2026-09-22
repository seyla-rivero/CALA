-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-09-2026 a las 02:03:01
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
-- Base de datos: `cala`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`idCategoria`, `nombre`) VALUES
(1, 'Hamburguesas'),
(2, 'Lomos'),
(3, 'Milanesas'),
(4, 'Pizzas'),
(5, 'Empanadas'),
(6, 'Panchos'),
(7, 'Especiales'),
(8, 'Papas'),
(9, 'Bebidas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `telefono` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idCliente`, `nombre`, `apellido`, `email`, `contraseña`, `telefono`) VALUES
(14, 'Seyla', 'Rivero', 'seylagiselrivero@gmail.com', '$2y$10$jQFLdlA5mEU0bNcs9vb0ue8o3En6Cy/atZAXjYq5e099MBReF/jR6', '4444444444'),
(18, 'Jael', 'Rivero', 'jaelmairarivero@gmail.com', '$2y$10$8yCf34PeKYetxV2JUeVn7O9NvTpwBBVA.ZVvSKgqw18rPwHbVoov.', '5555555555');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `idDetallePedido` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idItem` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precioUnitario` decimal(10,2) NOT NULL,
  `subTotal` decimal(10,2) NOT NULL,
  `comentario` text DEFAULT NULL,
  `bebida` varchar(100) DEFAULT NULL,
  `empanadas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`idDetallePedido`, `idPedido`, `idItem`, `cantidad`, `precioUnitario`, `subTotal`, `comentario`, `bebida`, `empanadas`) VALUES
(27, 6, 8, 1, 11000.00, 11000.00, '', NULL, NULL),
(28, 6, 39, 1, 3000.00, 3000.00, '', NULL, NULL),
(29, 6, 42, 1, 8000.00, 8000.00, '', NULL, NULL),
(32, 7, 36, 1, 17000.00, 17000.00, '', NULL, 'Criollas'),
(33, 7, 33, 1, 45000.00, 45000.00, '', 'Talca cola 3 litros', NULL),
(34, 8, 9, 1, 9000.00, 9000.00, '', NULL, NULL),
(35, 9, 4, 2, 10000.00, 20000.00, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario_sucursal`
--

CREATE TABLE `horario_sucursal` (
  `idHorario` int(11) NOT NULL,
  `diaSemana` varchar(15) NOT NULL,
  `horaApertura` time NOT NULL,
  `horaCierre` time NOT NULL,
  `idSucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `horario_sucursal`
--

INSERT INTO `horario_sucursal` (`idHorario`, `diaSemana`, `horaApertura`, `horaCierre`, `idSucursal`) VALUES
(1, 'Miercoles', '20:00:00', '23:59:00', 1),
(2, 'Jueves', '20:00:00', '23:59:00', 1),
(3, 'Viernes', '20:00:00', '23:59:00', 1),
(4, 'Sabado', '20:00:00', '23:59:00', 1),
(5, 'Domingo', '20:00:00', '23:59:00', 1),
(6, 'Miercoles', '21:00:00', '23:59:00', 2),
(7, 'Jueves', '21:00:00', '23:59:00', 2),
(8, 'Viernes', '21:00:00', '23:59:00', 2),
(9, 'Sabado', '21:00:00', '23:59:00', 2),
(10, 'Domingo', '21:00:00', '23:59:00', 2),
(11, 'Viernes', '12:30:00', '14:00:00', 2),
(12, 'Sabado', '12:30:00', '14:00:00', 2),
(13, 'Domingo', '12:30:00', '14:00:00', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_pedido`
--

CREATE TABLE `item_pedido` (
  `idItem` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `urlImagen` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `idCategoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `item_pedido`
--

INSERT INTO `item_pedido` (`idItem`, `nombre`, `descripcion`, `urlImagen`, `precio`, `activo`, `idCategoria`) VALUES
(2, 'Media burguerpizza con papas', 'Pizza, lechuga, salsa de tomate, medallón de hamburguesa, queso, jamón, huevo, mayo y golf, terminado en pizza ( 4 porciones )', 'burguerpizza.png', 25000.00, 1, 7),
(3, 'Burgerpizza ', 'Pizza, lechuga, salsa de tomate, medallón hamburguesa, queso, jamón, huevo, mayo y golf, terminado en pizza ( 8 porciones )', 'burguerpizza2.png', 33000.00, 1, 7),
(4, 'Hamburguesa Especial con papas', 'Pan de papa, lechuga, salsa de tomate, doble carne, queso, jamón, huevo, mayo y golf', 'hamburguesaEspecial.png', 10000.00, 1, 1),
(5, 'Super con papas (comen 2)', 'Pan, lechuga, salsa de tomate, medallón de carne, queso, jamón, huevo, mayo y golf', 'super.png', 14000.00, 1, 1),
(6, 'Super Cala (comen 4)', 'Pan, lechuga, salsa de tomate, medallón de carne, queso, jamón, huevo, mayo y golf', 'supercala.png', 22000.00, 1, 1),
(7, 'Pizza Especial', 'Pizza muzzarella, jamón, huevo, aceitunas, orégano', 'especial.png', 11000.00, 1, 4),
(8, 'Fugazzetta', 'Pizza muzzarella, cebolla, aceituna, queso rallado, orégano', 'fugazzetta.png', 10000.00, 1, 4),
(9, 'Muzzarella', '', 'muzzarella.png', 9000.00, 1, 4),
(10, 'Pizza Cala', 'Pizza muzzarella, carne en trozos, aceitunas, queso rallado', 'pizzacala.png', 17000.00, 1, 4),
(11, 'Pizza Roquefort', 'Pizza muzzarella, roquefort, aceituna, queso rallado', 'pizzaroque.png', 13000.00, 1, 4),
(13, 'Promo 8', 'Super con papas ( comen 2 )', 'burguersuper.png', 14000.00, 1, NULL),
(14, 'Promo 4', 'Muzzarella + Pizza Especial + Docena de empanadas', 'muza.png', 27000.00, 1, NULL),
(15, 'Milanesa a Caballo chica', 'Milanesa de carne, 2 huevo frito, con papas', 'milanesachica.png', 12000.00, 1, 3),
(16, 'Milanesa a Caballo grande', 'Milanesa de carne, 3 huevos fritos, con papas', 'milanesagrande.png', 20000.00, 1, 3),
(17, 'Milanesa Napolitana chica', 'Milanesa de carne, salsa de tomate, queso, orégano, con papas', 'milanapochica.png', 12000.00, 1, 3),
(18, 'Milanesa Napolitana grande', 'Milanesa de carne, salsa de tomate, queso, orégano, con papas', 'milanapogrande.png', 20000.00, 1, 3),
(19, 'Lomo Cala chico (20 cm)', 'Pan francés, lechuga, salsa de tomate, lomo, queso roquefort, queso, jamón, huevo, mayo y golf', 'lomocalachico.png', 25000.00, 1, 2),
(20, 'Lomo Cala grande (30 cm)', 'Pan francés, lechuga, salsa de tomate, lomo, queso roquefort, queso, jamón, huevo, mayo y golf', 'lomocalagrande.png', 28000.00, 1, 2),
(21, 'Lomo Criollo grande ', 'Pan francés, lechuga, salsa de tomate, lomo, cebolla, queso, jamón, huevo, mayo y golf', 'lomocriollogrande.png', 28000.00, 1, 2),
(22, 'Lomo Criollo chico ', 'Pan francés, lechuga, salsa de tomate, lomo, cebolla, queso, jamón, huevo, mayo y golf', 'lomocriollochico.png', 25000.00, 1, 2),
(23, 'Lomo Especial chico (20 cm)', 'Pan francés, lechuga, salsa de tomate, lomo, queso, jamón, huevo, mayo y golf', 'lomoespecialchico.png', 23000.00, 1, 2),
(24, 'Lomo Especial grande (30 cm)', 'Pan francés, lechuga, salsa de tomate, lomo, queso, jamón, huevo, mayo y golf', 'lomoespecialgrande.png', 27000.00, 1, 2),
(25, '1/2 Docena criolla', '', 'mediaempacarne.png', 5000.00, 1, 5),
(26, '1/2 Docena de jamon y queso', '', 'mediaempajamonqueso.png', 5500.00, 1, 5),
(27, 'Docena Criollas', '', 'empacarne.png', 10000.00, 1, 5),
(28, 'Docena de Jamón y Queso', '', 'empajamonqueso.png', 11000.00, 1, 5),
(29, 'Pancho con poncho', 'Pan de pancho, salchicha, jamón, queso, condimentos', 'panchoponcho.png', 4500.00, 1, 6),
(30, 'Pancho simple', 'Pan, salchicha, condimentos', 'panchosimple.png', 3500.00, 1, 6),
(31, 'Promo 9', 'Super Cala ( comen 4 )', 'hambursuper.png', 22000.00, 1, NULL),
(32, 'Promo 1', 'Lomo Especial grande ( 30cm ), Papas simples chico + bebida', 'lomoespecial.png', 32000.00, 1, NULL),
(33, 'Promo 2', 'Lomopizza ( 8 porciones ) + bebida', 'lomopizza.png', 45000.00, 1, NULL),
(36, 'Promo 3', 'Muzzarella + Docena de empanadas', 'muzzaempa.png', 17000.00, 1, NULL),
(37, 'Talca cola 3 litros', NULL, 'talca.png', 3000.00, 1, 9),
(38, 'Talca lima 3 litros', NULL, 'talca.png', 3000.00, 1, 9),
(39, 'Talca naranja 3 litros', NULL, 'talca.png', 3000.00, 1, 9),
(40, 'Talca pomelo 3 litros', NULL, 'talca.png', 3000.00, 1, 9),
(41, 'La cala', 'Papas grandes', 'lacala.png', 9000.00, 1, 8),
(42, 'La cala', 'Papas chicas', 'lacala.png', 8000.00, 1, 8),
(43, 'Papas cheddar', NULL, 'papascheddar.png', 9000.00, 1, 8),
(44, 'Comunes', 'Papas grandes', 'papascomunes.png', 7000.00, 1, 8),
(45, 'Comunes', 'Papas chicas', 'papascomunes.png', 6000.00, 1, 8),
(46, 'Lomopizza (8 porciones)', 'Pizza, lechuga, salsa de tomate, lomo, queso, jamón, huevo, mayo y golf, terminada en pizza muzzarella', 'lomopizza.png', 45000.00, 1, 7),
(47, 'Lomopizza con papas (4 porciones)', 'Pizza, lechuga, salsa de tomate, lomo, queso, jamón, huevo, mayo y golf, terminada en pizza muzzarella.', 'lomopizzapapas.png', 35000.00, 1, 7),
(48, 'Promo 5', 'Muzzarrell + bebida', 'muzapromo.png', 10000.00, 1, NULL),
(49, 'Promo 7', 'Lomopizza ( 8 porciones ) + Papas simples grande + bebida', 'promo7.png', 52000.00, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `idPedido` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idSucursal` int(11) DEFAULT NULL,
  `idZona` int(11) DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `estado` varchar(30) NOT NULL,
  `tipoEntrega` varchar(30) DEFAULT NULL,
  `direccionEntrega` varchar(255) DEFAULT NULL,
  `metodoPago` varchar(20) DEFAULT NULL,
  `estadoPago` varchar(20) DEFAULT NULL,
  `subTotal` decimal(10,2) NOT NULL,
  `costoEnvio` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`idPedido`, `idCliente`, `idSucursal`, `idZona`, `fecha`, `estado`, `tipoEntrega`, `direccionEntrega`, `metodoPago`, `estadoPago`, `subTotal`, `costoEnvio`, `total`) VALUES
(6, 14, 1, NULL, '2026-09-20 18:47:49', 'pendiente', 'retiro', NULL, 'efectivo', 'no_aplica', 22000.00, 0.00, 22000.00),
(7, 14, 2, NULL, '2026-09-21 10:26:06', 'pendiente', 'retiro', NULL, 'transferencia', 'pendiente', 62000.00, 0.00, 62000.00),
(8, 14, NULL, NULL, '2026-09-21 10:27:56', 'carrito', NULL, NULL, NULL, NULL, 9000.00, 0.00, 9000.00),
(9, 18, 1, 1, '2026-09-21 11:21:59', 'pendiente', 'delivery', 'Francia', 'efectivo', 'no_aplica', 20000.00, 1500.00, 21500.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idItem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idItem`) VALUES
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(37),
(38),
(39),
(40),
(41),
(42),
(43),
(44),
(45),
(46),
(47);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `idItem` int(11) NOT NULL,
  `esPromoDia` tinyint(1) NOT NULL DEFAULT 0,
  `incluyeBebida` tinyint(1) NOT NULL DEFAULT 0,
  `incluyeEmpanadas` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`idItem`, `esPromoDia`, `incluyeBebida`, `incluyeEmpanadas`) VALUES
(13, 0, 0, 0),
(14, 1, 0, 1),
(31, 0, 0, 0),
(32, 0, 1, 0),
(33, 1, 1, 0),
(36, 0, 0, 1),
(48, 0, 1, 0),
(49, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

CREATE TABLE `sucursal` (
  `idSucursal` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `cbu` varchar(30) DEFAULT NULL,
  `titular` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL,
  `horario` varchar(255) DEFAULT NULL,
  `ubicacionMapa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`idSucursal`, `nombre`, `direccion`, `telefono`, `alias`, `cbu`, `titular`, `activo`, `horario`, `ubicacionMapa`) VALUES
(1, 'Sucursal 1', 'Montes de Oca 394 Godoy Cruz', '2615726223', 'calasucursal1', '000003383838383838', 'Iñaki Tobares', 1, 'Miércoles a Domingos 20:00pm-23:59pm', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3349.1989472357445!2d-68.86931472437495!3d-32.919341570509935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x967e0984c3423023%3A0x686009755c123bec!2sMontes%20de%20Oca%20394%2C%20M5504%20Godoy%20Cruz%2C%20Mendoza!5e0!3m2!1ses-419!2sar!4v1790002498871!5m2!1ses-419!2sar'),
(2, 'Sucursal 2', 'Pres.R.Ortiz 1665 Godoy Cruz', '2615687706', 'cala.delivery', '0000003100080790972712', 'Sofia Yanina Muñoz Mulica', 1, 'Miércoles a Domingos \r\n21:00pm-23:59pm\r\nViernes a Domingos \r\n12:30pm-14:00pm', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4659.490622074897!2d-68.86557256871914!3d-32.91060004319097!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x967e099eec405ccb%3A0x8af2665564d85eb0!2sPres.%20Roberto%20M.%20Ortiz%201665%2C%20M5501%20Godoy%20Cruz%2C%20Mendoza!5e0!3m2!1ses-419!2sar!4v1790002733076!5m2!1ses-419!2sar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zona`
--

CREATE TABLE `zona` (
  `idZona` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `costoEnvio` decimal(10,2) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `idSucursal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `zona`
--

INSERT INTO `zona` (`idZona`, `nombre`, `costoEnvio`, `activo`, `idSucursal`) VALUES
(1, 'Zona norte', 1500.00, 1, 1),
(2, 'Zona oeste', 1000.00, 1, 1),
(3, 'Zona sur', 1000.00, 1, 2),
(4, 'Zona este', 2000.00, 1, 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idCliente`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`idDetallePedido`),
  ADD KEY `idPedido` (`idPedido`),
  ADD KEY `idItem` (`idItem`);

--
-- Indices de la tabla `horario_sucursal`
--
ALTER TABLE `horario_sucursal`
  ADD PRIMARY KEY (`idHorario`),
  ADD KEY `fk_horario_sucursal` (`idSucursal`);

--
-- Indices de la tabla `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD PRIMARY KEY (`idItem`),
  ADD KEY `fk_item_categoria` (`idCategoria`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`idPedido`),
  ADD KEY `idCliente` (`idCliente`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idItem`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`idItem`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`idSucursal`);

--
-- Indices de la tabla `zona`
--
ALTER TABLE `zona`
  ADD PRIMARY KEY (`idZona`),
  ADD KEY `fk_zona` (`idSucursal`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `idDetallePedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `horario_sucursal`
--
ALTER TABLE `horario_sucursal`
  MODIFY `idHorario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `item_pedido`
--
ALTER TABLE `item_pedido`
  MODIFY `idItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `idSucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `zona`
--
ALTER TABLE `zona`
  MODIFY `idZona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`idPedido`) REFERENCES `pedido` (`idPedido`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`idItem`) REFERENCES `item_pedido` (`idItem`);

--
-- Filtros para la tabla `horario_sucursal`
--
ALTER TABLE `horario_sucursal`
  ADD CONSTRAINT `fk_horario_sucursal` FOREIGN KEY (`idSucursal`) REFERENCES `sucursal` (`idSucursal`);

--
-- Filtros para la tabla `item_pedido`
--
ALTER TABLE `item_pedido`
  ADD CONSTRAINT `fk_item_categoria` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`idItem`) REFERENCES `item_pedido` (`idItem`);

--
-- Filtros para la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD CONSTRAINT `promocion_ibfk_1` FOREIGN KEY (`idItem`) REFERENCES `item_pedido` (`idItem`);

--
-- Filtros para la tabla `zona`
--
ALTER TABLE `zona`
  ADD CONSTRAINT `fk_zona` FOREIGN KEY (`idSucursal`) REFERENCES `sucursal` (`idSucursal`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
