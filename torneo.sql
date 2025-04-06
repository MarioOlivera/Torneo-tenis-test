-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-04-2025 a las 21:51:54
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `torneo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genders`
--

CREATE TABLE `genders` (
  `id` int(11) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genders`
--

INSERT INTO `genders` (`id`, `gender`, `created_at`, `updated_at`) VALUES
(1, 'MALE', '2025-04-04 15:47:43', '2025-04-04 10:47:54'),
(2, 'FEMALE', '2025-04-04 15:47:43', '2025-04-04 10:47:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `skill_level` int(11) NOT NULL,
  `strength` int(11) DEFAULT NULL,
  `speed` int(11) DEFAULT NULL,
  `reaction_time` int(11) DEFAULT NULL,
  `gender_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tournaments`
--

CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tournament_categories`
--

CREATE TABLE `tournament_categories` (
  `id` int(11) NOT NULL,
  `category` varchar(7) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tournament_categories`
--

INSERT INTO `tournament_categories` (`id`, `category`, `created_at`, `updated_at`) VALUES
(1, 'MEN´S', '2025-04-04 15:53:35', '2025-04-04 15:53:35'),
(2, 'WOMEN\'S', '2025-04-04 15:53:35', '2025-04-04 15:53:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tournament_matches`
--

CREATE TABLE `tournament_matches` (
  `id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `player_one` int(11) NOT NULL,
  `player_two` int(11) NOT NULL,
  `player_winner` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tournament_registrations`
--

CREATE TABLE `tournament_registrations` (
  `id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tournament_statuses`
--

CREATE TABLE `tournament_statuses` (
  `id` int(11) NOT NULL,
  `state` varchar(9) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tournament_statuses`
--

INSERT INTO `tournament_statuses` (`id`, `state`, `created_at`, `updated_at`) VALUES
(1, 'PENDING', '2025-04-04 15:54:27', '2025-04-04 15:54:27'),
(2, 'COMPLETED', '2025-04-04 15:54:27', '2025-04-04 15:54:27'),
(3, 'CANCELLED', '2025-04-04 15:54:44', '2025-04-04 15:54:44');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_players_gender` (`gender_id`);

--
-- Indices de la tabla `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tournaments_category` (`category_id`),
  ADD KEY `fk_tournaments_status` (`status_id`);

--
-- Indices de la tabla `tournament_categories`
--
ALTER TABLE `tournament_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tournament_matches`
--
ALTER TABLE `tournament_matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_matches_player_one` (`player_one`),
  ADD KEY `fk_matches_player_two` (`player_two`),
  ADD KEY `fk_matches_player_winner` (`player_winner`),
  ADD KEY `fk_matches_tournament` (`tournament_id`);

--
-- Indices de la tabla `tournament_registrations`
--
ALTER TABLE `tournament_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_registrations_player` (`player_id`),
  ADD KEY `fk_registrations_tournament` (`tournament_id`);

--
-- Indices de la tabla `tournament_statuses`
--
ALTER TABLE `tournament_statuses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `genders`
--
ALTER TABLE `genders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tournament_categories`
--
ALTER TABLE `tournament_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tournament_matches`
--
ALTER TABLE `tournament_matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tournament_registrations`
--
ALTER TABLE `tournament_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tournament_statuses`
--
ALTER TABLE `tournament_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `fk_players_gender` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tournaments`
--
ALTER TABLE `tournaments`
  ADD CONSTRAINT `fk_tournaments_category` FOREIGN KEY (`category_id`) REFERENCES `tournament_categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tournaments_status` FOREIGN KEY (`status_id`) REFERENCES `tournament_statuses` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tournament_matches`
--
ALTER TABLE `tournament_matches`
  ADD CONSTRAINT `fk_matches_player_one` FOREIGN KEY (`player_one`) REFERENCES `players` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matches_player_two` FOREIGN KEY (`player_two`) REFERENCES `players` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matches_player_winner` FOREIGN KEY (`player_winner`) REFERENCES `players` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_matches_tournament` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tournament_registrations`
--
ALTER TABLE `tournament_registrations`
  ADD CONSTRAINT `fk_registrations_player` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registrations_tournament` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
