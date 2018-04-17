-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Mar 17 Avril 2018 à 22:25
-- Version du serveur :  10.1.23-MariaDB-9+deb9u1
-- Version de PHP :  7.0.27-0+deb9u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `replay`
--

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

CREATE TABLE `amis` (
  `ami_demandeur` int(11) NOT NULL,
  `ami_receveur` int(11) NOT NULL,
  `attente` tinyint(1) NOT NULL,
  `date_amitie` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `categories_forum`
--

CREATE TABLE `categories_forum` (
  `id_cat` int(11) NOT NULL,
  `nom_cat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `changement_email`
--

CREATE TABLE `changement_email` (
  `id` int(11) NOT NULL,
  `nouvel_email` varchar(255) NOT NULL,
  `code` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `message_id` int(11) NOT NULL,
  `auteur` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `message_date` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `configuration`
--

CREATE TABLE `configuration` (
  `site_titre` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `configuration`
--

INSERT INTO `configuration` (`site_titre`, `version`) VALUES
('Re:Play', '0.4');

-- --------------------------------------------------------

--
-- Structure de la table `encyclo_consoles`
--

CREATE TABLE `encyclo_consoles` (
  `id` int(11) NOT NULL,
  `auteur` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `plateforme` varchar(4) NOT NULL,
  `contenu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `encyclo_jeux`
--

CREATE TABLE `encyclo_jeux` (
  `id` int(11) NOT NULL,
  `deved` varchar(255) NOT NULL,
  `auteur` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `plateforme` varchar(4) NOT NULL,
  `date_sortie` date NOT NULL,
  `contenu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `encyclo_tutos`
--

CREATE TABLE `encyclo_tutos` (
  `id` int(11) NOT NULL,
  `auteur` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `plateforme` varchar(4) NOT NULL,
  `contenu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `enligne`
--

CREATE TABLE `enligne` (
  `id_membre` int(11) NOT NULL,
  `temps_connexion` int(11) NOT NULL,
  `ip_membre` varchar(255) NOT NULL,
  `useragent_membre` text,
  `page_membre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

CREATE TABLE `membres` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(25) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `ip_inscription` varchar(255) DEFAULT NULL,
  `motdepasse` text,
  `avatar` varchar(255) NOT NULL,
  `grade` int(11) NOT NULL DEFAULT '4',
  `date_inscription` varchar(11) DEFAULT NULL,
  `date_derniere_visite` int(11) NOT NULL,
  `derniere_ip` varchar(255) DEFAULT NULL,
  `couleur_theme` varchar(255) NOT NULL,
  `couleur_theme2` varchar(7) NOT NULL,
  `theme_sombre` int(1) NOT NULL,
  `couleur_pseudo` varchar(255) NOT NULL,
  `anniversaire` date NOT NULL,
  `desactive` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `membres`
--

INSERT INTO `membres` (`id`, `pseudo`, `email`, `ip_inscription`, `motdepasse`, `avatar`, `grade`, `date_inscription`, `date_derniere_visite`, `derniere_ip`, `couleur_theme`, `couleur_theme2`, `theme_sombre`, `couleur_pseudo`, `anniversaire`, `desactive`) VALUES
(1, 'Système', '', NULL, NULL, '/ressources/avatars/defaut.jpg', 4, '1505772000', 0, '', '', '', 0, '#27AE60; background-color: #333D3F;padding:5px;border-radius:5px;', '0000-00-00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `id_posteur` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `reinitialisation_mdp`
--

CREATE TABLE `reinitialisation_mdp` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `sscat_forum`
--

CREATE TABLE `sscat_forum` (
  `id_sscat` int(11) NOT NULL,
  `categories` int(11) NOT NULL,
  `nom_sscat` varchar(255) NOT NULL,
  `desc_sscat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `sujet_forum`
--

CREATE TABLE `sujet_forum` (
  `id` int(11) NOT NULL,
  `id_sscat` int(11) NOT NULL,
  `auteur` int(11) NOT NULL,
  `date_creation` varchar(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `categories_forum`
--
ALTER TABLE `categories_forum`
  ADD PRIMARY KEY (`id_cat`);

--
-- Index pour la table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Index pour la table `encyclo_consoles`
--
ALTER TABLE `encyclo_consoles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `encyclo_jeux`
--
ALTER TABLE `encyclo_jeux`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `encyclo_tutos`
--
ALTER TABLE `encyclo_tutos`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `membres`
--
ALTER TABLE `membres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reinitialisation_mdp`
--
ALTER TABLE `reinitialisation_mdp`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sscat_forum`
--
ALTER TABLE `sscat_forum`
  ADD PRIMARY KEY (`id_sscat`);

--
-- Index pour la table `sujet_forum`
--
ALTER TABLE `sujet_forum`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `categories_forum`
--
ALTER TABLE `categories_forum`
  MODIFY `id_cat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT pour la table `encyclo_consoles`
--
ALTER TABLE `encyclo_consoles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `encyclo_jeux`
--
ALTER TABLE `encyclo_jeux`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `encyclo_tutos`
--
ALTER TABLE `encyclo_tutos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `membres`
--
ALTER TABLE `membres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pour la table `reinitialisation_mdp`
--
ALTER TABLE `reinitialisation_mdp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `sscat_forum`
--
ALTER TABLE `sscat_forum`
  MODIFY `id_sscat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `sujet_forum`
--
ALTER TABLE `sujet_forum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
