CREATE DATABASE VENERE;

CREATE TABLE UTENTE(
    NomeUtente VARCHAR(255) PRIMARY KEY,
    Email VARCHAR(255) UNIQUE NOT NULL,
    PASSWORD VARCHAR(255),
    Nome VARCHAR(255),
    Cognome VARCHAR(255)
    TipoUtente ENUM('MOD', 'USER') DEFAULT 'USER',
    Stato ENUM('ATTIVO', 'SOSPESO', 'BANNATO') DEFAULT 'ATTIVO'
)ENGINE = InnoDB;

CREATE TABLE CATEGORIAINTERESSE(
    IDCategoria INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(255)	
)ENGINE = InnoDB;

INSERT INTO CATEGORIAINTERESSE(Nome) VALUES ("Stand-up Comedies"), ("Opere Teatrali"), ("Concerti"), ("Mostre Artistiche"), ("Eventi Sportivi"), ("Eventi per Bambini"), ("Eventi Culturali");

CREATE TABLE PREFERENZA(
    Utente VARCHAR(255),
    Categoria INT,
    PRIMARY KEY(Utente, Categoria),
    FOREIGN KEY (Utente) REFERENCES UTENTE(NomeUtente),
    FOREIGN KEY (Categoria) REFERENCES CATEGORIAINTERESSE(IDCategoria)
)ENGINE = InnoDB;

CREATE TABLE EVENTO(
    IDEvento INT AUTO_INCREMENT PRIMARY KEY,
    Titolo VARCHAR(255),
    DataEvento DATE,
    OraEvento TIME,
    Luogo VARCHAR(255),
    Categoria INT,
    Descrizione VARCHAR(255),
    Immagine VARCHAR(255),
    Stato ENUM('IN ATTESA', 'ACCETTATO') DEFAULT 'IN ATTESA',
    FOREIGN KEY(Categoria) REFERENCES CATEGORIAINTERESSE(IDCategoria)
)ENGINE =  InnoDB;

INSERT INTO EVENTO (Titolo, DataEvento, OraEvento, Luogo, Categoria, Descrizione, Immagine, Stato) 
VALUES  ('Risate in Piedi', '2025-05-10', '21:00:00', 'Teatro Centrale, Roma', 1, 'Serata di stand-up con comici emergenti.', 'images/eventi/evento1.png', 'ACCETTATO'),
        ('La Traviata', '2025-06-01', '20:30:00', 'Teatro alla Scala, Milano', 2, 'Opera teatrale classica di Verdi.', 'images/eventi/evento2.png', 'ACCETTATO'),
        ('Rock in Park', '2025-07-15', '19:00:00', 'Parco Nord, Milano', 3, 'Concerto rock con band internazionali.', 'images/eventi/evento3.png', 'ACCETTATO'),
        ('Visioni Astratte', '2025-05-22', '10:00:00', 'Museo d’Arte Moderna, Torino', 4, 'Mostra di pittura astratta contemporanea.', 'images/eventi/evento4.png', 'ACCETTATO'),
        ('Maratona di Roma', '2025-04-28', '08:30:00', 'Centro Storico, Roma', 5, 'Evento sportivo annuale aperto a tutti.', 'images/eventi/evento5.png', 'ACCETTATO'),
        ('Il Mondo di Peppa Pig', '2025-05-05', '17:00:00', 'Palazzetto dello Sport, Napoli', 6, 'Spettacolo per bambini con personaggi animati.', 'images/eventi/evento6.png', 'ACCETTATO'),
        ('Festival delle Culture', '2025-06-18', '16:00:00', 'Piazza del Popolo, Roma', 7, 'Eventi e spettacoli dedicati a culture diverse.', 'images/eventi/evento7.png', 'ACCETTATO'),
        ('Comicità & Co.', '2025-04-30', '21:30:00', 'Auditorium, Firenze', 1, 'Show comico con ospiti speciali.', 'images/eventi/evento8.png', 'ACCETTATO'),
        ('Notte al Museo', '2025-06-10', '22:00:00', 'Museo Egizio, Torino', 4, 'Apertura notturna con visite guidate.', 'images/eventi/evento9.png', 'ACCETTATO'),
        ('Jazz sotto le Stelle', '2025-07-20', '20:00:00', 'Giardini Reali, Venezia', 3, 'Concerto jazz all’aperto.', 'images/eventi/evento10.png', 'ACCETTATO');

CREATE TABLE COMMENTO(
    IDCommento INT PRIMARY KEY,
    Evento INT,
    Utente VARCHAR(255),
    Descrizione VARCHAR(255),
    Voto INT,
    Stato ENUM('IN ATTESA', 'ACCETTATO') DEFAULT 'IN ATTESA',
    FOREIGN KEY(Evento) REFERENCES EVENTO(IDEvento),
    FOREIGN KEY(Utente) REFERENCES UTENTE(NomeUtente)
)ENGINE = InnoDB;


CREATE TABLE ARTISTA(
    IDArtista INT PRIMARY KEY,
    Nome VARCHAR(255),
    Cognome VARCHAR(255)
)ENGINE = InnoDB;

INSERT INTO ARTISTA (IDArtista, Nome, Cognome) 
VALUES  (1, 'Luca', 'Bartolini'),
        (2, 'Giulia', 'Rossi'),
        (3, 'Marco', 'Santoro'),
        (4, 'Alessia', 'Bianchi'),
        (5, 'Davide', 'Greco'),
        (6, 'Francesca', 'Moretti'),
        (7, 'Samuel', 'Diop'),
        (8, 'Federica', 'Serra'),
        (9, 'Lorenzo', 'Fontana'),
        (10, 'Elena', 'Vitale');

CREATE TABLE PARTECIPAZIONE(
    Evento INT,
    Artista INT,
    PRIMARY KEY(Evento, Artista),
    FOREIGN KEY(Evento) REFERENCES EVENTO(IDEvento),
    FOREIGN KEY(Artista) REFERENCES ARTISTA(IDArtista)
)ENGINE = InnoDB;

INSERT INTO PARTECIPAZIONE (Evento, Artista) 
VALUES  (1, 1),
        (2, 2),
        (3, 3),
        (4, 4),
        (5, 5),
        (6, 6),
        (7, 7),
        (8, 8),
        (9, 9),
        (10, 10);