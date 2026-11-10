-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 24 juin 2024 à 13:21
-- Version du serveur : 8.0.35
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecommerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `avie`
--

CREATE TABLE `avie` (
  `idavie` int NOT NULL,
  `idprofil` int NOT NULL,
  `message` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `idcategorie` int NOT NULL,
  `nomcategorie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`idcategorie`, `nomcategorie`) VALUES
(1, 'pantalon'),
(2, 'T-shirts et maillots de corps'),
(3, 'Chemisiers et blouses'),
(4, 'Manteaux, parkas, anoraks, blousons et articles similaires'),
(5, 'Chemises pour hommes ou garçonnets'),
(6, 'Vestes');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `idprofil` int NOT NULL,
  `idproduit` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`idprofil`, `idproduit`) VALUES
(6, 13),
(6, 14);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `idproduit` int NOT NULL,
  `nomproduit` varchar(255) NOT NULL,
  `imageproduit` varchar(255) NOT NULL,
  `quantite` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `prix` int NOT NULL,
  `idcategorie` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`idproduit`, `nomproduit`, `imageproduit`, `quantite`, `description`, `prix`, `idcategorie`) VALUES
(10, 't-shirt zara', 'product-item1.jpg', 75, 'tshirt zara', 350, 2),
(11, 't-shirt bershka', 'product-item-lg4.jpg', 150, 'tshirtbershka', 450, 2),
(12, 't-shirt zara', 'product-item5.jpg', 45, 'tshirt zara', 250, 2),
(13, 'hoodie noir', 'product-item-lg1.jpg', 95, 'hoodie-hoodie', 400, 2),
(14, 'T-shirt noir', 'product-item10.jpg', 95, 'T-shirt noir Bershka', 300, 2),
(16, 'Hoodie', 'product-item6.jpg', 95, 'Hoodie-ZARA', 300, 2);

-- --------------------------------------------------------

--
-- Structure de la table `profil`
--

CREATE TABLE `profil` (
  `idprofil` int NOT NULL,
  `iduser` int NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `codepostal` varchar(8) NOT NULL,
  `telephone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `profil`
--

INSERT INTO `profil` (`idprofil`, `iduser`, `nom`, `prenom`, `adresse`, `pays`, `ville`, `codepostal`, `telephone`) VALUES
(1, 5, 'bennis', 'reda', 'hay laayoune rue 20 n 27', 'Maroc', 'Casablanca', '10856', '0656707742'),
(2, 6, 'bencar', 'achraf', 'bouskoura', 'Maroc', 'Agadir', '20758', '0671283679'),
(6, 10, 'soufianebennishamada', 'bennis', 'hay laayoune rue 20 n 27', 'Maroc', 'Casablanca', '20758', '0671283678');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `iduser` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `birthday` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`iduser`, `email`, `username`, `password`, `role`, `birthday`) VALUES
(5, 'soufianebennis699@gmail.com', 'soufianebennis', '$2y$10$DqrKoMxFof8tWBIrGpCWge4V9G8wDi.waNKkrfdX42ypbF12UMgzO', 'client', '2024-06-13'),
(6, 'achrafbennis699@gmail.com', 'achrafbennis', '$2y$10$dolj3BdEATYbwAyQr4HpZelXwvod9nuZWNtRRPr4eSo58qjTe.gYu', 'client', '2024-06-15'),
(10, 'safaa@gmail.com', 'safaasafaa', '$2y$10$Hg5.W05J8DrK37/9MU9wfu/CxaaWqjSRNmboi0dF3k8oHx4ZBOR.i', 'admin', '2024-06-06');

-- --------------------------------------------------------

--
-- Structure de la table `voscommande`
--

CREATE TABLE `voscommande` (
  `idcommande` int NOT NULL,
  `idprofil` int NOT NULL,
  `statue` varchar(255) NOT NULL,
  `detail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avie`
--
ALTER TABLE `avie`
  ADD PRIMARY KEY (`idavie`),
  ADD KEY `idprofil` (`idprofil`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`idcategorie`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD KEY `idprofil` (`idprofil`),
  ADD KEY `idproduit` (`idproduit`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`idproduit`),
  ADD KEY `idcategorie` (`idcategorie`);

--
-- Index pour la table `profil`
--
ALTER TABLE `profil`
  ADD PRIMARY KEY (`idprofil`),
  ADD KEY `iduser` (`iduser`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`iduser`);

--
-- Index pour la table `voscommande`
--
ALTER TABLE `voscommande`
  ADD PRIMARY KEY (`idcommande`),
  ADD KEY `idprofil` (`idprofil`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avie`
--
ALTER TABLE `avie`
  MODIFY `idavie` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `idcategorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `idproduit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `profil`
--
ALTER TABLE `profil`
  MODIFY `idprofil` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `iduser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `voscommande`
--
ALTER TABLE `voscommande`
  MODIFY `idcommande` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avie`
--
ALTER TABLE `avie`
  ADD CONSTRAINT `avie_ibfk_1` FOREIGN KEY (`idprofil`) REFERENCES `profil` (`idprofil`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`idprofil`) REFERENCES `profil` (`idprofil`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`idproduit`) REFERENCES `produit` (`idproduit`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`idcategorie`) REFERENCES `categorie` (`idcategorie`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `profil`
--
ALTER TABLE `profil`
  ADD CONSTRAINT `profil_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `voscommande`
--
ALTER TABLE `voscommande`
  ADD CONSTRAINT `voscommande_ibfk_1` FOREIGN KEY (`idprofil`) REFERENCES `profil` (`idprofil`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

