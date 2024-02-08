-- Création de la table des messages
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Création de la table des catégories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

-- Création de la table des mots-clés
CREATE TABLE mots_cles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mot_cle VARCHAR(255) NOT NULL
);

-- Table de liaison entre les messages et les catégories (relation many-to-many)
CREATE TABLE messages_categories (
    id_message INT,
    id_categorie INT,
    PRIMARY KEY (id_message, id_categorie),
    FOREIGN KEY (id_message) REFERENCES messages(id),
    FOREIGN KEY (id_categorie) REFERENCES categories(id)
);

-- Table de liaison entre les messages et les mots-clés (relation many-to-many)
CREATE TABLE messages_mots_cles (
    id_message INT,
    id_mot_cle INT,
    PRIMARY KEY (id_message, id_mot_cle),
    FOREIGN KEY (id_message) REFERENCES messages(id),
    FOREIGN KEY (id_mot_cle) REFERENCES mots_cles(id)
);

-- Création de la table des utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL, -- Stockage sécurisé du mot de passe est recommandé
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table de liaison entre les messages et les utilisateurs (relation many-to-many)
CREATE TABLE messages_utilisateurs (
    id_message INT,
    id_utilisateur INT,
    PRIMARY KEY (id_message, id_utilisateur),
    FOREIGN KEY (id_message) REFERENCES messages(id),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id)
);
