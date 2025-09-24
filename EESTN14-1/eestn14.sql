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
-- Estructura de tabla para la tabla `directivos`
--

CREATE TABLE `directivos` (
  `Id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `directivos`
--

INSERT INTO `directivos` (`Id`, `nombre`, `email`, `contraseña`, `fecha_registro`) VALUES
(1, 'Director', 'director', '123456', '2025-09-12 07:53:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `Id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`Id`, `nombre`, `apellido`, `email`, `contraseña`, `telefono`, `activo`, `fecha_registro`) VALUES
(1, 'Carlos', 'Rodriguez', 'carlos.rodriguez@eest14.edu.ar', '123456', '1123456789', 1, '2025-01-15 10:00:00'),
(2, 'Maria', 'Gonzalez', 'maria.gonzalez@eest14.edu.ar', '123456', '1123456790', 1, '2025-01-15 10:00:00'),
(3, 'Juan', 'Perez', 'juan.perez@eest14.edu.ar', '123456', '1123456791', 1, '2025-01-15 10:00:00'),
(4, 'Ana', 'Martinez', 'ana.martinez@eest14.edu.ar', '123456', '1123456792', 1, '2025-01-15 10:00:00'),
(5, 'Luis', 'Fernandez', 'luis.fernandez@eest14.edu.ar', '123456', '1123456793', 1, '2025-01-15 10:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `Id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contraseña` varchar(255) NOT NULL,
  `año` int(11) NOT NULL,
  `division` varchar(10) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `Id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `año` int(11) NOT NULL,
  `categoria` enum('Formacion General','Formacion Cientifico Tecnologica','Formacion Tecnica Especifica','Practicas Profesionalizantes') NOT NULL,
  `tipo` enum('Aula','Taller') NOT NULL DEFAULT 'Aula',
  `especialidad` enum('Ciclo Basico','Programacion','Informatica','Alimentos') NOT NULL,
  `cht` int(11) DEFAULT NULL,
  `chs` int(11) DEFAULT NULL,
  `activa` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`Id`, `nombre`, `año`, `categoria`, `tipo`, `especialidad`, `cht`, `chs`) VALUES
-- CICLO BASICO
(1, 'Ciencias Naturales', 1, 'Formacion Cientifico Tecnologica', 'Aula', 'Ciclo Basico', 144, 4),
(2, 'Ciencias Sociales', 1, 'Formacion Cientifico Tecnologica', 'Aula', 'Ciclo Basico', 144, 4),
(3, 'Educacion Artistica', 1, 'Formacion General', 'Aula', 'Ciclo Basico', 72, 2),
(4, 'Educacion Fisica', 1, 'Formacion General', 'Aula', 'Ciclo Basico', 72, 2),
(5, 'Ingles', 1, 'Formacion General', 'Aula', 'Ciclo Basico', 72, 2),
(6, 'Matematica', 1, 'Formacion Cientifico Tecnologica', 'Aula', 'Ciclo Basico', 144, 4),
(7, 'Practicas del Lenguaje', 1, 'Formacion General', 'Aula', 'Ciclo Basico', 144, 4),
(8, 'Construccion Ciudadana', 1, 'Formacion General', 'Aula', 'Ciclo Basico', 72, 2),
(9, 'Procedimientos Tecnicos', 1, 'Formacion Tecnica Especifica', 'Taller', 'Ciclo Basico', 72, 2),
(10, 'Lenguajes Tecnologicos', 1, 'Formacion Tecnica Especifica', 'Taller', 'Ciclo Basico', 72, 2),
(11, 'Sistemas Tecnologicos', 1, 'Formacion Tecnica Especifica', 'Taller', 'Ciclo Basico', 72, 2),
(12, 'Biologia', 2, 'Formacion Cientifico Tecnologica', 'Aula', 'Ciclo Basico', 72, 2),
(13, 'Construccion de Ciudadania', 2, 'Formacion General', 'Aula', 'Ciclo Basico', 72, 2),
(14, 'Fisico Quimica', 2, 'Formacion Cientifico Tecnologica', 'Aula', 'Ciclo Basico', 72, 2),
(15, 'Geografia', 2, 'Formacion Cientifico Tecnologica', 'Aula', 'Ciclo Basico', 72, 2),
(16, 'Historia', 2, 'Formacion Cientifico Tecnologica', 'Aula', 'Ciclo Basico', 72, 2),
(17, 'Procedimientos Tecnicos', 2, 'Formacion Tecnica Especifica', 'Taller', 'Ciclo Basico', 144, 4),
(18, 'Biologia', 3, 'Formacion Cientifico Tecnologica', 'Aula', 'Ciclo Basico', 72, 2),
(19, 'Construccion de Ciudadania', 3, 'Formacion General', 'Aula', 'Ciclo Basico', 72, 2),
(20, 'Fisico Quimica', 3, 'Formacion Cientifico Tecnologica', 'Aula', 'Ciclo Basico', 72, 2),
(21, 'Geografia', 3, 'Formacion Cientifico Tecnologica', 'Aula', 'Ciclo Basico', 72, 2),
(22, 'Historia', 3, 'Formacion Cientifico Tecnologica', 'Aula', 'Ciclo Basico', 72, 2),
(23, 'Procedimientos Tecnicos', 3, 'Formacion Tecnica Especifica', 'Taller', 'Ciclo Basico', 72, 2),
(24, 'Lenguajes Tecnologicos', 3, 'Formacion Tecnica Especifica', 'Taller', 'Ciclo Basico', 72, 2),
(25, 'Sistemas Tecnologicos', 3, 'Formacion Tecnica Especifica', 'Taller', 'Ciclo Basico', 144, 4),
-- PROGRAMACION
(26, 'Literatura', 4, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(27, 'Ingles', 4, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(28, 'Educacion Fisica', 4, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(29, 'Salud y Adolescencia', 4, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(30, 'Historia', 4, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(31, 'Geografia', 4, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(32, 'Matematica Ciclo Superior', 4, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 144, 4),
(33, 'Fisica', 4, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(34, 'Quimica', 4, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(35, 'Arquitectura de Hardware', 4, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(36, 'Metodologias de Programacion', 4, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(37, 'Hardware y Componentes', 4, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(38, 'Sistemas Operativos', 4, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(39, 'Suite de Aplicaciones', 4, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(40, 'Literatura', 5, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(41, 'Ingles', 5, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(42, 'Educacion Fisica', 5, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(43, 'Politica y Ciudadania', 5, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(44, 'Historia', 5, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(45, 'Geografia', 5, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(46, 'Analisis Matematico', 5, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 144, 4),
(47, 'Sistemas Digitales I', 5, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(48, 'Bases de Datos', 5, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(49, 'Modelos y Sistemas I', 5, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(50, 'Lenguajes de Programacion I', 5, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(51, 'Redes Informaticas', 5, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(52, 'Diseño WEB', 5, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(53, 'Arquitectura de Base de Datos', 5, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(54, 'Literatura', 6, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(55, 'Ingles', 6, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(56, 'Educacion Fisica', 6, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(57, 'Filosofia', 6, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(58, 'Arte', 6, 'Formacion General', 'Aula', 'Programacion', 72, 2),
(59, 'Matematica Aplicada', 6, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(60, 'Sistemas Digitales II', 6, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(61, 'Sistemas de Gestion y Autogestion', 6, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(62, 'Seguridad Informatica', 6, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(63, 'Derechos del Trabajo', 6, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(64, 'Lenguajes de Programacion II', 6, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(65, 'Programacion y Controles Automatizados', 6, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(66, 'Desarrollo de Aplicaciones Web Estaticas', 6, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(67, 'Desarrollo de Aplicaciones WEB Dinamicas', 6, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(68, 'Practicas Profesionalizantes del Sector Programacion', 7, 'Practicas Profesionalizantes', 'Taller', 'Programacion', 216, 6),
(69, 'Emprendimientos e Innovacion productiva', 7, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(70, 'Evaluacion de Proyectos', 7, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(71, 'Modelos y Sistemas II', 7, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(72, 'Organizacion y Metodos', 7, 'Formacion Cientifico Tecnologica', 'Aula', 'Programacion', 72, 2),
(73, 'Proyecto Integrador', 7, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(74, 'Desarrollo de Software para Plataformas Moviles', 7, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
(75, 'Diseño e Implementacion de Sitios WEB', 7, 'Formacion Tecnica Especifica', 'Taller', 'Programacion', 72, 2),
-- INFORMATICA
(76, 'Literatura', 4, 'Formacion General', 'Informatica', 72, 2),
(77, 'Ingles', 4, 'Formacion General', 'Informatica', 72, 2),
(78, 'Educacion Fisica', 4, 'Formacion General', 'Informatica', 72, 2),
(79, 'Salud y Adolescencia', 4, 'Formacion General', 'Informatica', 72, 2),
(80, 'Historia', 4, 'Formacion General', 'Informatica', 72, 2),
(81, 'Geografia', 4, 'Formacion General', 'Informatica', 72, 2),
(82, 'Matematica Ciclo Superior', 4, 'Formacion Cientifico Tecnologica', 'Informatica', 144, 4),
(83, 'Fisica', 4, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(84, 'Quimica', 4, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(85, 'Tecnologias Electronicas', 4, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(86, 'Lenguajes de Programacion I', 4, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(87, 'Hardware Equipo Monousuario', 4, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(88, 'Introduccion a los Sistemas Operativos', 4, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(89, 'Suite de Aplicaciones', 4, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(90, 'Literatura', 5, 'Formacion General', 'Informatica', 72, 2),
(91, 'Ingles', 5, 'Formacion General', 'Informatica', 72, 2),
(92, 'Educacion Fisica', 5, 'Formacion General', 'Informatica', 72, 2),
(93, 'Politica y Ciudadania', 5, 'Formacion General', 'Informatica', 72, 2),
(94, 'Historia', 5, 'Formacion General', 'Informatica', 72, 2),
(95, 'Geografia', 5, 'Formacion General', 'Informatica', 72, 2),
(96, 'Analisis Matematico', 5, 'Formacion Cientifico Tecnologica', 'Informatica', 144, 4),
(97, 'Sistemas Digitales', 5, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(98, 'Teleinformatica', 5, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(99, 'Lenguajes de Programacion II', 5, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(100, 'Hardware de Red', 5, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(101, 'Sistemas Operativos Mono y Multiusuario', 5, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(102, 'Arquitectura de Datos', 5, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(103, 'Literatura', 6, 'Formacion General', 'Informatica', 72, 2),
(104, 'Ingles', 6, 'Formacion General', 'Informatica', 72, 2),
(105, 'Educacion Fisica', 6, 'Formacion General', 'Informatica', 72, 2),
(106, 'Filosofia', 6, 'Formacion General', 'Informatica', 72, 2),
(107, 'Arte', 6, 'Formacion General', 'Informatica', 72, 2),
(108, 'Matematica Aplicada', 6, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(109, 'Sistemas Digitales', 6, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(110, 'Investigacion Operativa', 6, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(111, 'Seguridad Informatica', 6, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(112, 'Derechos del Trabajo', 6, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(113, 'Diseño de Programas', 6, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(114, 'Diseño y Hardware de Redes Locales y WAN', 6, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(115, 'Sistemas Operativos Multiplataforma', 6, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(116, 'Diseño de APP', 6, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(117, 'Practicas Profesionalizantes del Sector Informatico', 7, 'Practicas Profesionalizantes', 'Informatica', 216, 6),
(118, 'Emprendimientos e Innovacion productiva', 7, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(119, 'Evaluacion de Proyectos', 7, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(120, 'Modelos y Sistemas', 7, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(121, 'Base de Datos', 7, 'Formacion Cientifico Tecnologica', 'Informatica', 72, 2),
(122, 'Proyecto Integrador', 7, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(123, 'Instalacion, Mantenimiento y Reparacion de Sistemas Computacionales', 7, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
(124, 'Instalacion, Mantenimiento y Reparacion de Redes Informaticas', 7, 'Formacion Tecnica Especifica', 'Informatica', 72, 2),
-- ALIMENTOS
(125, 'Literatura', 4, 'Formacion General', 'Alimentos', 72, 2),
(126, 'Ingles', 4, 'Formacion General', 'Alimentos', 72, 2),
(127, 'Educacion Fisica', 4, 'Formacion General', 'Alimentos', 72, 2),
(128, 'Salud y Adolescencia', 4, 'Formacion General', 'Alimentos', 72, 2),
(129, 'Historia', 4, 'Formacion General', 'Alimentos', 72, 2),
(130, 'Geografia', 4, 'Formacion General', 'Alimentos', 72, 2),
(131, 'Matematica Ciclo Superior', 4, 'Formacion Cientifico Tecnologica', 'Alimentos', 144, 4),
(132, 'Quimica', 4, 'Formacion Cientifico Tecnologica', 'Alimentos', 108, 3),
(133, 'Fisica', 4, 'Formacion Cientifico Tecnologica', 'Alimentos', 72, 2),
(134, 'Operaciones Unitarias y Tecnologia de los Materiales', 4, 'Formacion Cientifico Tecnologica', 'Alimentos', 72, 2),
(135, 'Introduccion a la Biologia Celular', 4, 'Formacion Cientifico Tecnologica', 'Alimentos', 72, 2),
(136, 'Laboratorio de Operaciones Unitarias y Tecnologia de los Materiales', 4, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4),
(137, 'Laboratorio de Ensayos Fisicos', 4, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4),
(138, 'Laboratorio de Quimica', 4, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4),
(139, 'Literatura', 5, 'Formacion General', 'Alimentos', 72, 2),
(140, 'Ingles', 5, 'Formacion General', 'Alimentos', 72, 2),
(141, 'Educacion Fisica', 5, 'Formacion General', 'Alimentos', 72, 2),
(142, 'Politica y Ciudadania', 5, 'Formacion General', 'Alimentos', 72, 2),
(143, 'Historia', 5, 'Formacion General', 'Alimentos', 72, 2),
(144, 'Geografia', 5, 'Formacion General', 'Alimentos', 72, 2),
(145, 'Analisis Matematico', 5, 'Formacion Cientifico Tecnologica', 'Alimentos', 144, 4),
(146, 'Quimica Organica', 5, 'Formacion Cientifico Tecnologica', 'Alimentos', 144, 4),
(147, 'Quimica General e Inorganica', 5, 'Formacion Cientifico Tecnologica', 'Alimentos', 108, 3),
(148, 'Procesos Quimicos y Control', 5, 'Formacion Cientifico Tecnologica', 'Alimentos', 72, 2),
(149, 'Laboratorio de Procesos Industriales', 5, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4),
(150, 'Laboratorio de Tecnicas Analiticas', 5, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4),
(151, 'Laboratorio de Quimica Organica', 5, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4),
(152, 'Literatura', 6, 'Formacion General', 'Alimentos', 72, 2),
(153, 'Ingles', 6, 'Formacion General', 'Alimentos', 72, 2),
(154, 'Educacion Fisica', 6, 'Formacion General', 'Alimentos', 72, 2),
(155, 'Filosofia', 6, 'Formacion General', 'Alimentos', 72, 2),
(156, 'Arte', 6, 'Formacion General', 'Alimentos', 72, 2),
(157, 'Matematica Aplicada', 6, 'Formacion Cientifico Tecnologica', 'Alimentos', 72, 2),
(158, 'Quimica Organica y Biologica', 6, 'Formacion Cientifico Tecnologica', 'Alimentos', 108, 3),
(159, 'Quimica Industrial', 6, 'Formacion Cientifico Tecnologica', 'Alimentos', 108, 3),
(160, 'Quimica Analitica', 6, 'Formacion Cientifico Tecnologica', 'Alimentos', 108, 3),
(161, 'Derechos del Trabajo', 6, 'Formacion Cientifico Tecnologica', 'Alimentos', 72, 2),
(162, 'Laboratorio de Quimica Organica, Biologica y Microbiologica', 6, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4),
(163, 'Laboratorio de Tecnicas Analiticas', 6, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4),
(164, 'Laboratorio de Procesos Industriales I', 6, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4),
(165, 'Practicas Profesionalizantes del Sector Alimentos', 7, 'Practicas Profesionalizantes', 'Alimentos', 216, 6),
(166, 'Emprendimientos e Innovacion productiva', 7, 'Formacion Cientifico Tecnologica', 'Alimentos', 72, 2),
(167, 'Bromatologia y Nutricion', 7, 'Formacion Cientifico Tecnologica', 'Alimentos', 108, 3),
(168, 'Gestion de la Calidad y Legislacion', 7, 'Formacion Cientifico Tecnologica', 'Alimentos', 108, 3),
(169, 'Organizacion y Gestion Industrial', 7, 'Formacion Cientifico Tecnologica', 'Alimentos', 72, 2),
(170, 'Microbiologia de los Alimentos', 7, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4),
(171, 'Laboratorio de Bromatologia', 7, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4),
(172, 'Laboratorio de Procesos Industriales II', 7, 'Formacion Tecnica Especifica', 'Alimentos', 144, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modelos_examen`
--

CREATE TABLE `modelos_examen` (
  `Id` int(11) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `archivo` varchar(255) NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor_materia`
--

CREATE TABLE `profesor_materia` (
  `Id` int(11) NOT NULL,
  `profesor_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `año_academico` year(4) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `profesor_materia`
--

INSERT INTO `profesor_materia` (`Id`, `profesor_id`, `materia_id`, `año_academico`, `activo`) VALUES
-- Asignaciones de profesores de programación
(1, 1, 36, 2025, 1), -- Carlos Rodriguez - Metodologias de Programacion
(2, 1, 50, 2025, 1), -- Carlos Rodriguez - Lenguajes de Programacion I
(3, 1, 64, 2025, 1), -- Carlos Rodriguez - Lenguajes de Programacion II
(4, 2, 37, 2025, 1), -- Maria Gonzalez - Hardware y Componentes
(5, 2, 51, 2025, 1), -- Maria Gonzalez - Redes Informaticas
(6, 3, 38, 2025, 1), -- Juan Perez - Sistemas Operativos
(7, 3, 52, 2025, 1), -- Juan Perez - Diseño WEB
(8, 3, 66, 2025, 1), -- Juan Perez - Desarrollo de Aplicaciones Web Estaticas
(9, 4, 39, 2025, 1), -- Ana Martinez - Suite de Aplicaciones
(10, 4, 53, 2025, 1), -- Ana Martinez - Arquitectura de Base de Datos
(11, 4, 67, 2025, 1), -- Ana Martinez - Desarrollo de Aplicaciones WEB Dinamicas
(12, 5, 32, 2025, 1), -- Luis Fernandez - Matematica Ciclo Superior
(13, 5, 46, 2025, 1), -- Luis Fernandez - Analisis Matematico
(14, 5, 59, 2025, 1); -- Luis Fernandez - Matematica Aplicada

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno_materia`
--

CREATE TABLE `alumno_materia` (
  `Id` int(11) NOT NULL,
  `alumno_id` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  `año_academico` year(4) NOT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
-- Indices de la tabla `directivos`
--
ALTER TABLE `directivos`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `modelos_examen`
--
ALTER TABLE `modelos_examen`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `profesor_materia`
--
ALTER TABLE `profesor_materia`
  ADD PRIMARY KEY (`Id`);

--
-- Indices de la tabla `alumno_materia`
--
ALTER TABLE `alumno_materia`
  ADD PRIMARY KEY (`Id`);

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
-- AUTO_INCREMENT de la tabla `directivos`
--
ALTER TABLE `directivos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT de la tabla `modelos_examen`
--
ALTER TABLE `modelos_examen`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT de la tabla `profesor_materia`
--
ALTER TABLE `profesor_materia`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `alumno_materia`
--
ALTER TABLE `alumno_materia`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Claves foráneas
--

--
-- Claves foráneas para la tabla `modelos_examen`
--
ALTER TABLE `modelos_examen`
  ADD CONSTRAINT `fk_modelos_examen_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_modelos_examen_materia` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Claves foráneas para la tabla `profesor_materia`
--
ALTER TABLE `profesor_materia`
  ADD CONSTRAINT `fk_profesor_materia_profesor` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_profesor_materia_materia` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Claves foráneas para la tabla `alumno_materia`
--
ALTER TABLE `alumno_materia`
  ADD CONSTRAINT `fk_alumno_materia_alumno` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_alumno_materia_materia` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
