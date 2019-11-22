-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Lun 10 Juillet 2017 à 09:21
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `chateauform`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  `logo` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `disponibilite`
--

CREATE TABLE `disponibilite` (
  `id` int(11) NOT NULL,
  `dateSeminaire` date NOT NULL,
  `idSeminaire` int(11) DEFAULT NULL,
  `nomSeminaire` varchar(70) DEFAULT NULL,
  `idSalle` int(11) NOT NULL,
  `creneau` time NOT NULL,
  `debutSeminaire` time DEFAULT NULL,
  `finSeminaire` time DEFAULT NULL,
  `libre` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `idSalle` int(11) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  `fonction` varchar(30) NOT NULL,
  `parcoursA1` varchar(300) NOT NULL,
  `parcoursA2` varchar(300) NOT NULL,
  `parcoursA3` varchar(300) NOT NULL,
  `intitule` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `sederouler`
--

CREATE TABLE `sederouler` (
  `idSeminaire` int(5) NOT NULL,
  `idSalle` int(5) NOT NULL,
  `debut` time NOT NULL,
  `fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `seminaire`
--

CREATE TABLE `seminaire` (
  `idSeminaire` int(11) NOT NULL,
  `nomSeminaire` varchar(110) NOT NULL,
  `dateSeminaire` date NOT NULL,
  `affNom` tinyint(1) NOT NULL,
  `idClient` int(11) NOT NULL,
  `message` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `disponibilite`
--
ALTER TABLE `disponibilite`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`idSalle`);

--
-- Index pour la table `sederouler`
--
ALTER TABLE `sederouler`
  ADD PRIMARY KEY (`idSeminaire`,`idSalle`,`debut`),
  ADD KEY `idSalle` (`idSalle`);

--
-- Index pour la table `seminaire`
--
ALTER TABLE `seminaire`
  ADD PRIMARY KEY (`idSeminaire`),
  ADD KEY `seminaire_ibfk_1` (`idClient`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;
--
-- AUTO_INCREMENT pour la table `disponibilite`
--
ALTER TABLE `disponibilite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1098;
--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `idSalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT pour la table `seminaire`
--
ALTER TABLE `seminaire`
  MODIFY `idSeminaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1779;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `sederouler`
--
ALTER TABLE `sederouler`
  ADD CONSTRAINT `sederouler_ibfk_1` FOREIGN KEY (`idSeminaire`) REFERENCES `seminaire` (`idSeminaire`) ON DELETE CASCADE,
  ADD CONSTRAINT `sederouler_ibfk_2` FOREIGN KEY (`idSalle`) REFERENCES `salle` (`idSalle`);

--
-- Contraintes pour la table `seminaire`
--
ALTER TABLE `seminaire`
  ADD CONSTRAINT `seminaire_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `client` (`id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Événements
--
CREATE DEFINER=`root`@`localhost` EVENT `cleanDispo` ON SCHEDULE EVERY 1 DAY STARTS '2017-06-13 14:24:42' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM disponibilite WHERE dateSeminaire< NOW()$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
