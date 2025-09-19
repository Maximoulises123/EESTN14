-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-09-2025 a las 18:35:14
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
-- Base de datos: `eestn14`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunicado`
--

CREATE TABLE `comunicado` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Importancia` varchar(50) NOT NULL,
  `Fecha` date NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `numero_comunicado` int(11) DEFAULT NULL,
  `año` year(4) DEFAULT NULL,
  `destacado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `comunicado`
--

INSERT INTO `comunicado` (`Id`, `Nombre`, `Importancia`, `Fecha`, `descripcion`, `imagen`) VALUES
(31, 'THEBIGBANGTEORY', 'urgente', '2025-09-13', 'ABCDEFGHIJKLMÑOPQRSTEWMIYZABCDEFGHIJKLMÑOPQRSTEWMIYZABCDEFGHIJKLMÑOPQRSTEWMIYZABCDEFGHIJKLMÑOPQRSTEWMIYZ', 'assets/img/comunicados/1757743672_Captura de pantalla 2025-09-09 201457.png'),
(32, 'THEBIGBANGTEORY', 'normal', '2025-09-13', 'FWFQWFQWFQWFWEFREDVFBHNJTUKMYTHNJYDGFTBVFRCFWRVGTHBNYJUMKTJNHWREQYGTEHBRUNTIYJHBYGTE4HBRUJ5', 'assets/img/comunicados/1757743700_Captura de pantalla 2025-09-08 023605.png'),
(33, 'THEBIGBANGTEORY', 'informativo', '2025-09-13', 'DAWDAWDAWDAWDAWDQWDQDQDQDQDQDQDWQDQWDQWDQWDQWDQWDQWDQWDQWDQWDWQDQWDQWDQWDQWDQWDQWDQWDQWDQWDQWDQWDQWDQWDQWDQWDQWDQQWDWQDDWDWDQWDWWQDQWDDWQWDQQWDDQW', 'assets/img/comunicados/1757744419_Captura de pantalla 2025-09-07 201134.png'),
(34, 'THEBIGBANGTEORY', 'urgente', '2025-09-13', 'WADAWDAWDAWDASCZCASV SDVWSDAVWFSAFSFASFASFASDADASDASD', ''),
(35, 'brian flores', 'normal', '2025-09-13', 'AWDAWDAWDAWDADAWDAWDAWDWASDASD', 'assets/img/comunicados/1757745399_Captura de pantalla 2025-09-08 033426.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `Id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` enum('capacidades','precapacidades','saberes','destacados','expo-tecnica') NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_creacion` date NOT NULL,
  `destacado` tinyint(1) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preinsicripcion`
--

CREATE TABLE `preinsicripcion` (
  `Id` int(11) NOT NULL,
  `Nombre` varchar(500) NOT NULL,
  `DNI` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente',
  `inscripto` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `Id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(50) NOT NULL,
  `tipo` enum('usuario','admin','director') DEFAULT 'usuario',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`Id`, `nombre`, `email`, `contraseña`, `tipo`, `fecha_registro`) VALUES
(2, 'Director', 'director', '123456', 'director', '2025-09-12 07:53:41');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comunicado`
--
ALTER TABLE `comunicado`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `preinsicripcion`
--
ALTER TABLE `preinsicripcion`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comunicado`
--
ALTER TABLE `comunicado`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `preinsicripcion`
--
ALTER TABLE `preinsicripcion`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
