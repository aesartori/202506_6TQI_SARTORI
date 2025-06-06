CREATE DATABASE event_manager_v1;
USE event_manager_v1;

-- Table des événements
CREATE TABLE evenement (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME,
    lieu VARCHAR(255),
    capacite INT,
    prix DECIMAL(10,2),
    statut ENUM('planifie', 'en_cours', 'termine', 'annule') DEFAULT 'planifie',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des artistes
CREATE TABLE artiste (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    specialite VARCHAR(255),
    email VARCHAR(255),
    telephone VARCHAR(20),
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des tâches
CREATE TABLE tache (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    date_echeance DATETIME,
    statut ENUM('a_faire', 'en_cours', 'termine') DEFAULT 'a_faire',
    priorite ENUM('basse', 'moyenne', 'haute') DEFAULT 'moyenne',
    evenement_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (evenement_id) REFERENCES evenement(id) ON DELETE CASCADE
);

-- Table de liaison événements-artistes
CREATE TABLE evenement_artiste (
    id INT PRIMARY KEY AUTO_INCREMENT,
    evenement_id INT NOT NULL,
    artiste_id INT NOT NULL,
    role VARCHAR(255),
    FOREIGN KEY (evenement_id) REFERENCES evenement(id) ON DELETE CASCADE,
    FOREIGN KEY (artiste_id) REFERENCES artiste(id) ON DELETE CASCADE
);

-- Insertion de données d'exemple
INSERT INTO evenement (nom, description, date_debut, date_fin, lieu, capacite, prix) VALUES
('Concert Jazz Festival', 'Festival de jazz annuel', '2025-07-15 20:00:00', '2025-07-15 23:30:00', 'Salle Pleyel', 2000, 45.00),
('Soirée Rock', 'Concert de rock alternatif', '2025-08-20 21:00:00', '2025-08-21 01:00:00', 'Zénith', 5000, 35.00);

INSERT INTO artiste (nom, prenom, specialite, email) VALUES
('Dupont', 'Pierre', 'Saxophone', 'pierre.dupont@email.com'),
('Martin', 'Sophie', 'Guitare', 'sophie.martin@email.com');

INSERT INTO tache (titre, description, date_echeance, evenement_id) VALUES
('Réservation salle', 'Confirmer la réservation de la salle', '2025-07-01 12:00:00', 1),
('Sound check', 'Test du matériel audio', '2025-07-15 18:00:00', 1);