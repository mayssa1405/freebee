-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 01 mai 2025 à 22:41
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `freebee_platform`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `cin` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `cin`, `phone`, `email`, `password_hash`, `created_at`, `password`) VALUES
(1, 'mayssa', 'chrif', '14785895', '96289477', 'mayssacherif234@gmail.com', '', '2025-04-25 23:53:30', '$2y$10$nC8MLLTdPcpdjIB8G1r6RuGgeVVAbABhrFKEUMv61UExWDfUBa9FW'),
(3, 'mayssa', 'chrif', '15478963', '96289477', 'mayssacherif255@gmail.com', '', '2025-04-26 00:00:37', '$2y$10$WW4.l0i7WwL4fZF1bU/SGecaxImJIC91iyXiXe192tQzGItz9wfb.');

-- --------------------------------------------------------

--
-- Structure de la table `user_alerts`
--

CREATE TABLE `user_alerts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `alert_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `alert_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_alerts`
--

INSERT INTO `user_alerts` (`id`, `user_id`, `alert_message`, `created_at`, `alert_type`) VALUES
(1, 1, 'High pollen levels today. Take precautions!', '2025-04-26 00:06:50', 'Pollen Allergy');

-- --------------------------------------------------------

--
-- Structure de la table `user_allergies`
--

CREATE TABLE `user_allergies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `allergy_type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_locations`
--

CREATE TABLE `user_locations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user_locations`
--

INSERT INTO `user_locations` (`id`, `user_id`, `city`, `region`, `latitude`, `longitude`, `country`) VALUES
(1, 1, 'Tunis', NULL, NULL, NULL, NULL),
(2, 3, 'Tunis', 'Tunis', 33.8869, 9.5375, 'Tunisia'),
(3, 1, 'Tunis', NULL, NULL, NULL, 'Tunisia'),
(4, 3, 'Tunis', 'Tunis', 33.8869, 9.5375, 'Tunisia');

-- --------------------------------------------------------

--
-- Structure de la table `weather_data`
--

CREATE TABLE `weather_data` (
  `id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `temperature` float DEFAULT NULL,
  `weather_condition` varchar(100) DEFAULT NULL,
  `pollen_level` varchar(100) DEFAULT NULL,
  `recorded_at` datetime DEFAULT current_timestamp(),
  `last_updated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `weather_data`
--

INSERT INTO `weather_data` (`id`, `location_id`, `temperature`, `weather_condition`, `pollen_level`, `recorded_at`, `last_updated`) VALUES
(1, 2, 25.52, 'broken clouds', NULL, '2025-04-26 10:04:49', '2025-04-26'),
(2, 2, 25.52, 'broken clouds', NULL, '2025-04-26 10:05:19', '2025-04-26'),
(3, 2, 25.52, 'broken clouds', NULL, '2025-04-26 10:05:42', '2025-04-26'),
(4, 2, 25.52, 'broken clouds', NULL, '2025-04-26 10:05:59', '2025-04-26'),
(5, 2, 25.52, 'broken clouds', NULL, '2025-04-26 10:06:48', '2025-04-26'),
(6, 2, 25.52, 'broken clouds', NULL, '2025-04-26 10:09:52', '2025-04-26'),
(7, 2, 25.52, 'broken clouds', NULL, '2025-04-26 10:10:47', '2025-04-26'),
(8, 2, 25.52, 'broken clouds', NULL, '2025-04-26 10:21:46', '2025-04-26'),
(9, 2, 25.52, 'broken clouds', NULL, '2025-04-26 10:23:32', '2025-04-26'),
(10, 2, 25.52, 'broken clouds', NULL, '2025-04-26 10:23:38', '2025-04-26'),
(11, 2, 26.75, 'broken clouds', NULL, '2025-04-26 11:05:18', '2025-04-26'),
(12, 2, 26.75, 'broken clouds', NULL, '2025-04-26 11:05:20', '2025-04-26'),
(13, 2, 26.75, 'broken clouds', NULL, '2025-04-26 11:11:43', '2025-04-26'),
(14, 2, 26.75, 'broken clouds', NULL, '2025-04-26 11:29:21', '2025-04-26'),
(15, 2, 26.75, 'broken clouds', NULL, '2025-04-26 11:38:00', '2025-04-26'),
(16, 2, 24.26, 'few clouds', NULL, '2025-05-01 21:38:31', '2025-05-01');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cin` (`cin`),
  ADD UNIQUE KEY `unique_cin` (`cin`);

--
-- Index pour la table `user_alerts`
--
ALTER TABLE `user_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_allergies`
--
ALTER TABLE `user_allergies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `user_locations`
--
ALTER TABLE `user_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `weather_data`
--
ALTER TABLE `weather_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `user_alerts`
--
ALTER TABLE `user_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user_allergies`
--
ALTER TABLE `user_allergies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user_locations`
--
ALTER TABLE `user_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `weather_data`
--
ALTER TABLE `weather_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user_alerts`
--
ALTER TABLE `user_alerts`
  ADD CONSTRAINT `user_alerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_allergies`
--
ALTER TABLE `user_allergies`
  ADD CONSTRAINT `user_allergies_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `user_locations`
--
ALTER TABLE `user_locations`
  ADD CONSTRAINT `user_locations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `weather_data`
--
ALTER TABLE `weather_data`
  ADD CONSTRAINT `weather_data_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `user_locations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
