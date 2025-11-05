-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-11-2025 a las 15:45:07
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
-- Base de datos: `mk`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `armas`
--

CREATE TABLE `armas` (
  `id_arma` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `daño` int(11) DEFAULT NULL,
  `cantidad_balas` int(11) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `id_nivel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `armas`
--

INSERT INTO `armas` (`id_arma`, `nombre`, `daño`, `cantidad_balas`, `imagen_url`, `tipo`, `id_nivel`) VALUES
(1, 'Pistola', 22, 22, 'uploads/68e2f21970d3c_pistola.png', NULL, 1),
(2, 'Puño', 33, 22, 'uploads/68ff10b13604d_unnamed-removebg-preview (7).png', NULL, 1),
(3, 'Katana', 33, 33, 'uploads/68e2f1c983751_katana.png', NULL, 2),
(4, 'Kunai', 33, 33, 'uploads/68e2f1e91f53c_kunai.png', NULL, 2),
(5, 'Sombrero', 33, 33, 'uploads/68e2f22c835c5_sombrero.png', NULL, 3),
(6, 'Triblade', 33, 33, 'uploads/68e2f241647ea_triblade.png', NULL, 3),
(7, 'Macuahuitl', 33, 33, 'uploads/68e2f2056576d_macuahuitl.png', NULL, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `avatar`
--

CREATE TABLE `avatar` (
  `id_avatar` int(11) NOT NULL,
  `avatar_foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `avatar`
--

INSERT INTO `avatar` (`id_avatar`, `avatar_foto`) VALUES
(1, 'uploads/68fdba68d8888_Imagen_de_WhatsApp_2025-10-16_a_las_20.24.22_2072d527-removebg-preview.png'),
(2, 'uploads/68fdbc8ac0db7_ran-removebg-preview.png'),
(3, 'uploads/68fdbca9ea0da_es-removebg-preview.png'),
(4, 'uploads/68fdbcd662503_ra-removebg-preview.png'),
(5, 'uploads/68fdbda2e08d1_run-removebg-preview.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estado`, `nombre`) VALUES
(1, 'ACTIVO'),
(2, 'INACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mundos`
--

CREATE TABLE `mundos` (
  `id_mundo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `nivel_requerido` int(11) DEFAULT 1,
  `max_jugadores` int(11) DEFAULT 5,
  `img_mapa` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mundos`
--

INSERT INTO `mundos` (`id_mundo`, `nombre`, `descripcion`, `nivel_requerido`, `max_jugadores`, `img_mapa`) VALUES
(1, 'Bermuda', 'El mapa clásico de Free Fire, una isla tropical con diversos terrenos y zonas urbanas', 2, 5, 'uploads/68fef9f510fcd_mapa1.png'),
(2, 'Purgatorio', 'Un mapa grande con grandes colinas, puentes y áreas industriales; ideal para snipers.', 1, 5, 'uploads/68fefa2c7aed2_mapa2.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id_nivel` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `puntos_requeridos` int(11) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id_nivel`, `nombre`, `puntos_requeridos`, `imagen_url`) VALUES
(1, 'Oro-1', 20, 'uploads/68feba7cd7c8e_unnamed-removebg-preview.png'),
(2, 'Platino-2', 500, 'uploads/68febb9cde7c1_unnamed-removebg-preview (1).png'),
(3, 'Diamante-1', 750, 'uploads/68febcc97826e_unnamed-removebg-preview (3).png'),
(4, 'Heroico-2', 1000, 'uploads/68febd6930eb5_unnamed-removebg-preview (4).png'),
(5, 'Maestro-3', 1250, 'uploads/68febdb27daa6_unnamed-removebg-preview (5).png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partidas`
--

CREATE TABLE `partidas` (
  `id_partida` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL,
  `inicio` datetime DEFAULT current_timestamp(),
  `fin` datetime DEFAULT NULL,
  `duracion_segundos` int(11) DEFAULT NULL,
  `estado` enum('en_curso','finalizada','cancelada') DEFAULT 'en_curso',
  `ganador_documento` varchar(50) DEFAULT NULL,
  `perdedor_documento` varchar(50) DEFAULT NULL,
  `resultado` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personaje`
--

CREATE TABLE `personaje` (
  `id_personaje` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `daño` int(11) DEFAULT NULL,
  `personaje_foto` varchar(255) DEFAULT NULL,
  `id_nivel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `personaje`
--

INSERT INTO `personaje` (`id_personaje`, `nombre`, `daño`, `personaje_foto`, `id_nivel`) VALUES
(1, 'raiden', NULL, 'uploads/68ff148f58d5f_hero1.png', 1),
(2, '', NULL, 'uploads/68ff14977473d_hero2.png', 1),
(3, '', NULL, 'uploads/68ff149d6b9ba_hero3.png', 2),
(4, '', NULL, 'uploads/68ff14a42133f_hero4.png', 3),
(5, '', NULL, 'uploads/68ff14acbcc42_hero5.png', 3),
(6, '', NULL, 'uploads/68ff14b444f68_hero6.png', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_role` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_role`, `nombre`) VALUES
(1, 'ADMINISTRADOR'),
(2, 'USUARIO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `id_sala` int(11) NOT NULL,
  `id_mundo` int(11) NOT NULL,
  `id_nivel` int(11) DEFAULT NULL,
  `nombre_sala` varchar(100) DEFAULT NULL,
  `codigo_sala` varchar(20) DEFAULT NULL,
  `creado_en` datetime DEFAULT current_timestamp(),
  `jugadores_actuales` int(11) DEFAULT 0,
  `nivel_requerido` int(11) DEFAULT 1,
  `estado` enum('abierta','en_partida','cerrada') DEFAULT 'abierta',
  `max_jugadores` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `salas`
--

INSERT INTO `salas` (`id_sala`, `id_mundo`, `id_nivel`, `nombre_sala`, `codigo_sala`, `creado_en`, `jugadores_actuales`, `nivel_requerido`, `estado`, `max_jugadores`) VALUES
(33, 1, 1, '80808', NULL, '2025-11-04 10:43:23', 1, 1, 'en_partida', 2),
(37, 1, 1, '4', NULL, '2025-11-04 16:50:47', 5, 1, 'en_partida', 5),
(38, 1, 1, 'ed', NULL, '2025-11-04 23:58:33', 5, 1, 'en_partida', 5),
(39, 1, 1, 'jryj', NULL, '2025-11-05 00:05:08', 5, 1, 'en_partida', 5),
(40, 1, 1, '44', NULL, '2025-11-05 00:15:46', 5, 1, 'en_partida', 5),
(41, 1, 1, 'fuck', NULL, '2025-11-05 00:19:53', 5, 1, 'en_partida', 5),
(47, 1, 1, 'ebebg', NULL, '2025-11-05 01:02:20', 5, 1, 'en_partida', 5),
(48, 1, 1, 'fff', NULL, '2025-11-05 01:49:55', 5, 1, 'en_partida', 5),
(49, 1, 1, '4fre', NULL, '2025-11-05 02:24:33', 5, 1, 'en_partida', 5),
(50, 1, 1, 'ddv', NULL, '2025-11-05 02:37:19', 5, 1, 'en_partida', 5),
(51, 1, 1, '67', NULL, '2025-11-05 02:53:24', 5, 1, 'en_partida', 5),
(52, 1, 1, '434r4', NULL, '2025-11-05 03:01:55', 5, 1, 'en_partida', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sala_usuarios`
--

CREATE TABLE `sala_usuarios` (
  `id_sala_usuario` int(11) NOT NULL,
  `id_sala` int(11) NOT NULL,
  `documento` varchar(50) NOT NULL,
  `fecha_union` datetime DEFAULT current_timestamp(),
  `listo` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `documento` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pin_verify` int(11) DEFAULT NULL,
  `id_role` int(11) DEFAULT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1,
  `id_nivel` int(11) DEFAULT NULL,
  `puntos_actuales` int(11) DEFAULT 0,
  `fecha_creacion` datetime DEFAULT NULL,
  `ultimo_login` datetime DEFAULT NULL,
  `id_avatar` int(11) NOT NULL,
  `id_personaje` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`documento`, `username`, `email`, `password`, `pin_verify`, `id_role`, `id_estado`, `id_nivel`, `puntos_actuales`, `fecha_creacion`, `ultimo_login`, `id_avatar`, `id_personaje`) VALUES
('1106227312', 'dilan1', 'dilansantiortizm@gmail.com', '$2y$10$OV9raMmE1otyJ0bbPWfCHuaNnWUNG/SuZXISvoUw1cqzgVGlLtJIm', NULL, 2, 1, 1, 750, NULL, '2025-11-05 09:43:41', 5, 3),
('110623634', 'santiago', 'dylansatiagoo@gmail.com', '$2y$10$lvZk8SnFBkBeNZ4YGic5oeJ8zImOfm00fr5h3vj1zlf/QaiFfPPh6', 605, 2, 1, 1, 0, '2025-10-28 09:49:43', '2025-11-05 09:29:24', 3, 2),
('2112332', 'pipe2', 'pipe@gmail.com', '$2y$10$wmzSvm8hkalrewugCgSNr.M2onDWIwJP/fYNfqVGsFClecFQnlBmG', NULL, 1, 1, NULL, 0, NULL, '2025-11-05 09:42:30', 2, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_armas`
--

CREATE TABLE `usuario_armas` (
  `id_usuario_arma` int(11) NOT NULL,
  `documento` varchar(50) NOT NULL,
  `id_arma` int(11) NOT NULL,
  `cantidad_balas` int(11) DEFAULT 0,
  `fecha_adquirido` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_partida`
--

CREATE TABLE `usuario_partida` (
  `id_usuario_partida` int(11) NOT NULL,
  `id_partida` int(11) NOT NULL,
  `documento` varchar(50) NOT NULL,
  `vida_restante` int(11) DEFAULT 100,
  `puntos_acumulados` int(11) DEFAULT 0,
  `id_arma` int(11) DEFAULT NULL,
  `daño_realizado` int(11) DEFAULT 0,
  `daño_recibido` int(11) DEFAULT 0,
  `eliminaciones` int(11) DEFAULT 0,
  `eliminado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `armas`
--
ALTER TABLE `armas`
  ADD PRIMARY KEY (`id_arma`),
  ADD KEY `id_nivel` (`id_nivel`);

--
-- Indices de la tabla `avatar`
--
ALTER TABLE `avatar`
  ADD PRIMARY KEY (`id_avatar`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `mundos`
--
ALTER TABLE `mundos`
  ADD PRIMARY KEY (`id_mundo`),
  ADD KEY `fk_mundos_niveles` (`nivel_requerido`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id_nivel`);

--
-- Indices de la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD PRIMARY KEY (`id_partida`),
  ADD KEY `id_sala` (`id_sala`),
  ADD KEY `ganador_documento` (`ganador_documento`);

--
-- Indices de la tabla `personaje`
--
ALTER TABLE `personaje`
  ADD PRIMARY KEY (`id_personaje`),
  ADD KEY `id_nivel` (`id_nivel`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_role`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id_sala`),
  ADD UNIQUE KEY `codigo_sala` (`codigo_sala`),
  ADD KEY `id_mundo` (`id_mundo`),
  ADD KEY `id_nivel` (`id_nivel`);

--
-- Indices de la tabla `sala_usuarios`
--
ALTER TABLE `sala_usuarios`
  ADD PRIMARY KEY (`id_sala_usuario`),
  ADD KEY `fk_sala_usuario_sala` (`id_sala`),
  ADD KEY `fk_sala_usuario_usuario` (`documento`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`documento`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_role` (`id_role`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_nivel` (`id_nivel`),
  ADD KEY `id_avatar` (`id_avatar`),
  ADD KEY `usuario_ibfk_5` (`id_personaje`);

--
-- Indices de la tabla `usuario_armas`
--
ALTER TABLE `usuario_armas`
  ADD PRIMARY KEY (`id_usuario_arma`),
  ADD KEY `documento` (`documento`),
  ADD KEY `id_arma` (`id_arma`);

--
-- Indices de la tabla `usuario_partida`
--
ALTER TABLE `usuario_partida`
  ADD PRIMARY KEY (`id_usuario_partida`),
  ADD KEY `id_partida` (`id_partida`),
  ADD KEY `id_arma` (`id_arma`),
  ADD KEY `fk_usuario_partida_usuario` (`documento`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `avatar`
--
ALTER TABLE `avatar`
  MODIFY `id_avatar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `mundos`
--
ALTER TABLE `mundos`
  MODIFY `id_mundo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id_nivel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `partidas`
--
ALTER TABLE `partidas`
  MODIFY `id_partida` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personaje`
--
ALTER TABLE `personaje`
  MODIFY `id_personaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id_sala` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `sala_usuarios`
--
ALTER TABLE `sala_usuarios`
  MODIFY `id_sala_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario_armas`
--
ALTER TABLE `usuario_armas`
  MODIFY `id_usuario_arma` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario_partida`
--
ALTER TABLE `usuario_partida`
  MODIFY `id_usuario_partida` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `armas`
--
ALTER TABLE `armas`
  ADD CONSTRAINT `armas_ibfk_1` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id_nivel`);

--
-- Filtros para la tabla `mundos`
--
ALTER TABLE `mundos`
  ADD CONSTRAINT `fk_mundos_niveles` FOREIGN KEY (`nivel_requerido`) REFERENCES `niveles` (`id_nivel`);

--
-- Filtros para la tabla `partidas`
--
ALTER TABLE `partidas`
  ADD CONSTRAINT `fk_partida_ganador` FOREIGN KEY (`ganador_documento`) REFERENCES `usuario` (`documento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_partida_sala` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `personaje`
--
ALTER TABLE `personaje`
  ADD CONSTRAINT `personaje_ibfk_1` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id_nivel`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `salas`
--
ALTER TABLE `salas`
  ADD CONSTRAINT `salas_ibfk_1` FOREIGN KEY (`id_mundo`) REFERENCES `mundos` (`id_mundo`),
  ADD CONSTRAINT `salas_ibfk_2` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id_nivel`);

--
-- Filtros para la tabla `sala_usuarios`
--
ALTER TABLE `sala_usuarios`
  ADD CONSTRAINT `fk_sala_usuario_sala` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sala_usuario_usuario` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`),
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id_nivel`),
  ADD CONSTRAINT `usuario_ibfk_4` FOREIGN KEY (`id_avatar`) REFERENCES `avatar` (`id_avatar`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_ibfk_5` FOREIGN KEY (`id_personaje`) REFERENCES `personaje` (`id_personaje`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario_armas`
--
ALTER TABLE `usuario_armas`
  ADD CONSTRAINT `usuario_armas_ibfk_1` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`),
  ADD CONSTRAINT `usuario_armas_ibfk_2` FOREIGN KEY (`id_arma`) REFERENCES `armas` (`id_arma`);

--
-- Filtros para la tabla `usuario_partida`
--
ALTER TABLE `usuario_partida`
  ADD CONSTRAINT `fk_usuario_partida_usuario` FOREIGN KEY (`documento`) REFERENCES `usuario` (`documento`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
