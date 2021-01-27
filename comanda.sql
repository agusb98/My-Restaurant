-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-01-2021 a las 05:21:58
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `persona_id`, `created_at`, `updated_at`) VALUES
(6, 9, '2021-01-06 00:23:38', '2021-01-07 02:13:58'),
(7, 7, '2021-01-06 00:24:17', '2021-01-06 00:24:17'),
(8, 8, '2021-01-06 00:24:29', '2021-01-07 04:49:39'),
(12, 21, '2021-01-21 19:45:47', '2021-01-21 19:45:47'),
(14, 23, '2021-01-27 04:02:22', '2021-01-27 04:02:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `puesto_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `persona_id`, `puesto_id`, `estado_id`, `created_at`, `updated_at`) VALUES
(3, 1, 5, 1, '2020-12-15 07:56:37', '2020-12-15 07:56:37'),
(4, 3, 4, 1, '2021-01-06 00:20:14', '2021-01-06 00:20:14'),
(7, 2, 3, 1, '2021-01-06 00:19:27', '2021-01-06 00:19:27'),
(21, 5, 1, 1, '2021-01-06 00:20:49', '2021-01-06 00:20:49'),
(22, 6, 4, 1, '2021-01-09 04:01:13', '2021-01-09 06:16:44'),
(33, 4, 2, 1, '2021-01-21 14:35:07', '2021-01-21 14:35:07'),
(34, 10, 5, 1, '2021-01-21 09:51:14', '2021-01-21 09:51:14'),
(37, 18, 1, 1, '2021-01-21 10:19:14', '2021-01-21 10:19:14'),
(38, 11, 3, 1, '2021-01-27 02:29:16', '2021-01-27 02:29:16'),
(41, 23, 2, 1, '2021-01-27 03:25:44', '2021-01-27 03:37:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL,
  `pedido_codigo` varchar(5) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `mesa` int(11) DEFAULT NULL,
  `restaurante` int(11) DEFAULT NULL,
  `mozo` int(11) DEFAULT NULL,
  `cocinero` int(11) DEFAULT NULL,
  `descripcion` varchar(66) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `pedido_codigo`, `cliente_id`, `mesa`, `restaurante`, `mozo`, `cocinero`, `descripcion`, `created_at`, `updated_at`) VALUES
(9, 'BM2Op', 8, 7, 7, 8, 9, 'muy bonito el local y el ambiente, buena vibra', '2021-01-23 22:14:11', '2021-01-23 22:14:11'),
(10, 'tqs7M', 14, 4, 2, 5, 10, 'solo me gusto el morfi, la atencion fue pesima', '2021-01-27 07:06:17', '2021-01-27 07:06:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_empleados`
--

CREATE TABLE `estado_empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estado_empleados`
--

INSERT INTO `estado_empleados` (`id`, `nombre`) VALUES
(0, 'INACTIVO'),
(1, 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_mesas`
--

CREATE TABLE `estado_mesas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estado_mesas`
--

INSERT INTO `estado_mesas` (`id`, `nombre`) VALUES
(1, 'con cliente esperando pedido'),
(2, 'con cliente esperando pedido'),
(3, 'con clientes pagando'),
(4, 'cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pedidos`
--

CREATE TABLE `estado_pedidos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estado_pedidos`
--

INSERT INTO `estado_pedidos` (`id`, `nombre`) VALUES
(1, 'PENDIENTE'),
(2, 'EN PREPARACION'),
(3, 'LISTO PARA SERVIR'),
(4, 'EN MESA'),
(5, 'COBRADO'),
(6, 'CANCELADO'),
(7, 'DEMORADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso_empleados`
--

CREATE TABLE `ingreso_empleados` (
  `empleado_id` int(11) NOT NULL,
  `ingreso` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ingreso_empleados`
--

INSERT INTO `ingreso_empleados` (`empleado_id`, `ingreso`) VALUES
(3, '2021-01-26 19:13:11'),
(3, '2021-01-26 19:14:13'),
(3, '2021-01-26 19:16:22'),
(3, '2021-01-26 20:12:39'),
(3, '2021-01-26 20:13:39'),
(3, '2021-01-26 20:17:10'),
(3, '2021-01-26 20:22:00'),
(3, '2021-01-26 20:22:44'),
(3, '2021-01-26 20:22:56'),
(3, '2021-01-26 20:23:01'),
(3, '2021-01-26 20:24:07'),
(40, '2021-01-26 20:24:07'),
(3, '2021-01-26 20:24:09'),
(3, '2021-01-26 20:24:39'),
(3, '2021-01-26 20:24:48'),
(3, '2021-01-26 20:25:24'),
(3, '2021-01-26 20:25:44'),
(41, '2021-01-26 20:25:44'),
(3, '2021-01-26 20:25:59'),
(3, '2021-01-26 20:26:10'),
(3, '2021-01-26 20:26:29'),
(3, '2021-01-26 20:35:45'),
(3, '2021-01-26 20:36:19'),
(3, '2021-01-26 20:36:37'),
(3, '2021-01-26 20:37:01'),
(3, '2021-01-26 20:37:06'),
(3, '2021-01-26 20:37:12'),
(3, '2021-01-26 20:38:26'),
(3, '2021-01-26 20:38:55'),
(3, '2021-01-26 20:39:17'),
(3, '2021-01-26 20:39:36'),
(3, '2021-01-26 20:39:49'),
(3, '2021-01-26 20:40:13'),
(3, '2021-01-26 20:43:25'),
(3, '2021-01-26 20:44:41'),
(3, '2021-01-26 20:45:20'),
(3, '2021-01-26 20:58:25'),
(3, '2021-01-26 20:59:50'),
(3, '2021-01-26 21:00:51'),
(3, '2021-01-26 21:02:09'),
(3, '2021-01-26 21:02:22'),
(3, '2021-01-26 21:02:24'),
(3, '2021-01-26 21:03:49'),
(3, '2021-01-26 21:04:05'),
(3, '2021-01-26 21:04:37'),
(3, '2021-01-26 21:04:46'),
(4, '2021-01-26 21:05:04'),
(4, '2021-01-26 21:05:45'),
(4, '2021-01-26 21:06:20'),
(4, '2021-01-26 21:07:00'),
(4, '2021-01-26 21:07:02'),
(3, '2021-01-26 21:10:37'),
(3, '2021-01-26 21:10:47'),
(4, '2021-01-26 21:10:56'),
(4, '2021-01-26 21:11:09'),
(3, '2021-01-26 21:11:19'),
(3, '2021-01-26 21:11:43'),
(3, '2021-01-26 21:13:36'),
(3, '2021-01-26 21:14:06'),
(3, '2021-01-26 21:14:23'),
(3, '2021-01-26 21:14:54'),
(3, '2021-01-26 21:14:57'),
(3, '2021-01-26 21:15:00'),
(3, '2021-01-26 21:15:11'),
(3, '2021-01-26 21:15:28'),
(3, '2021-01-26 21:15:34'),
(3, '2021-01-26 21:15:51'),
(3, '2021-01-26 21:16:48'),
(3, '2021-01-26 21:17:40'),
(3, '2021-01-26 21:35:00'),
(3, '2021-01-26 21:35:27'),
(3, '2021-01-26 21:35:29'),
(3, '2021-01-26 21:35:47'),
(3, '2021-01-26 21:36:08'),
(3, '2021-01-26 21:36:32'),
(3, '2021-01-26 23:24:21'),
(4, '2021-01-26 23:25:39'),
(3, '2021-01-26 23:25:53'),
(3, '2021-01-26 23:26:06'),
(7, '2021-01-26 23:31:42'),
(33, '2021-01-26 23:31:56'),
(21, '2021-01-26 23:32:00'),
(33, '2021-01-26 23:33:09'),
(33, '2021-01-26 23:33:54'),
(3, '2021-01-26 23:33:59'),
(21, '2021-01-26 23:34:05'),
(7, '2021-01-26 23:34:13'),
(21, '2021-01-26 23:34:26'),
(7, '2021-01-26 23:34:44'),
(7, '2021-01-26 23:35:45'),
(7, '2021-01-26 23:35:58'),
(21, '2021-01-26 23:36:15'),
(3, '2021-01-26 23:36:20'),
(7, '2021-01-26 23:36:33'),
(7, '2021-01-26 23:37:05'),
(7, '2021-01-26 23:37:42'),
(7, '2021-01-26 23:40:09'),
(7, '2021-01-26 23:40:22'),
(7, '2021-01-26 23:40:36'),
(7, '2021-01-26 23:44:48'),
(7, '2021-01-26 23:44:56'),
(7, '2021-01-26 23:45:00'),
(7, '2021-01-26 23:45:01'),
(7, '2021-01-26 23:45:02'),
(7, '2021-01-26 23:45:58'),
(33, '2021-01-26 23:46:04'),
(21, '2021-01-26 23:46:08'),
(3, '2021-01-26 23:46:12'),
(4, '2021-01-26 23:46:29'),
(4, '2021-01-26 23:47:35'),
(4, '2021-01-26 23:48:41'),
(4, '2021-01-26 23:48:59'),
(4, '2021-01-26 23:49:26'),
(4, '2021-01-26 23:49:48'),
(22, '2021-01-26 23:50:07'),
(4, '2021-01-26 23:50:32'),
(4, '2021-01-26 23:52:26'),
(4, '2021-01-26 23:52:45'),
(3, '2021-01-26 23:53:48'),
(3, '2021-01-26 23:54:04'),
(3, '2021-01-26 23:54:20'),
(3, '2021-01-26 23:54:54'),
(3, '2021-01-27 00:57:47'),
(3, '2021-01-27 00:58:05'),
(3, '2021-01-27 00:58:43'),
(3, '2021-01-27 00:58:45'),
(3, '2021-01-27 00:59:10'),
(3, '2021-01-27 00:59:14'),
(4, '2021-01-27 01:00:01'),
(4, '2021-01-27 01:00:30'),
(4, '2021-01-27 01:04:37'),
(4, '2021-01-27 01:04:51'),
(4, '2021-01-27 01:05:37'),
(4, '2021-01-27 01:05:41'),
(4, '2021-01-27 01:06:15'),
(4, '2021-01-27 01:07:36'),
(4, '2021-01-27 01:07:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `empleado_id` int(11) DEFAULT NULL,
  `pedido_codigo` varchar(5) NOT NULL,
  `tiempo` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`, `producto_id`, `empleado_id`, `pedido_codigo`, `tiempo`, `created_at`, `updated_at`) VALUES
(109, 1, 7, 'BM2Op', '00:13:00', '2020-12-23 02:41:44', '2020-12-23 03:16:29'),
(110, 2, 33, 'BM2Op', '00:02:00', '2020-12-23 02:41:44', '2020-12-23 03:17:43'),
(111, 3, 33, 'BM2Op', '00:02:00', '2020-12-23 02:41:44', '2021-01-23 03:18:04'),
(112, 10, 7, 'tqs7M', '00:02:00', '2021-01-27 06:25:39', '2021-01-27 06:34:44'),
(113, 4, 21, 'tqs7M', '00:02:00', '2021-01-27 06:25:39', '2021-01-27 06:34:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `estado_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `codigo`, `estado_id`, `created_at`, `updated_at`) VALUES
(1, 'AAAAA', 4, '2021-01-21 03:29:54', '2021-01-27 06:52:26'),
(2, 'AAAAB', 4, '2021-01-21 03:29:54', '2021-01-21 03:29:54'),
(3, 'AAAAC', 4, '2021-01-21 03:29:54', '2021-01-21 03:29:54'),
(4, 'AAAAD', 4, '2021-01-21 03:29:54', '2021-01-21 03:29:54'),
(5, 'AAAAQ', 4, '2021-01-21 03:29:54', '2021-01-27 03:13:39'),
(10, 'AAAAX', 4, '2021-01-21 20:11:47', '2021-01-22 20:28:02'),
(11, 'AAAAK', 4, '2021-01-27 04:10:47', '2021-01-27 04:10:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `mozo_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL DEFAULT 1,
  `mesa_codigo` varchar(5) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `foto` varchar(100) DEFAULT '',
  `tiempoEstimado` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `codigo`, `mozo_id`, `estado_id`, `mesa_codigo`, `cliente_id`, `foto`, `tiempoEstimado`, `created_at`, `updated_at`) VALUES
(60, 'BM2Op', 4, 5, 'AAAAQ', 8, '234144.jpeg', '00:17:00', '2020-12-23 02:41:44', '2020-12-25 03:13:17'),
(61, 'tqs7M', 4, 4, 'AAAAA', 14, '32539.jpeg', '00:02:00', '2021-01-27 06:25:39', '2021-01-27 08:00:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id`, `nombre`, `apellido`, `created_at`, `updated_at`) VALUES
(1, 'AGUSTIN', 'BAEZ NUñEZ', '2020-12-15 07:43:09', '2020-12-15 07:43:09'),
(2, 'HOMERO', 'HOP', '2020-12-15 07:46:52', '2020-12-15 07:46:52'),
(3, 'PEDRO', 'TIN', '2020-12-15 07:47:08', '2020-12-15 07:47:08'),
(4, 'CRAVERO', 'CAMILA', '2020-12-15 07:47:29', '2020-12-15 07:47:29'),
(5, 'CATENA', 'FEDERICO', '2020-12-15 07:47:42', '2020-12-15 07:47:42'),
(6, 'MOUSE', 'YOEL', '2020-12-15 07:47:59', '2020-12-15 07:47:59'),
(7, 'SDFSDF', 'SDFSDF', '2020-12-16 03:13:51', '2020-12-16 03:13:51'),
(8, 'JUAN CARLOS', 'GASPARINI', '2020-12-16 03:26:55', '2020-12-16 03:26:55'),
(9, 'MARCOS', 'HERNANDEZ', '2020-12-21 22:05:50', '2020-12-21 22:05:50'),
(10, 'MARCELO', 'LOPEZ', '2020-12-22 18:25:49', '2020-12-22 18:25:49'),
(11, 'FAUSTO', 'CASTRO', '2021-01-26 23:48:59', '2021-01-26 23:48:59'),
(12, 'LEO', 'DICRIP', '2021-01-26 23:48:59', '2021-01-26 23:48:59'),
(17, 'PEPE', 'SABIONDO', '2021-01-21 10:04:29', '2021-01-21 10:04:29'),
(18, 'CESAR', 'LOIZAGA', '2021-01-21 10:12:27', '2021-01-21 10:12:27'),
(20, 'JULIAN', 'DURE', '2021-01-21 17:51:31', '2021-01-21 17:51:31'),
(21, 'MAGUI', 'MONTIEL', '2021-01-21 19:31:02', '2021-01-21 19:31:02'),
(22, 'MAGUI', 'SAMPALLO', '2021-01-27 02:09:44', '2021-01-27 02:09:44'),
(23, 'MAGUI', 'POMPEYE', '2021-01-27 03:15:34', '2021-01-27 03:15:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `precio` double NOT NULL,
  `puesto_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `puesto_id`, `created_at`, `updated_at`) VALUES
(1, 'PIZZA A LA PIEDRA', 390, 3, '2020-12-15 23:41:21', '2020-12-15 23:41:21'),
(2, 'IPA', 200, 2, '2020-12-15 23:42:35', '2021-01-09 07:23:05'),
(3, 'SCOTISH', 200, 2, '2020-12-15 23:42:44', '2020-12-15 23:42:44'),
(4, 'AGUA MINERAL', 90, 1, '2020-12-15 23:43:52', '2020-12-15 23:43:52'),
(5, 'FIDEOS CON TUCO', 190, 3, '2020-12-15 23:44:33', '2020-12-15 23:44:33'),
(6, 'COSECHA TARDIA', 350, 1, '2020-12-16 04:05:34', '2020-12-16 04:05:34'),
(7, 'SORRENTINOS', 450, 3, '2021-01-04 20:09:35', '2021-01-04 20:09:35'),
(10, 'HAMBURGUESA DOBLE CHEDDAR', 380, 3, '2021-01-09 07:16:18', '2021-01-09 07:16:18'),
(11, 'ENSALADA TROPICAL', 180, 3, '2021-01-21 19:54:35', '2021-01-21 19:54:35'),
(12, 'ENSALADA MIX', 190, 3, '2021-01-27 04:07:00', '2021-01-27 04:09:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `puestos`
--

CREATE TABLE `puestos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `puestos`
--

INSERT INTO `puestos` (`id`, `nombre`) VALUES
(1, 'BARTENDER'),
(2, 'CERVECERO'),
(3, 'COCINERO'),
(4, 'MOZO'),
(5, 'SOCIO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `email` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `persona_id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 1, 'AGUSSZURDOB@GMAIL.COM', '$2y$10$YBKLX0jCaTo3ZMeQN.qv5up7NSKJzv6eOoK8yDoCHZh8pboVWRQqi', '2020-12-15 07:46:02', '2020-12-15 07:46:02'),
(2, 2, 'LEOPOLDOSABE@GMAIL.COM', '$2y$10$Lr4TG5rGrnAQqq9fjbswxuTMdpxThUNdSX74LDQxwRCD1r4HXl8zW', '2020-12-15 07:46:52', '2020-12-15 07:46:52'),
(3, 3, 'TINCHOLARA@GMAIL.COM', '$2y$10$c67S13R1ojgcAGM7OxVk2uwPh9rX7e313xreguwBMbxZfUe/efkli', '2020-12-15 07:47:08', '2020-12-15 07:47:08'),
(4, 4, 'OBJETOVERDE@GMAIL.COM', '$2y$10$CWwqpb.TNO9usdq.4vnED.ymJkeV4dCXzN6LCsl7oB9FpXheFgI9C', '2020-12-15 07:47:29', '2020-12-15 07:47:29'),
(5, 5, 'FEDEPA@GMAIL.COM', '$2y$10$eYuLfGiTG/OngbwhH54DLulpO2lR0OcSfXn/8iIcQUHBeTSzexZ82', '2020-12-15 07:47:42', '2020-12-15 07:47:42'),
(6, 6, 'YOEL@GMAIL.COM', '$2y$10$JqrGGXeLn5AEl2JfL/DUtOd7mAY.pvZX801YFQKLiwuXSgWR3nE4K', '2020-12-15 07:47:59', '2020-12-15 07:47:59'),
(7, 7, 'holaquetal@gmail.com', '$2y$10$JqrGGXeLn5AEl2JfL/DUtOd7mAY.pvZX801YFQKLiwuXSgWR3nE4K', '2020-12-16 03:13:51', '2020-12-16 03:13:51'),
(8, 8, 'JUANCITOLARGO@GMAIL.COM', '$2y$10$TNfQRNxMKWUccDOigcmJFOhaby7lIPIq725dMpdV5c6mtJkVb2yS2', '2020-12-16 03:26:55', '2020-12-16 03:26:55'),
(9, 9, 'MARCOSLOCO2020@GMAIL.COM', '$2y$10$Ys6Oqbdp9pUjNVDas84VSei2wX0Fgp31VbYfpa5QFEVc7ObOXf0gS', '2020-12-21 22:05:50', '2020-12-21 22:05:50'),
(10, 10, 'ELGATODELACOSTA@YAHOO.COM', '$2y$10$hO2BrU5gnzkFUvu6ntOYmOdzvR/8DsRm9p/3Z31eJQQbOFEqzUadW', '2020-12-22 18:25:49', '2020-12-22 18:25:49'),
(11, 11, 'CATRO1999@YAHOO.COM', '$2y$10$XLLfBkIZ9niVbgjj3quP.Oaz5R/eJZzAqs/hfXHEVF6uLyywxAfVa', '2020-12-23 12:30:27', '2020-12-23 12:30:27'),
(12, 12, 'LEONARDODICAPRIO@COSO.COM', '$2y$10$/aNm3S75yNsIWl6itVXd7uEOqXif8IxGxU9ztISJM9O9vUCiriN/S', '2021-01-09 00:37:04', '2021-01-09 00:37:04'),
(17, 17, 'TITITRITIPI@GMAIL.COM', '$2y$10$fv4h2GHJ5BB7FhA31LExquMl/KyWyMxRXJOJ/sJ9XHYuMQ7b5Hsxi', '2021-01-21 10:04:29', '2021-01-21 10:04:29'),
(18, 18, 'PERALTA@GMAIL.COM', '$2y$10$kq5DqhiyCwVFNxSQd0UBUOqxc0/7lHR1HqthDI7UkAAv/ow1V00S.', '2021-01-21 10:12:27', '2021-01-21 10:12:27'),
(20, 20, 'ELDURO1976@GMAIL.COM', '$2y$10$Ds7s3GKohYtRUpTuqyyoLO6sK4EA9hjFCchDk8T/0ah0VfdeA246e', '2021-01-21 17:51:31', '2021-01-21 17:51:31'),
(21, 21, 'MAGUEEE@GMAIL.COM', '$2y$10$fcNAShcjJ5UGnyYVpwWLGOoQwXUNs2LKWXxR2FNpHnCNZHY8cBOVm', '2021-01-21 19:31:03', '2021-01-21 19:31:03'),
(22, 22, 'LAHORAERATI@GMAIL.COM', '$2y$10$pRd.qU5y0BPFNQCLULwiIOZNjcMSvL.hxUb.zRNCFyUWwiuif.Y3W', '2021-01-27 02:09:44', '2021-01-27 02:09:44'),
(23, 23, 'POMPEYE_MARINO@GMAIL.COM', '$2y$10$v1Xdwnc5pA.RADKOUyasXOzt2Uz3p6ebmYJAJjROXHghta42hrSMu', '2021-01-27 03:15:34', '2021-01-27 03:15:34');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `persona_id` (`persona_id`),
  ADD KEY `puesto_id` (`puesto_id`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_empleados`
--
ALTER TABLE `estado_empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_mesas`
--
ALTER TABLE `estado_mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estado_pedidos`
--
ALTER TABLE `estado_pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ingreso_empleados`
--
ALTER TABLE `ingreso_empleados`
  ADD KEY `empleado_id` (`empleado_id`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `estado_id` (`estado_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `puesto_id` (`puesto_id`);

--
-- Indices de la tabla `puestos`
--
ALTER TABLE `puestos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estado_empleados`
--
ALTER TABLE `estado_empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado_mesas`
--
ALTER TABLE `estado_mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado_pedidos`
--
ALTER TABLE `estado_pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `puestos`
--
ALTER TABLE `puestos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
