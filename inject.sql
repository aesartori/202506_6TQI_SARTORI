-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 06, 2025 alle 18:55
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_manager_v1`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `artiste`
--

CREATE TABLE `artiste` (
  `id_artiste` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `artiste`
--

INSERT INTO `artiste` (`id_artiste`, `nom`, `url`, `photo`) VALUES
(1, 'Les Electrons Libres', 'https://electronslibres.com', NULL),
(2, 'DJ Pulse', 'https://djpulse.be', NULL),
(3, 'Marie Martin', 'https://mariemartin.com', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `evenement`
--

CREATE TABLE `evenement` (
  `id_evenement` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date_heure` datetime NOT NULL,
  `prix` decimal(10,2) DEFAULT 0.00,
  `id_venue` int(11) DEFAULT NULL,
  `id_artiste` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `evenement`
--

INSERT INTO `evenement` (`id_evenement`, `titre`, `description`, `date_heure`, `prix`, `id_venue`, `id_artiste`, `image`) VALUES
(1, 'Concert Rock', 'Une soirée rock exceptionnelle', '2025-07-15 20:00:00', 30.00, 1, 1, NULL),
(2, 'Festival Électro', 'Le meilleur de la musique électronique', '2025-08-10 19:00:00', 40.00, 2, 2, NULL),
(3, 'Showcase Acoustique', 'Un moment musical intime', '2025-09-05 20:30:00', 25.00, 3, 3, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `ticket`
--

CREATE TABLE `ticket` (
  `id_ticket` int(11) NOT NULL,
  `code_unique` varchar(20) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1,
  `prix_personne` decimal(10,2) NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `date_reservation` datetime DEFAULT current_timestamp(),
  `statut` enum('En attente','Payé','Annulé') DEFAULT 'En attente',
  `utilise` tinyint(1) DEFAULT 0,
  `id_evenement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ticket`
--

INSERT INTO `ticket` (`id_ticket`, `code_unique`, `nom_complet`, `email`, `quantite`, `prix_personne`, `prix_total`, `date_reservation`, `statut`, `utilise`, `id_evenement`) VALUES
(1, 'TCK-2025-ABC123', 'Jean Dupont', 'jean@example.com', 2, 30.00, 60.00, '2025-06-06 18:47:08', 'Payé', 0, 1),
(2, 'TCK-2025-DEF456', 'Marie Dubois', 'marie@example.com', 1, 40.00, 40.00, '2025-06-06 18:47:08', 'En attente', 0, 2),
(3, 'TCK-2025-GHI789', 'Pierre Martin', 'pierre@example.com', 3, 25.00, 75.00, '2025-06-06 18:47:08', 'Payé', 0, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `venue`
--

CREATE TABLE `venue` (
  `id_venue` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `venue`
--

INSERT INTO `venue` (`id_venue`, `nom`, `type`, `adresse`, `url`, `photo`) VALUES
(1, 'Palais 12', 'Salle de concert', 'Avenue de Miramar, 1020 Bruxelles', 'https://www.palais12.be', NULL),
(2, 'Forest National', 'Arena', 'Avenue Victor Rousseau 208, 1190 Forest', 'https://www.forest-national.be', NULL),
(3, 'Ancienne Belgique', 'Salle de concert', 'Boulevard Anspach 110, 1000 Bruxelles', 'https://www.abconcerts.be', NULL);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `artiste`
--
ALTER TABLE `artiste`
  ADD PRIMARY KEY (`id_artiste`);

--
-- Indici per le tabelle `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`id_evenement`),
  ADD KEY `id_venue` (`id_venue`),
  ADD KEY `id_artiste` (`id_artiste`);

--
-- Indici per le tabelle `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id_ticket`),
  ADD UNIQUE KEY `code_unique` (`code_unique`),
  ADD KEY `id_evenement` (`id_evenement`);

--
-- Indici per le tabelle `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`id_venue`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `artiste`
--
ALTER TABLE `artiste`
  MODIFY `id_artiste` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id_evenement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `venue`
--
ALTER TABLE `venue`
  MODIFY `id_venue` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `evenement`
--
ALTER TABLE `evenement`
  ADD CONSTRAINT `evenement_ibfk_1` FOREIGN KEY (`id_venue`) REFERENCES `venue` (`id_venue`),
  ADD CONSTRAINT `evenement_ibfk_2` FOREIGN KEY (`id_artiste`) REFERENCES `artiste` (`id_artiste`);

--
-- Limiti per la tabella `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`id_evenement`) REFERENCES `evenement` (`id_evenement`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
