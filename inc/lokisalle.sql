-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 31 Mars 2014 à 16:06
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `lokisalle`
--
CREATE DATABASE IF NOT EXISTS `lokisalle` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `lokisalle`;

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE IF NOT EXISTS `avis` (
  `id_avis` int(5) NOT NULL AUTO_INCREMENT,
  `commentaire` text,
  `note` int(2) DEFAULT NULL,
  `date` datetime NOT NULL,
  `id_salle` int(5) NOT NULL,
  `id_membre` int(5) NOT NULL,
  PRIMARY KEY (`id_avis`),
  KEY `fk_avis_salle1_idx` (`id_salle`),
  KEY `fk_avis_membre1_idx` (`id_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` int(6) NOT NULL AUTO_INCREMENT,
  `montant` int(5) NOT NULL,
  `date` datetime NOT NULL,
  `id_membre` int(5) NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `fk_commande_membre1_idx` (`id_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `details_commande`
--

CREATE TABLE IF NOT EXISTS `details_commande` (
  `id_details_commande` int(6) NOT NULL AUTO_INCREMENT,
  `id_commande` int(6) NOT NULL,
  `id_produit` int(5) NOT NULL,
  PRIMARY KEY (`id_details_commande`),
  KEY `fk_details_commande_commande1_idx` (`id_commande`),
  KEY `fk_details_commande_produit1_idx` (`id_produit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(5) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(15) NOT NULL,
  `mdp` varchar(32) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `sexe` enum('m','f') DEFAULT NULL,
  `ville` varchar(20) DEFAULT NULL,
  `cp` int(5) DEFAULT NULL,
  `adresse` varchar(30) DEFAULT NULL,
  `statut` int(1) DEFAULT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `email`, `sexe`, `ville`, `cp`, `adresse`, `statut`) VALUES
(1, 'admin', 'admin', 'basquin', 'julien', 'julienbasquin@hotmail.com', 'm', 'PARIS', 75, '70 rue de tocqueville', 1),
(2, 'test', 'test', 'test', 'test', 'test@test.fr', 'm', 'test', 0, 'test', 0);

-- --------------------------------------------------------

--
-- Structure de la table `newsletter`
--

CREATE TABLE IF NOT EXISTS `newsletter` (
  `id_newsletter` int(5) NOT NULL AUTO_INCREMENT,
  `id_membre` int(5) NOT NULL,
  PRIMARY KEY (`id_newsletter`),
  KEY `fk_newsletter_membre_idx` (`id_membre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE IF NOT EXISTS `produit` (
  `id_produit` int(5) NOT NULL AUTO_INCREMENT,
  `date_arrivee` datetime NOT NULL,
  `date_depart` datetime NOT NULL,
  `prix` int(5) NOT NULL,
  `etat` int(1) DEFAULT NULL,
  `id_salle` int(5) NOT NULL,
  `id_promotion` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id_produit`),
  KEY `fk_produit_salle1_idx` (`id_salle`),
  KEY `fk_produit_promotion1_idx` (`id_promotion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `date_arrivee`, `date_depart`, `prix`, `etat`, `id_salle`, `id_promotion`) VALUES
(1, '2014-10-01 09:00:00', '2014-10-04 18:00:00', 300, 0, 1, NULL),
(2, '2014-10-05 09:00:00', '2014-10-08 18:00:00', 350, 0, 3, NULL),
(3, '2014-10-09 09:00:00', '2014-10-12 18:00:00', 300, 0, 1, NULL),
(4, '2014-10-13 09:00:00', '2014-10-16 18:00:00', 300, 0, 1, NULL),
(5, '2014-10-17 09:00:00', '2014-10-20 18:00:00', 500, 0, 2, NULL),
(6, '2014-10-21 09:00:00', '2014-10-24 18:00:00', 666, 0, 6, NULL),
(7, '2014-10-25 09:00:00', '2014-10-29 18:00:00', 500, 0, 5, NULL),
(8, '2014-10-26 09:00:00', '2014-10-29 18:00:00', 600, 0, 4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `promotion`
--

CREATE TABLE IF NOT EXISTS `promotion` (
  `id_promotion` smallint(6) NOT NULL AUTO_INCREMENT,
  `code_promo` varchar(6) NOT NULL,
  `reduction` int(5) NOT NULL,
  PRIMARY KEY (`id_promotion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `promotion`
--

INSERT INTO `promotion` (`id_promotion`, `code_promo`, `reduction`) VALUES
(1, 'lo456k', 100),
(2, 'lo123k', 100),
(3, 'lo654k', 75);

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE IF NOT EXISTS `salle` (
  `id_salle` int(5) NOT NULL AUTO_INCREMENT,
  `pays` varchar(20) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `adresse` text NOT NULL,
  `cp` varchar(5) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `capacite` mediumint(9) NOT NULL,
  `categorie` varchar(45) NOT NULL,
  PRIMARY KEY (`id_salle`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Contenu de la table `salle`
--

INSERT INTO `salle` (`id_salle`, `pays`, `ville`, `adresse`, `cp`, `titre`, `description`, `photo`, `capacite`, `categorie`) VALUES
(1, 'France', 'Paris', '10 rue Adresse', '75', 'Salle Duval', 'description', 'img/salle1.jpg', 50, 'reunion'),
(2, 'France', 'Paris', '10 rue Adresse', '75', 'Salle baron', 'description', 'img/salle2.jpg', 70, 'reunion'),
(3, 'France', 'Paris', '10 rue Adresse', '75', 'Salle Bardin', 'description', 'img/salle3.jpg', 20, 'reunion'),
(4, 'France', 'Paris', '10 rue Adresse', '75', 'Salle Baille', 'description', 'img/salle4.jpg', 80, 'reunion'),
(5, 'France', 'Paris', '10 rue Adresse', '75', 'Salle Ballerat', 'description', 'img/salle5.png', 50, 'reunion'),
(6, 'France', 'Marseille', '10 rue Adresse', '13', 'Salle Victoire', 'description', 'img/salle6.jpg', 30, 'reunion'),
(7, 'France', 'Lyon', '10 rue Adresse', '69', 'Salle Ballerat', 'description', 'img/salle7.jpg', 15, 'reunion'),
(8, 'France', 'Paris', '10 rue Adresse', '75', 'Salle Cabat', 'description', 'img/salle8.jpg', 25, 'reunion'),
(9, 'France', 'Marseille', '10 rue Adresse', '13', 'Salle carriere', 'description', 'img/salle9.jpg', 10, 'reunion'),
(10, 'France', 'Lyon', '10 rue Adresse', '69', 'Salle Cezanne', 'description', 'img/salle10.jpg', 30, 'reunion'),
(11, 'France', 'Paris', '10 rue Adresse', '75', 'Salle Clesinger', 'description', 'img/salle11.jpg', 45, 'reunion'),
(12, 'France', 'Marseille', '10 rue Adresse', '13', 'Salle Couture', 'description', 'img/salle12.jpg', 20, 'reunion'),
(13, 'France', 'Paris', '10 rue Adresse', '75', 'Salle Daubigny', 'description', 'img/salle13.jpg', 30, 'reunion'),
(14, 'France', 'Lyon', '10 rue Adresse', '69', 'Salle Delacroix', 'description', 'img/salle14.jpg', 20, 'reunion'),
(15, 'France', 'Paris', '10 rue Adresse', '75', 'Salle Delaroche', 'description', 'img/salle15.jpg', 20, 'reunion'),
(16, 'France', 'Marseille', '10 rue Adresse', '13', 'Salle Demanche', 'description', 'img/salle16.jpg', 35, 'reunion'),
(17, 'France', 'Lyon', '10 rue Adresse', '69', 'Salle Latour', 'description', 'img/salle17.jpg', 20, 'reunion'),
(18, 'France', 'Paris', '10 rue Adresse', '75', 'Salle Jouvenet', 'description', 'img/salle18.jpg', 60, 'reunion'),
(19, 'France', 'Lyon', '10 rue Adresse', '69', 'Salle Grimaud', 'description', 'img/salle19.jpg', 65, 'reunion'),
(20, 'France', 'Paris', '10 rue Adresse', '75', 'Salle Langlois', 'description', 'img/salle20.jpg', 30, 'reunion');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `fk_avis_membre1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_avis_salle1` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `fk_commande_membre1` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `details_commande`
--
ALTER TABLE `details_commande`
  ADD CONSTRAINT `fk_details_commande_commande1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_details_commande_produit1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `newsletter`
--
ALTER TABLE `newsletter`
  ADD CONSTRAINT `fk_newsletter_membre` FOREIGN KEY (`id_membre`) REFERENCES `membre` (`id_membre`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk_produit_salle1` FOREIGN KEY (`id_salle`) REFERENCES `salle` (`id_salle`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
