-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 16 sep. 2025 à 13:42
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `biblio_base`
--

-- --------------------------------------------------------

--
-- Structure de la table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `publisher` varchar(250) DEFAULT NULL,
  `publication_date` date DEFAULT NULL,
  `status` enum('DISPONIBLE','INDISPONIBLE') DEFAULT 'DISPONIBLE',
  `description` text DEFAULT NULL,
  `cover_image` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `borrow_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `category`, `isbn`, `publisher`, `publication_date`, `status`, `description`, `cover_image`, `created_at`, `borrow_count`) VALUES
(1, 'Le mystère de l\'étoile', 'Jean Dupont', 'Science', NULL, NULL, NULL, 'DISPONIBLE', 'Un roman captivant sur les secrets de l\'univers.', 'https://cdn.pixabay.com/photo/2018/01/17/18/43/book-3088775_1280.jpg', '2025-09-12 18:15:44', 15),
(2, 'Au coeur de la forêt', 'Marie Curie', 'Nature', NULL, NULL, NULL, 'INDISPONIBLE', 'Une exploration épique d\'une jungle inconnue.', 'https://cdn.pixabay.com/photo/2014/08/17/16/33/notebook-420011_640.jpg', '2025-09-12 18:15:44', 1),
(3, 'Les oubliés de Montmartre', 'Clément Durand', 'Littérature', NULL, NULL, NULL, 'DISPONIBLE', 'Un roman historique plein de mystères à Paris.', 'https://cdn.pixabay.com/photo/2022/12/05/22/08/open-book-7637805_640.jpg', '2025-09-12 18:15:44', 12),
(4, 'L\'âme du printemps', 'Sophie Martin', 'Poésie', NULL, NULL, NULL, 'INDISPONIBLE', 'Une série de poèmes inspirés par la nature.', 'https://cdn.pixabay.com/photo/2017/06/01/16/42/book-2363912_640.jpg', '2025-09-12 18:15:44', 1),
(5, 'Symphonie des mots', 'Luc Moreau', 'Musique', NULL, NULL, NULL, 'DISPONIBLE', 'Un recueil lyrique de vers sur la musique et l\'âme.', 'https://cdn.pixabay.com/photo/2018/07/01/20/01/music-3510326_640.jpg', '2025-09-12 18:15:44', 8),
(6, 'Au-delà de la ville', 'Isabelle Berger', 'Science-fiction', NULL, NULL, NULL, 'DISPONIBLE', 'Une aventure futuriste dans des métropoles avancées.', 'https://cdn.pixabay.com/photo/2024/11/13/08/47/city-9193823_640.jpg', '2025-09-12 18:15:44', 1),
(7, 'Jardin secret', 'René Lavoisier', 'Romantisme', NULL, NULL, NULL, 'INDISPONIBLE', 'Une histoire d\'amour et de découverte au cœur d\'un jardin enchanté.', 'https://cdn.pixabay.com/photo/2019/06/03/22/09/books-4250085_640.jpg', '2025-09-12 18:15:44', 6),
(8, 'De la terre au ciel', 'Céline Dubois', 'Histoire', NULL, NULL, NULL, 'DISPONIBLE', 'Un voyage historique retraçant les grandes explorations.', 'https://cdn.pixabay.com/photo/2015/11/05/18/59/book-cover-1024644_640.jpg', '2025-09-12 18:15:44', 1),
(9, 'Les couleurs de la vie', 'Mathieu Rousseau', 'Art', NULL, NULL, NULL, 'DISPONIBLE', 'Un roman inspirant liant art et vie quotidienne.', 'https://cdn.pixabay.com/photo/2015/12/04/17/06/notebook-1076812_640.jpg', '2025-09-12 18:15:44', 1),
(10, 'Rêves d\'avenir', 'Emma Fournier', 'Philosophie', NULL, NULL, NULL, 'INDISPONIBLE', 'Un ensemble de réflexions sur l\'humanité et son avenir.', 'https://cdn.pixabay.com/photo/2024/01/06/15/05/ai-generated-8491555_640.jpg', '2025-09-12 18:15:44', 1),
(11, 'L\'ombre du passé', 'Antoine Lefèvre', 'Policier', NULL, NULL, NULL, 'DISPONIBLE', 'Une enquête troublante sur un cold case non résolu.', 'https://cdn.pixabay.com/photo/2016/03/26/22/21/books-1281581_640.jpg', '2025-09-12 18:15:44', 0),
(12, 'Les murmures du vent', 'Élodie Petit', 'Nature', NULL, NULL, NULL, 'DISPONIBLE', 'Une ode à la beauté sauvage des paysages montagnards.', 'https://cdn.pixabay.com/photo/2015/07/31/11/31/library-868148_640.jpg', '2025-09-12 18:15:44', 0),
(13, 'Algorithmes vivants', 'Dr. Hassan Khan', 'Science', NULL, NULL, NULL, 'INDISPONIBLE', 'Quand l\'IA rencontre la biologie synthétique.', 'https://cdn.pixabay.com/photo/2018/01/31/07/36/technology-3120153_640.jpg', '2025-09-12 18:15:44', 0),
(14, 'Cuisine des terroirs', 'Julie Bonnet', 'Cuisine', NULL, NULL, NULL, 'DISPONIBLE', 'Recettes traditionnelles et histoires gourmandes de France.', 'https://cdn.pixabay.com/photo/2014/09/18/13/22/recipe-451155_640.jpg', '2025-09-12 18:15:44', 0),
(15, 'Le dernier pharaon', 'Marc Thierry', 'Histoire', NULL, NULL, NULL, 'DISPONIBLE', 'La découverte archéologique qui bouleverse l\'égyptologie.', 'https://cdn.pixabay.com/photo/2017/02/01/10/20/egypt-2029286_640.jpg', '2025-09-12 18:15:44', 0),
(16, 'Océans de silence', 'Camille Leroy', 'Aventure', NULL, NULL, NULL, 'INDISPONIBLE', 'Une expédition dans les abysses aux frontières du connu.', 'https://cdn.pixabay.com/photo/2016/11/14/03/06/ocean-1822480_640.jpg', '2025-09-12 18:15:44', 0),
(17, 'Voyage intérieur', 'Philippe Deschamps', 'Philosophie', NULL, NULL, NULL, 'DISPONIBLE', 'Méditations sur la conscience et l\'existence.', 'https://cdn.pixabay.com/photo/2017/01/31/00/09/books-2022464_640.jpg', '2025-09-12 18:15:44', 0),
(18, 'Renaissance', 'Sarah Mekbel', 'Science-fiction', NULL, NULL, NULL, 'DISPONIBLE', 'Une nouvelle humanité naît sur une exoplanète.', 'https://cdn.pixabay.com/photo/2016/06/14/23/01/planet-1456991_640.jpg', '2025-09-12 18:15:44', 0),
(19, 'L\'art du trait', 'Pierre Garnier', 'Art', NULL, NULL, NULL, 'INDISPONIBLE', 'Maîtriser les techniques du dessin académique.', 'https://cdn.pixabay.com/photo/2016/06/14/23/01/sketch-1456990_640.jpg', '2025-09-12 18:15:44', 0),
(20, 'Mélodies cachées', 'Aurélie Dubois', 'Musique', NULL, NULL, NULL, 'DISPONIBLE', 'Le pouvoir thérapeutique des harmonies oubliées.', 'https://cdn.pixabay.com/photo/2018/05/02/19/37/music-3369041_640.jpg', '2025-09-12 18:15:44', 0),
(21, 'Jardins suspendus', 'Thomas Leroux', 'Botanique', NULL, NULL, NULL, 'DISPONIBLE', 'Créer des écosystèmes verticaux en milieu urbain.', 'https://cdn.pixabay.com/photo/2016/11/19/12/14/plants-1838594_640.jpg', '2025-09-12 18:15:44', 0),
(22, 'Échecs et Stratégies', 'Igor Vassiliev', 'Jeux', NULL, NULL, NULL, 'INDISPONIBLE', 'Analyses des plus grandes parties des maîtres.', 'https://cdn.pixabay.com/photo/2016/01/17/09/12/chess-1143479_640.jpg', '2025-09-12 18:15:44', 0),
(23, 'L\'héritage Borgia', 'Maria Ricci', 'Histoire', NULL, NULL, NULL, 'DISPONIBLE', 'Secrets et intrigues de la Renaissance italienne.', 'https://cdn.pixabay.com/photo/2017/08/10/01/35/vatican-2616235_640.jpg', '2025-09-12 18:15:44', 0),
(24, 'Poèmes du crépuscule', 'Lucie Bernard', 'Poésie', NULL, NULL, NULL, 'DISPONIBLE', 'Entre lumière et obscurité, la dualité de l\'âme.', 'https://cdn.pixabay.com/photo/2015/11/19/21/10/glasses-1052010_640.jpg', '2025-09-12 18:15:44', 0),
(25, 'Au nom du code', 'Kevin Roche', 'Programmation', NULL, NULL, NULL, 'INDISPONIBLE', 'Initiation aux langages informatiques par la pratique.', 'https://cdn.pixabay.com/photo/2016/11/19/14/00/code-1839406_640.jpg', '2025-09-12 18:15:44', 0),
(26, 'Les sentiers perdus', 'Nathalie Simon', 'Randonnée', NULL, NULL, NULL, 'DISPONIBLE', 'Guide des chemins mystérieux à travers l\'Europe.', 'https://cdn.pixabay.com/photo/2017/10/10/07/48/hills-2836301_640.jpg', '2025-09-12 18:15:44', 0),
(27, 'L\'alchimiste moderne', 'David Klein', 'Science', NULL, NULL, NULL, 'DISPONIBLE', 'Quand la chimie transforme notre quotidien.', 'https://cdn.pixabay.com/photo/2016/03/05/19/02/chemistry-1238178_640.jpg', '2025-09-12 18:15:44', 0),
(28, 'Destins croisés', 'Émilie Gautier', 'Romance', NULL, NULL, NULL, 'INDISPONIBLE', 'Cinq vies qui s\'entrecroisent dans le Paris des années 20.', 'https://cdn.pixabay.com/photo/2016/09/10/17/18/book-1659717_640.jpg', '2025-09-12 18:15:44', 0),
(29, 'Métamorphoses', 'Charles Dabo', 'Littérature', NULL, NULL, NULL, 'DISPONIBLE', 'Récit initiatique d\'une transformation personnelle.', 'https://cdn.pixabay.com/photo/2015/12/09/17/11/books-1084686_640.jpg', '2025-09-12 18:15:44', 0),
(30, 'Astres et Légendes', 'Fatima Zohra', 'Astronomie', NULL, NULL, NULL, 'DISPONIBLE', 'Mythes cosmologiques à travers les civilisations.', 'https://cdn.pixabay.com/photo/2016/10/22/01/54/constellation-1758963_640.jpg', '2025-09-12 18:15:44', 0);

-- --------------------------------------------------------

--
-- Structure de la table `borrowings`
--

CREATE TABLE `borrowings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` datetime NOT NULL DEFAULT current_timestamp(),
  `return_date` datetime DEFAULT NULL,
  `due_date` datetime NOT NULL,
  `status` enum('emprunté','retourné','en retard') NOT NULL DEFAULT 'emprunté'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `digital_documents`
--

CREATE TABLE `digital_documents` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(500) NOT NULL,
  `cover_image` varchar(500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `category` varchar(100) DEFAULT NULL,
  `publication_date` date DEFAULT NULL,
  `is_free` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `document_access`
--

CREATE TABLE `document_access` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `access_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('card','mobile_money','bank_transfer') DEFAULT 'card',
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `reservation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Demande en cours...','Approuvée','Rejetée','Emprunté','Rendu') DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `pickup_deadline` datetime DEFAULT NULL,
  `return_deadline` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `email` varchar(250) NOT NULL,
  `pass_word` varchar(250) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `npi` varchar(50) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `borrowings`
--
ALTER TABLE `borrowings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Index pour la table `digital_documents`
--
ALTER TABLE `digital_documents`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `document_access`
--
ALTER TABLE `document_access`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `document_id` (`document_id`);

--
-- Index pour la table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `document_id` (`document_id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `borrowings`
--
ALTER TABLE `borrowings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `digital_documents`
--
ALTER TABLE `digital_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `document_access`
--
ALTER TABLE `document_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `borrowings`
--
ALTER TABLE `borrowings`
  ADD CONSTRAINT `borrowings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrowings_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `document_access`
--
ALTER TABLE `document_access`
  ADD CONSTRAINT `document_access_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `document_access_ibfk_2` FOREIGN KEY (`document_id`) REFERENCES `digital_documents` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_ibfk_2` FOREIGN KEY (`document_id`) REFERENCES `digital_documents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
ALTER TABLE users ADD COLUMN status ENUM('active', 'inactive', 'deleted') DEFAULT 'active';
 ALTER TABLE reservations MODIFY id INT(11) NOT NULL AUTO_INCREMENT;