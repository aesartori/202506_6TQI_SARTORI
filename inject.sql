-- Création de la base de données
CREATE DATABASE IF NOT EXISTS `event_manager_v2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `event_manager_v2`;

-- --------------------------------------------------------
-- Structure de la table `artiste`
CREATE TABLE IF NOT EXISTS `artiste` (
  `id_artiste` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_artiste`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- Insertion de 10 artistes
INSERT INTO `artiste` (`nom`, `url`, `photo`) VALUES
('The Rolling Stones', 'https://rollingstones.com', 'stones.jpg'),
('Daft Punk', 'https://daftpunk.com', 'daftpunk.jpg'),
('Ed Sheeran', 'https://edsheeran.com', 'ed.jpg'),
('Beyoncé', 'https://beyonce.com', 'beyonce.jpg'),
('David Guetta', 'https://davidguetta.com', 'guetta.jpg'),
('Muse', 'https://muse.mu', 'muse.jpg'),
('Adele', 'https://adele.com', 'adele.jpg'),
('Coldplay', 'https://coldplay.com', 'coldplay.jpg'),
('Lady Gaga', 'https://ladygaga.com', 'gaga.jpg'),
('Metallica', 'https://metallica.com', 'metallica.jpg');

-- --------------------------------------------------------
-- Structure de la table `venue`
CREATE TABLE IF NOT EXISTS `venue` (
  `id_venue` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_venue`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

-- Insertion de 10 lieux
INSERT INTO `venue` (`nom`, `type`, `adresse`, `url`, `photo`) VALUES
('Stade de France', 'Stadium', 'Saint-Denis, France', 'https://stadedefrance.com', 'stade.jpg'),
('Accor Arena', 'Arena', 'Paris, France', 'https://accorarena.com', 'accor.jpg'),
('Olympia', 'Théâtre', 'Paris, France', 'https://olympia.com', 'olympia.jpg'),
('Bercy', 'Salle polyvalente', 'Paris, France', 'https://bercy.fr', 'bercy.jpg'),
('Royal Albert Hall', 'Salle de concert', 'Londres, UK', 'https://royalalberthall.com', 'albert.jpg'),
('Madison Square Garden', 'Arena', 'New York, USA', 'https://msg.com', 'msg.jpg'),
('O2 Arena', 'Arena', 'Londres, UK', 'https://theo2.co.uk', 'o2.jpg'),
('Opéra Garnier', 'Opéra', 'Paris, France', 'https://opera-garnier.fr', 'opera.jpg'),
('Red Rocks', 'Amphithéâtre naturel', 'Colorado, USA', 'https://redrocks.com', 'redrocks.jpg'),
('Sydney Opera House', 'Opéra', 'Sydney, Australie', 'https://sydneyoperahouse.com', 'sydney.jpg');

-- --------------------------------------------------------
-- Structure de la table `evenement`
CREATE TABLE IF NOT EXISTS `evenement` (
  `id_evenement` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_heure` datetime NOT NULL,
  `prix` decimal(10,2) DEFAULT 0.00,
  `id_venue` int(11) DEFAULT NULL,
  `id_artiste` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_evenement`),
  KEY `id_venue` (`id_venue`),
  KEY `id_artiste` (`id_artiste`),
  CONSTRAINT `evenement_ibfk_1` FOREIGN KEY (`id_venue`) REFERENCES `venue` (`id_venue`),
  CONSTRAINT `evenement_ibfk_2` FOREIGN KEY (`id_artiste`) REFERENCES `artiste` (`id_artiste`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

-- Insertion de 15 événements
INSERT INTO `evenement` (`titre`, `description`, `date_heure`, `prix`, `id_venue`, `id_artiste`, `image`) VALUES
('Tournée mondiale', 'La dernière tournée des Rolling Stones', '2025-07-01 20:00:00', 120.00, 1, 1, 'tour.jpg'),
('Electro Night', 'Soirée spéciale Daft Punk', '2025-08-15 22:00:00', 80.00, 2, 2, 'electro.jpg'),
('Divide Tour', 'Concert acoustique d\'Ed Sheeran', '2025-09-10 19:30:00', 75.00, 3, 3, 'divide.jpg'),
('Renaissance Tour', 'Spectacle épique de Beyoncé', '2025-10-05 20:00:00', 150.00, 4, 4, 'renaissance.jpg'),
('Ultra Music Festival', 'DJ set de David Guetta', '2025-11-20 23:00:00', 65.00, 5, 5, 'ultra.jpg'),
('Simulation Theory', 'Concert futuriste de Muse', '2025-12-12 20:30:00', 90.00, 6, 6, 'simulation.jpg'),
('Adele Live', 'Retour sur scène d\'Adele', '2026-01-15 19:00:00', 200.00, 7, 7, 'adele_live.jpg'),
('Music of the Spheres', 'Spectacle spatial de Coldplay', '2026-02-14 20:00:00', 110.00, 8, 8, 'spheres.jpg'),
('Chromatica Ball', 'Show visuel de Lady Gaga', '2026-03-08 21:00:00', 130.00, 9, 9, 'chromatica.jpg'),
('Metallica WorldWired', 'Concert métal de Metallica', '2026-04-20 19:30:00', 85.00, 10, 10, 'worldwired.jpg'),
('Jazz Night', 'Soirée jazz avec artistes invités', '2026-05-05 20:00:00', 45.00, 3, NULL, 'jazz.jpg'),
('Festival Rock', '3 jours de rock non-stop', '2026-06-18 18:00:00', 180.00, 1, NULL, 'rockfest.jpg'),
('Classique Moderne', 'Orchestre symphonique moderne', '2026-07-22 19:30:00', 55.00, 8, NULL, 'classique.jpg'),
('Hip-Hop Summit', 'Battle de rap international', '2026-08-30 17:00:00', 40.00, 2, NULL, 'hiphop.jpg'),
('EDM Festival', 'Festival électronique en plein air', '2026-09-12 22:00:00', 70.00, 5, 5, 'edm.jpg');

-- --------------------------------------------------------
-- Structure de la table `ticket`
CREATE TABLE IF NOT EXISTS `ticket` (
  `id_ticket` int(11) NOT NULL AUTO_INCREMENT,
  `code_unique` varchar(20) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prix_personne` decimal(10,2) NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `date_reservation` datetime DEFAULT current_timestamp(),
  `statut` enum('En attente','Payé','Annulé') DEFAULT 'En attente',
  `utilise` tinyint(1) DEFAULT 0,
  `id_evenement` int(11) NOT NULL,
  PRIMARY KEY (`id_ticket`),
  UNIQUE KEY `code_unique` (`code_unique`),
  KEY `id_evenement` (`id_evenement`),
  CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`id_evenement`) REFERENCES `evenement` (`id_evenement`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Étape 2 - Insertion sécurisée des tickets (exemple pour 5 tickets)
INSERT INTO `ticket` (`code_unique`, `nom_complet`, `email`, `quantite`, `prix_personne`, `prix_total`, `statut`, `utilise`, `id_evenement`) VALUES
('TCK-2025-1A2B3', 'Marie Dupont', 'marie@mail.com', 2, 120.00, 240.00, 'Payé', 0, 1),
('TCK-2025-4C5D6', 'Jean Martin', 'jean@mail.com', 1, 80.00, 80.00, 'Payé', 1, 2),
('TCK-2025-7E8F9', 'Lucie Leroy', 'lucie@mail.com', 4, 75.00, 300.00, 'Annulé', 0, 3),
('TCK-2025-GHIJ1', 'Paul Dubois', 'paul@mail.com', 2, 150.00, 300.00, 'En attente', 0, 4),
('TCK-2025-KLMN2', 'Sophie Lambert', 'sophie@mail.com', 3, 65.00, 195.00, 'Payé', 0, 5);