-- Creazione database
CREATE DATABASE IF NOT EXISTS gestione_centro_sportivo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestione_centro_sportivo;

-- Tabella utenti
CREATE TABLE utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NULL,
    ruolo ENUM('gestore', 'atleta') NOT NULL,
    skill_score INT DEFAULT 0,
    google_id VARCHAR(255) NULL
);

-- Tabella centri sportivi
CREATE TABLE centri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    indirizzo VARCHAR(255) NOT NULL,
    citta VARCHAR(100) NOT NULL,
    id_gestore INT NOT NULL,
    FOREIGN KEY (id_gestore) REFERENCES utenti(id) ON DELETE CASCADE
);

-- Tabella proposte di gioco
CREATE TABLE proposte (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(100) NOT NULL,
    descrizione TEXT,
    data_evento DATETIME NOT NULL,
    id_centro INT NOT NULL,
    max_partecipanti INT DEFAULT 10,
    FOREIGN KEY (id_centro) REFERENCES centri(id) ON DELETE CASCADE
);

-- Tabella prenotazioni
CREATE TABLE prenotazioni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_proposta INT NOT NULL,
    id_utente INT NOT NULL,
    data_prenotazione DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_proposta) REFERENCES proposte(id) ON DELETE CASCADE,
    FOREIGN KEY (id_utente) REFERENCES utenti(id) ON DELETE CASCADE
);

-- Inserimento utenti di esempio
INSERT INTO utenti (nome, email, password, ruolo, skill_score) VALUES
('Admin Centro', 'admin@centrosportivo.it', '$2y$10$vjd2F5tFu3XZ1s2eQOY9Ge7McmtKLVoTHTwC9tfwMdV1EJTb6BhPO', 'gestore', 150),
('Atleta Uno', 'atleta1@esempio.it', '$2y$10$vjd2F5tFu3XZ1s2eQOY9Ge7McmtKLVoTHTwC9tfwMdV1EJTb6BhPO', 'atleta', 80),
('Atleta Due', 'atleta2@esempio.it', '$2y$10$vjd2F5tFu3XZ1s2eQOY9Ge7McmtKLVoTHTwC9tfwMdV1EJTb6BhPO', 'atleta', 90);

-- Inserimento centro sportivo
INSERT INTO centri (nome, indirizzo, citta, id_gestore) VALUES
('Centro Sportivo Primavera', 'Via dello Sport 1', 'Roma', 1);

-- Inserimento proposte di gioco
INSERT INTO proposte (titolo, descrizione, data_evento, id_centro, max_partecipanti) VALUES
('Partita di calcetto', 'Giochiamo 5 contro 5', '2025-05-10 18:00:00', 1, 10),
('Allenamento basket', 'Allenamento amatoriale', '2025-05-12 17:00:00', 1, 12);

-- Inserimento prenotazione di esempio
INSERT INTO prenotazioni (id_proposta, id_utente) VALUES
(1, 2);

