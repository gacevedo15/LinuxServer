-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: mysql:3306
-- Tiempo de generación: 20-08-2023 a las 16:41:17
-- Versión del servidor: 8.1.0
-- Versión de PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `m09`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flota`
--

CREATE TABLE `flota` (
  `id` int NOT NULL,
  `matrícula` varchar(7) NOT NULL,
  `modelo` text NOT NULL,
  `ciudad` enum('Madrid','Barcelona','Valencia') NOT NULL,
  `uso` int NOT NULL,
  `idusuario` int NOT NULL,
  `iniciotrayecto` date NOT NULL,
  `observaciones` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `flota`
--

INSERT INTO `flota` (`id`, `matrícula`, `modelo`, `ciudad`, `uso`, `idusuario`, `iniciotrayecto`, `observaciones`) VALUES
(1, 'ABC123', 'Sedán', 'Madrid', 500, 2, '2023-08-01', 'Buen estado general.'),
(2, 'DEF456', 'SUV', 'Barcelona', 300, 3, '2023-08-05', 'Necesita mantenimiento.'),
(3, 'GHI789', 'Compacto', 'Valencia', 200, 1, '2023-08-10', 'Cambio de aceite reciente.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `rol` enum('admin','user') NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `uso` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `facturacion` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `rol`, `username`, `password`, `uso`, `precio`, `facturacion`) VALUES
(1, 'admin', 'admin_user', '7adc785be4a31eff6783871ff63e18f1', 50, 100.00, 5000.00),
(2, 'user', 'john_doe', '186bca7826f8aeb9aa3eb12928329389', 30, 75.00, 2500.00),
(3, 'user', 'jane_doe', '186bca7826f8aeb9aa3eb12928329389', 25, 80.00, 2000.00);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `flota`
--
ALTER TABLE `flota`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `flota`
--
ALTER TABLE `flota`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `flota`
--
ALTER TABLE `flota`
  ADD CONSTRAINT `flota_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
