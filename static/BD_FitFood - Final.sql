-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 03-06-2024 a las 19:39:44
-- Versión del servidor: 8.0.36-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.2-1ubuntu2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `BD_FitFood`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `idCategoria` int NOT NULL,
  `nombreCategoria` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`idCategoria`, `nombreCategoria`) VALUES
(1, 'Carnes'),
(2, 'Embutidos'),
(3, 'Pescados'),
(4, 'Mariscos'),
(5, 'Lacteos'),
(6, 'Panes'),
(7, 'Cereales'),
(8, 'Frutos Secos'),
(9, 'Pastas'),
(10, 'Vegetales'),
(11, 'Frutas'),
(12, 'Especias'),
(13, 'Salsas'),
(14, 'Legumbres'),
(15, 'Aceites'),
(16, 'Vinagres'),
(17, 'Agua'),
(18, 'Batidos'),
(19, 'Zumos'),
(20, 'Refrescos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comidas`
--

CREATE TABLE `comidas` (
  `idComida` int NOT NULL,
  `nombreComida` varchar(45) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `hcarbonoTotales` decimal(6,2) DEFAULT NULL,
  `caloriasTotales` decimal(6,2) DEFAULT NULL,
  `grasasTotales` decimal(6,2) DEFAULT NULL,
  `proteinasTotales` decimal(6,2) DEFAULT NULL,
  `idUsuarioFK` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comidasProductos`
--

CREATE TABLE `comidasProductos` (
  `idComProd` int NOT NULL,
  `idComidaFK` int NOT NULL,
  `idProductoFK` int NOT NULL,
  `cantidad` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int NOT NULL,
  `idUsuario` int NOT NULL,
  `title` varchar(60) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `color` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT '#c8c8c8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `idUsuarioFK` int NOT NULL,
  `idProductoFK` int NOT NULL,
  `idFavorito` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProducto` int NOT NULL,
  `nombreProducto` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `cantidadProducto` decimal(6,2) NOT NULL,
  `hcarbonoProducto` decimal(6,2) DEFAULT NULL,
  `caloriasProducto` decimal(6,2) NOT NULL,
  `grasasProducto` decimal(6,2) NOT NULL,
  `proteinasProducto` decimal(6,2) NOT NULL,
  `imgProducto` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `idCategoriaFK` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idProducto`, `nombreProducto`, `cantidadProducto`, `hcarbonoProducto`, `caloriasProducto`, `grasasProducto`, `proteinasProducto`, `imgProducto`, `idCategoriaFK`) VALUES
(1, 'Pechuga de Pollo', '100.00', '1.00', '104.00', '3.57', '28.04', NULL, 1),
(2, 'Solomillo de Cerdo', '100.00', '0.00', '133.00', '4.05', '22.49', NULL, 1),
(3, 'Pechuga de Pavo', '100.00', '0.00', '167.00', '5.98', '26.94', NULL, 1),
(4, 'Solomillo de Ternera', '100.00', '0.00', '110.00', '2.59', '20.20', NULL, 1),
(5, 'Chuletón de Ternera', '100.00', '0.00', '142.00', '7.02', '21.02', NULL, 1),
(6, 'Chuletón de Cerdo', '100.00', '0.00', '149.00', '8.53', '20.13', NULL, 1),
(7, 'Carne Ternera picada', '100.00', '0.00', '136.00', '5.97', '21.86', NULL, 1),
(8, 'Salchicha de Cerdo', '100.00', '2.00', '118.00', '6.05', '22.64', NULL, 1),
(9, 'Salchicha de Pollo', '100.00', '3.00', '111.00', '5.36', '19.48', NULL, 1),
(10, 'Jamón Serrano', '100.00', '0.00', '110.00', '2.59', '20.20', NULL, 2),
(11, 'Jamón York', '100.00', '0.00', '125.00', '4.46', '17.86', NULL, 2),
(12, 'Chorizo', '100.00', '2.00', '346.00', '28.10', '19.30', NULL, 2),
(13, 'Salchichón', '100.00', '0.00', '189.00', '8.04', '17.08', NULL, 2),
(14, 'Lomo Embuchado', '100.00', '0.00', '173.00', '6.08', '27.58', NULL, 2),
(15, 'Fuet', '100.00', '1.00', '206.00', '12.86', '23.71', NULL, 2),
(16, 'Chopped', '100.00', '2.00', '193.00', '10.68', '21.81', NULL, 2),
(17, 'Mortadela', '100.00', '4.00', '311.00', '25.39', '16.37', NULL, 2),
(18, 'Morcilla', '100.00', '15.00', '379.00', '34.50', '14.60', NULL, 2),
(19, 'Foie Gras', '100.00', '0.00', '462.00', '43.84', '11.40', NULL, 2),
(20, 'Atún ', '100.00', '0.00', '144.00', '4.90', '23.33', NULL, 3),
(21, 'Bacalao', '100.00', '0.00', '82.00', '0.67', '17.81', NULL, 3),
(22, 'Boquerón o Anchoa', '100.00', '0.00', '406.00', '18.75', '12.50', NULL, 3),
(23, 'Merluza', '100.00', '0.00', '78.00', '0.88', '17.54', NULL, 3),
(24, 'Pez Espada', '100.00', '0.00', '144.00', '6.65', '19.66', NULL, 3),
(25, 'Rape', '100.00', '0.00', '76.00', '1.52', '14.48', NULL, 3),
(26, 'Sardina', '100.00', '0.00', '208.00', '11.45', '24.62', NULL, 3),
(27, 'Dorada', '100.00', '0.00', '156.00', '4.67', '19.78', NULL, 3),
(28, 'Lubina', '100.00', NULL, '114.00', '3.69', '18.86', NULL, 3),
(29, 'Salmón', '100.00', NULL, '127.00', '4.40', '20.50', NULL, 3),
(30, 'Bogavante', '100.00', NULL, '88.00', '0.85', '18.88', NULL, 4),
(31, 'Cigala', '100.00', NULL, '74.00', '0.65', '18.36', NULL, 4),
(32, 'Gamba', '100.00', NULL, '71.00', '0.00', '17.86', NULL, 4),
(33, 'Cangrejo', '100.00', NULL, '77.00', '0.95', '15.97', NULL, 4),
(34, 'Langosta', '100.00', NULL, '90.00', '0.89', '18.73', NULL, 4),
(35, 'Langostino', '100.00', NULL, '75.00', '0.57', '18.01', NULL, 4),
(36, 'Percebe', '100.00', NULL, '72.00', '0.67', '17.11', NULL, 4),
(37, 'Almeja', '100.00', NULL, '68.00', '0.63', '16.43', NULL, 4),
(38, 'Coquina', '100.00', NULL, '64.00', '0.59', '15.28', NULL, 4),
(39, 'Mejillón', '100.00', NULL, '66.00', '0.68', '15.95', NULL, 4),
(40, 'Ostra', '100.00', NULL, '71.00', '0.71', '16.02', NULL, 4),
(41, 'Vieira', '100.00', NULL, '73.00', '0.70', '15.98', NULL, 4),
(42, 'Calamar', '100.00', NULL, '82.00', '0.82', '18.31', NULL, 4),
(43, 'Pulpo', '100.00', NULL, '84.00', '0.84', '18.74', NULL, 4),
(44, 'Choco o Sepia', '100.00', NULL, '81.00', '0.81', '18.55', NULL, 4),
(45, 'Leche', '100.00', NULL, '62.00', '3.33', '3.33', NULL, 5),
(46, 'Leche Condensada', '100.00', NULL, '8.70', '7.91', '54.40', NULL, 5),
(47, 'Leche Evaporada', '100.00', NULL, '6.30', '5.90', '49.70', NULL, 5),
(48, 'Leche de Almendras', '100.00', NULL, '1.06', '0.42', '6.78', NULL, 5),
(49, 'Leche de Avena', '100.00', NULL, '0.98', '0.57', '6.11', NULL, 5),
(50, 'Leche de Coco', '100.00', NULL, '2.08', '0.21', '2.92', NULL, 5),
(51, 'Leche de Soja', '100.00', NULL, '1.72', '0.34', '1.85', NULL, 5),
(52, 'Queso ', '100.00', NULL, '21.43', '17.86', '3.57', NULL, 5),
(53, 'Queso ricotta', '100.00', NULL, '11.29', '11.29', '6.45', NULL, 5),
(54, 'Yogur', '100.00', NULL, '0.37', '10.30', '3.64', NULL, 5),
(55, 'Mantequilla', '100.00', NULL, '81.11', '0.85', '0.06', NULL, 5),
(56, 'Mantequilla de Cacahuetes', '100.00', NULL, '102.03', '0.67', '0.09', NULL, 5),
(57, 'Nata', '100.00', NULL, '33.94', '2.67', '8.59', NULL, 5),
(58, 'Helado', '100.00', NULL, '11.00', '3.80', '28.20', NULL, 5),
(59, 'Pan Blanco ', '100.00', NULL, '3.49', '9.30', '74.42', NULL, 6),
(60, 'Pan Integral', '100.00', NULL, '6.45', '9.68', '45.16', NULL, 6),
(61, 'Pan de Centeno', '100.00', NULL, '3.30', '8.50', '48.30', NULL, 6),
(62, 'Pan de Masa Madre', '100.00', NULL, '4.48', '6.94', '69.01', NULL, 6),
(63, 'Pan Multicereales', '100.00', NULL, '4.53', '10.67', '47.54', NULL, 6),
(64, 'Baguette', '100.00', NULL, '0.00', '8.93', '53.57', NULL, 6),
(65, 'Chapata', '100.00', NULL, '0.00', '9.13', '61.01', NULL, 6),
(66, 'Pan Brioche', '100.00', NULL, '4.26', '11.32', '38.75', NULL, 6),
(67, 'Bagel', '100.00', NULL, '5.28', '10.69', '42.36', NULL, 6),
(68, 'Mollete', '100.00', NULL, '4.79', '9.97', '41.09', NULL, 6),
(69, 'Arroz', '100.00', NULL, '0.28', '2.67', '27.99', NULL, 7),
(70, 'Maíz', '100.00', NULL, '4.74', '9.42', '74.26', NULL, 7),
(71, 'Centeno', '100.00', NULL, '1.63', '10.34', '75.86', NULL, 7),
(72, 'Trigo', '100.00', NULL, '1.27', '7.49', '42.53', NULL, 7),
(73, 'Avena', '100.00', NULL, '7.03', '17.30', '66.22', NULL, 7),
(74, 'Cebada', '100.00', NULL, '1.16', '9.91', '77.72', NULL, 7),
(75, 'Quinoa', '100.00', NULL, '6.07', '14.12', '64.16', NULL, 7),
(76, 'Espelta', '100.00', NULL, '2.43', '14.57', '70.19', NULL, 7),
(77, 'Mijo', '100.00', NULL, '4.22', '11.02', '72.85', NULL, 7),
(78, 'Avellanas', '100.00', NULL, '60.75', '14.95', '16.70', NULL, 8),
(79, 'Cacahuetes', '100.00', NULL, '49.24', '25.80', '16.13', NULL, 8),
(80, 'Almendras', '100.00', NULL, '50.00', '20.00', '20.00', NULL, 8),
(81, 'Nueces', '100.00', NULL, '64.29', '14.29', '14.29', NULL, 8),
(82, 'Piñones', '100.00', NULL, '68.37', '13.69', '13.08', NULL, 8),
(83, 'Pistachos', '100.00', NULL, '45.32', '20.16', '27.17', NULL, 8),
(84, 'Castañas', '100.00', NULL, '2.20', '3.17', '52.96', NULL, 8),
(85, 'Semillas de Calabaza', '100.00', NULL, '49.05', '30.23', '10.71', NULL, 8),
(86, 'Anacardos', '100.00', NULL, '43.85', '18.22', '30.19', NULL, 8),
(87, 'Nueces de Macadamia', '100.00', NULL, '75.77', '7.91', '13.82', NULL, 8),
(88, 'Semillas de Girasol', '100.00', NULL, '51.46', '20.78', '20.00', NULL, 8),
(89, 'Macarrones', '100.00', NULL, '0.11', '4.53', '26.61', NULL, 9),
(90, 'Gnocchi', '100.00', NULL, '0.08', '4.38', '25.71', NULL, 9),
(91, 'Pasta de Hojas', '100.00', NULL, '0.07', '4.29', '25.02', NULL, 9),
(92, 'Espaguetis', '100.00', NULL, '0.21', '8.86', '30.88', NULL, 9),
(93, 'Tallarines', '100.00', NULL, '0.20', '8.75', '29.45', NULL, 9),
(94, 'Fettuccine', '100.00', NULL, '0.19', '8.73', '28.98', NULL, 9),
(95, 'Tagliatelle', '100.00', NULL, '0.18', '8.52', '28.91', NULL, 9),
(96, 'Ravioli', '100.00', NULL, '0.17', '8.69', '28.86', NULL, 9),
(97, 'Tortellini', '100.00', NULL, '0.18', '8.54', '28.76', NULL, 9),
(98, 'Lechuga', '100.00', NULL, '0.15', '1.36', '2.87', NULL, 10),
(99, 'Tomate', '100.00', NULL, '0.20', '0.88', '3.89', NULL, 10),
(100, 'Cebolla', '100.00', NULL, '0.10', '1.10', '9.34', NULL, 10),
(101, 'Berenjena', '100.00', NULL, '0.18', '0.98', '5.88', NULL, 10),
(102, 'Calabacin', '100.00', NULL, '0.32', '1.21', '3.11', NULL, 10),
(103, 'Cebolleta', '100.00', NULL, '0.73', '3.27', '4.35', NULL, 10),
(104, 'Pimiento Verde', '100.00', NULL, '0.33', '0.96', '6.67', NULL, 10),
(105, 'Pimiento Rojo', '100.00', NULL, '0.30', '0.99', '6.03', NULL, 10),
(106, 'Pepino', '100.00', NULL, '0.11', '0.65', '3.63', NULL, 10),
(107, 'Coliflor', '100.00', NULL, '0.22', '1.61', '3.75', NULL, 10),
(108, 'Espinaca', '100.00', NULL, '0.26', '2.97', '3.75', NULL, 10),
(109, 'Acelga', '100.00', NULL, '0.08', '1.88', '4.13', NULL, 10),
(110, 'Brocoli', '100.00', NULL, '0.41', '2.38', '7.18', NULL, 10),
(111, 'Alcachofa', '100.00', NULL, '0.15', '3.27', '10.51', NULL, 10),
(112, 'Zanahoria', '100.00', NULL, '0.24', '0.93', '9.58', NULL, 10),
(113, 'Calabaza', '100.00', NULL, '0.10', '1.00', '6.50', NULL, 10),
(114, 'Remolacha', '100.00', NULL, '0.17', '1.61', '9.56', NULL, 10),
(115, 'Patata', '100.00', NULL, '0.09', '2.05', '17.49', NULL, 10),
(116, 'Boniato', '100.00', NULL, '0.51', '2.49', '8.82', NULL, 10),
(117, 'Yuca', '100.00', NULL, '0.47', '2.03', '7.67', NULL, 10),
(118, 'Ajo', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 10),
(119, 'Apio', '100.00', NULL, '14.00', '0.17', '2.97', NULL, 10),
(120, 'Puerro', '100.00', NULL, '12.00', '0.15', '2.60', NULL, 10),
(121, 'Esparrago', '100.00', NULL, '16.00', '0.20', '2.87', NULL, 10),
(122, 'Fresa', '100.00', NULL, '32.00', '0.30', '0.67', NULL, 11),
(123, 'Melocotón', '100.00', NULL, '42.00', '0.00', '0.91', NULL, 11),
(124, 'Ciruela', '100.00', NULL, '250.00', '0.00', '0.00', NULL, 11),
(125, 'Frambuesa', '100.00', NULL, '52.00', '0.00', '1.20', NULL, 11),
(126, 'Arándano', '100.00', NULL, '46.00', '0.00', '0.46', NULL, 11),
(127, 'Mandarina', '100.00', NULL, '53.00', '0.00', '0.81', NULL, 11),
(128, 'Naranja', '100.00', NULL, '97.00', '0.00', '1.50', NULL, 11),
(129, 'Mango', '100.00', NULL, '60.00', '0.00', '0.82', NULL, 11),
(130, 'Limón', '100.00', NULL, '70.00', '0.00', '1.12', NULL, 11),
(131, 'Kiwi', '100.00', NULL, '58.00', '0.00', '1.06', NULL, 11),
(132, 'Piña', '100.00', NULL, '50.00', '0.00', '0.54', NULL, 11),
(133, 'Manzana', '100.00', NULL, '62.00', '0.00', '0.19', NULL, 11),
(134, 'Uva Morada', '100.00', NULL, '93.00', '0.00', '5.60', NULL, 11),
(135, 'Uva Blanca', '100.00', NULL, '88.00', '0.00', '5.50', NULL, 11),
(136, 'Coco', '100.00', NULL, '354.00', '0.00', '3.33', NULL, 11),
(137, 'Plátano', '100.00', NULL, '89.00', '0.00', '1.09', NULL, 11),
(138, 'Cereza', '100.00', NULL, '32.00', '0.00', '0.43', NULL, 11),
(139, 'Higo', '100.00', NULL, '74.00', '0.00', '0.75', NULL, 11),
(140, 'Melón', '100.00', NULL, '34.00', '0.00', '0.82', NULL, 11),
(141, 'Pera', '100.00', NULL, '57.00', '0.00', '0.36', NULL, 11),
(142, 'Sandía', '100.00', NULL, '30.00', '0.00', '0.61', NULL, 11),
(143, 'Granada', '100.00', NULL, '83.00', '0.00', '1.67', NULL, 11),
(144, 'Albaricoque', '100.00', NULL, '48.00', '0.00', '1.40', NULL, 11),
(145, 'Lima', '100.00', NULL, '30.00', '0.00', '0.70', NULL, 11),
(146, 'Aguacate', '100.00', NULL, '160.00', '0.00', '2.00', NULL, 11),
(147, 'Mora', '100.00', NULL, '43.00', '0.00', '1.39', NULL, 11),
(148, 'Sal', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(149, 'Pimienta', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(150, 'Cúrcuma', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(151, 'Orégano', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(152, 'Comino', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(153, 'Curry', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(154, 'Pimentón Rojo', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(155, 'Canela', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(156, 'Romero', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(157, 'Laurel', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(158, 'Tomillo', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(159, 'Albahaca', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(160, 'Ázafran', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(161, 'Perejil', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(162, 'Vainilla', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 12),
(163, 'Ketchup', '100.00', NULL, '50.00', '0.00', '1.45', NULL, 13),
(164, 'Mayonesa', '100.00', NULL, '55.00', '0.00', '1.69', NULL, 13),
(165, 'Mostaza', '100.00', NULL, '48.00', '0.00', '1.37', NULL, 13),
(166, 'Alioli', '100.00', NULL, '57.00', '0.00', '1.73', NULL, 13),
(167, 'Salsa Barbacoa', '100.00', NULL, '43.00', '0.00', '1.39', NULL, 13),
(168, 'Salsa de Tomate', '100.00', NULL, '30.00', '0.00', '1.16', NULL, 13),
(169, 'Salsa de Soja', '100.00', NULL, '33.00', '0.00', '0.00', NULL, 13),
(170, 'Salsa Picante o Tabasco', '100.00', NULL, '90.00', '0.00', '0.00', NULL, 13),
(171, 'Salsa Mojo Picón', '100.00', NULL, '182.00', '0.00', '3.03', NULL, 13),
(172, 'Salsa Agridulce', '100.00', NULL, '154.00', '0.00', '0.27', NULL, 13),
(173, 'Alfalfa', '100.00', NULL, '371.00', '9.74', '36.17', NULL, 14),
(174, 'Altramuces', '100.00', NULL, '367.00', '9.73', '33.71', NULL, 14),
(175, 'Frijoles o Habichuelas', '100.00', NULL, '284.00', '6.71', '4005.00', NULL, 14),
(176, 'Garbanzos', '100.00', NULL, '378.00', '6.04', '20.47', NULL, 14),
(177, 'Guisantes', '100.00', NULL, '81.00', '0.40', '5.42', NULL, 14),
(178, 'Habas', '100.00', NULL, '341.00', '1.22', '19.51', NULL, 14),
(179, 'Judías Verdes', '100.00', NULL, '31.00', '0.22', '1.83', NULL, 14),
(180, 'Lentejas', '100.00', NULL, '352.00', '1.06', '24.63', NULL, 14),
(181, 'Aceite de Oliva', '100.00', NULL, '884.00', '100.00', '0.00', NULL, 15),
(182, 'Aceite de Girasol', '100.00', NULL, '884.00', '100.00', '0.00', NULL, 15),
(183, 'Aceite de Maíz', '100.00', NULL, '884.00', '100.00', '0.00', NULL, 15),
(184, 'Aceite de Palma', '100.00', NULL, '884.00', '100.00', '0.00', NULL, 15),
(185, 'Vinagre Blanco', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 16),
(186, 'Vinagre de Vino', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 16),
(187, 'Vinagre de Manzana', '100.00', NULL, '21.00', '0.00', '0.00', NULL, 16),
(188, 'Vinagre de Jerez', '100.00', NULL, '33.00', '0.00', '0.00', NULL, 16),
(189, 'Vinagre de Módena', '100.00', NULL, '40.00', '0.00', '0.00', NULL, 16),
(190, 'Vinagre de Sidra', '100.00', NULL, '35.00', '0.00', '0.00', NULL, 16),
(191, 'Agua Mineral', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 17),
(192, 'Agua con Gas', '100.00', NULL, '0.00', '0.00', '0.00', NULL, 17),
(193, 'Batido de Chocolate', '100.00', NULL, '119.00', '3.39', '4.24', NULL, 18),
(194, 'Batido de Vainilla', '100.00', NULL, '161.00', '6.71', '3.36', NULL, 18),
(195, 'Batido de Fresa', '100.00', NULL, '134.00', '4.59', '3.92', NULL, 18),
(196, 'Zumo de Naranja', '100.00', NULL, '46.00', '0.00', '0.83', NULL, 19),
(197, 'Zumo de Melocotón', '100.00', NULL, '39.00', '0.00', '0.00', NULL, 19),
(198, 'Zumo de Manzana', '100.00', NULL, '49.00', '0.00', '0.00', NULL, 19),
(199, 'Zumo de Piña', '100.00', NULL, '50.00', '0.00', '0.00', NULL, 19),
(200, 'Zumo de Tomate', '100.00', NULL, '17.00', '0.00', '0.83', NULL, 19),
(201, 'Zumo de Uva', '100.00', NULL, '40.00', '0.00', '0.00', NULL, 19),
(202, 'Zumo de Frutos Rojos', '100.00', NULL, '37.00', '0.00', '0.00', NULL, 19),
(203, 'Zumo de Mandarina', '100.00', NULL, '45.00', '0.00', '0.21', NULL, 19),
(204, 'Zumo de Mango', '100.00', NULL, '51.00', '0.00', '0.00', NULL, 19),
(205, 'Zumo de Pera', '100.00', NULL, '48.00', '0.00', '0.00', NULL, 19),
(206, 'Zumo de Mora', '100.00', NULL, '43.00', '0.00', '0.00', NULL, 19),
(207, 'Zumo de Fresa', '100.00', NULL, '45.00', '0.00', '0.00', NULL, 19),
(208, 'Zumo de Frambuesa', '100.00', NULL, '41.00', '0.00', '0.00', NULL, 19),
(209, 'Refresco Cola', '100.00', NULL, '42.00', '0.25', '0.00', NULL, 20),
(210, 'Refresco Naranja', '100.00', NULL, '48.00', '0.00', '0.00', NULL, 20),
(211, 'Refresco Limon', '100.00', NULL, '50.00', '0.00', '0.00', '../media/prods/img_6653043064219_fanta.png', 20),
(212, 'Bedida Isotónica', '100.00', NULL, '26.00', '0.00', '0.00', NULL, 20),
(213, 'Bedia Energétcia', '100.00', NULL, '43.00', '0.00', '0.46', NULL, 20),
(214, 'Bebida Té', '100.00', NULL, '32.00', '0.00', '0.00', '../media/prods/img_6653036eaee8c_214.png', 20),
(216, 'Golosina', '1.00', '1.00', '1.00', '1.00', '1.00', '../media/prods/teta2.png', 1),
(217, 'Chocolate', '100.00', '50.00', '546.00', '31.00', '4.90', 'chocolate.jpg', 1),
(218, 'Gambas', '100.00', '0.00', '85.00', '0.60', '20.30', 'gambas.jpg', 1),
(219, 'Maiz', '100.00', '19.00', '365.00', '4.70', '9.40', 'maiz.jpg', 1),
(220, 'Pescado', '100.00', '0.00', '206.00', '12.00', '22.00', 'pescado.jpg', 1),
(221, 'Filete de pollo', '100.00', '0.00', '165.00', '3.60', '31.00', 'filete_pollo.jpg', 1),
(222, 'Agua', '100.00', '0.00', '0.00', '0.00', '0.00', 'agua.jpg', 1),
(223, 'Sal', '100.00', '0.00', '0.00', '0.00', '0.00', 'sal.jpg', 1),
(224, 'Chocolate', '100.00', '50.00', '546.00', '31.00', '4.90', 'chocolate.jpg', 1),
(225, 'Gambas', '100.00', '0.00', '85.00', '0.60', '20.30', 'gambas.jpg', 1),
(226, 'Maiz', '100.00', '19.00', '365.00', '4.70', '9.40', 'maiz.jpg', 1),
(227, 'Pescado', '100.00', '0.00', '206.00', '12.00', '22.00', 'pescado.jpg', 1),
(228, 'Filete de pollo', '100.00', '0.00', '165.00', '3.60', '31.00', 'filete_pollo.jpg', 1),
(229, 'Agua', '100.00', '0.00', '0.00', '0.00', '0.00', 'agua.jpg', 1),
(230, 'Sal', '100.00', '0.00', '0.00', '0.00', '0.00', 'sal.jpg', 1),
(231, 'Brócoli', '100.00', '7.00', '34.00', '0.40', '2.80', 'brocoli.jpg', 1),
(232, 'Espinacas', '100.00', '3.60', '23.00', '0.40', '2.90', 'espinacas.jpg', 1),
(233, 'Pepino', '100.00', '3.60', '16.00', '0.10', '0.70', 'pepino.jpg', 1),
(234, 'Tomate', '100.00', '3.90', '18.00', '0.20', '0.90', 'tomate.jpg', 1),
(235, 'Pimiento', '100.00', '6.00', '31.00', '0.30', '1.00', 'pimiento.jpg', 1),
(236, 'Manzana', '100.00', '14.00', '52.00', '0.20', '0.30', 'manzana.jpg', 1),
(237, 'Lechuga', '100.00', '2.00', '15.00', '0.20', '1.40', 'lechuga.jpg', 1),
(238, 'Zanahoria', '100.00', '10.00', '41.00', '0.20', '0.90', 'zanahoria.jpg', 1),
(239, 'Brócoli', '100.00', '7.00', '34.00', '0.40', '2.80', 'brocoli.jpg', 1),
(240, 'Espinacas', '100.00', '3.60', '23.00', '0.40', '2.90', 'espinacas.jpg', 1),
(241, 'Pepino', '100.00', '3.60', '16.00', '0.10', '0.70', 'pepino.jpg', 1),
(242, 'Tomate', '100.00', '3.90', '18.00', '0.20', '0.90', 'tomate.jpg', 1),
(243, 'Pimiento', '100.00', '6.00', '31.00', '0.30', '1.00', 'pimiento.jpg', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `idRol` int NOT NULL,
  `nombreRol` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`idRol`, `nombreRol`) VALUES
(1, 'Administrador'),
(2, 'Dietista'),
(3, 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int NOT NULL,
  `nombreUsuario` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `apellidosUsuario` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `correoElectronicoUsuario` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fechaNacimientoUsuario` date NOT NULL,
  `fechaRegistroUsuario` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `contraseña` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `imagenUsuario` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL DEFAULT 'users/default.png',
  `idRolFK` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombreUsuario`, `apellidosUsuario`, `correoElectronicoUsuario`, `fechaNacimientoUsuario`, `fechaRegistroUsuario`, `contraseña`, `imagenUsuario`, `idRolFK`) VALUES
(1, 'Iván', 'Castañeda Álvarez', 'ivanrani2004@gmail.com', '2004-12-15', '2024-05-21 19:16:47', '$2y$10$HK1xCXADCqwiinftL9iAI.fI/D0py7KYKplFZa5wBozt9tjinoSdq', 'users/img_6652f63954908__31dba7d8-a520-42d7-87d3-5c52841eaef5.jpeg', 1),
(2, 'Vicente', 'Llamas Moreno', 'vicentellamas@gmail.com', '1997-08-07', '2024-05-22 07:54:36', '$2y$10$bDYtA3fzuaYonr.rRCEEJOVjWtSpf34EcXzIIOD7UYdbFKWQPFLEu', 'users/default.png', 1),
(3, 'Paco', 'Dona Villar', 'frandonavillar@gmail.com', '2024-05-22', '2024-05-22 17:15:07', '$2y$10$NqRYevrw3KY845Z.wEnpse.bvvvrdWc1p7YtBl63aDfURr6lsiU1C', 'users/img_664e3b62d417f_fotoperfil.jpeg', 1),
(4, 'Pablo', 'Camacho', 'pcamacho@gmail.com', '1993-02-10', '2024-05-23 13:19:22', '$2y$10$DiR1JLS/qWbhyIuilZ7Fc.MJ74fjHSOYq0QlGqmtxej6/8Z5w6c7K', 'users/default.png', 1),
(5, 'Churumbe', 'Canastero', 'chivato@flames.com', '2001-04-17', '2024-05-26 18:23:15', '$2y$10$ZT6v5OBI5YRrhPyH9FEVe.RYk5aTqV6.9C9ow4VSOngXskYwggBqu', 'users/default.png', 1),
(6, 'Juan', 'Pérez', 'juan@example.com', '1980-01-01', '2024-05-28 21:47:05', 'hashed_password', 'users/default.png', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indices de la tabla `comidas`
--
ALTER TABLE `comidas`
  ADD PRIMARY KEY (`idComida`);

--
-- Indices de la tabla `comidasProductos`
--
ALTER TABLE `comidasProductos`
  ADD PRIMARY KEY (`idComProd`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`idFavorito`),
  ADD KEY `idUsuarioFK` (`idUsuarioFK`),
  ADD KEY `favoritos_ibfk_2` (`idProductoFK`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`),
  ADD KEY `idCategoriaFK` (`idCategoriaFK`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`idRol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD KEY `idRolFK` (`idRolFK`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `idCategoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `comidas`
--
ALTER TABLE `comidas`
  MODIFY `idComida` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `comidasProductos`
--
ALTER TABLE `comidasProductos`
  MODIFY `idComProd` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `idFavorito` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `idRol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`idUsuarioFK`) REFERENCES `usuarios` (`idUsuario`),
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`idProductoFK`) REFERENCES `productos` (`idProducto`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`idCategoriaFK`) REFERENCES `categorias` (`idCategoria`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`idRolFK`) REFERENCES `roles` (`idRol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
