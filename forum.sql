-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour forum
CREATE DATABASE IF NOT EXISTS `forum` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `forum`;

-- Listage de la structure de table forum. category
CREATE TABLE IF NOT EXISTS `category` (
  `id_category` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table forum.category : ~13 rows (environ)
INSERT INTO `category` (`id_category`, `name`) VALUES
	(1, 'Annonces et Informations'),
	(2, 'Présentations'),
	(3, 'Discussions Générales'),
	(4, 'Loisirs et Divertissement'),
	(5, 'Art et Créativité'),
	(6, 'Technologie et Informatique'),
	(7, 'Vie Professionnelle'),
	(8, 'Vie Pratique'),
	(9, 'Santé et Bien-être'),
	(10, 'Études et Enseignement'),
	(11, 'Langues et Cultures'),
	(12, 'Écologie et Environnement'),
	(14, 'Divers');

-- Listage de la structure de table forum. datasencrypted
CREATE TABLE IF NOT EXISTS `datasencrypted` (
  `id` int NOT NULL AUTO_INCREMENT,
  `encryptedData` longtext COLLATE utf8mb4_general_ci NOT NULL,
  `iv` varbinary(16) NOT NULL,
  `user_id` int DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tokenValidity` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `datasencrypted_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table forum.datasencrypted : ~0 rows (environ)

-- Listage de la structure de table forum. post
CREATE TABLE IF NOT EXISTS `post` (
  `id_post` int NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `dateCreation` datetime NOT NULL,
  `topic_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id_post`) USING BTREE,
  KEY `topic_id` (`topic_id`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `topic` (`id_topic`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table forum.post : ~40 rows (environ)
INSERT INTO `post` (`id_post`, `content`, `dateCreation`, `topic_id`, `user_id`) VALUES
	(33, 'Sunt accusamus vel ad nemo et ut quibusdam iusto.', '2002-11-02 22:15:13', 12, 1),
	(34, 'Quam mollitia esse ratione ad amet.', '1988-02-10 11:34:15', 12, 2),
	(35, 'Magni soluta nobis molestias corporis qui et dolore qui.', '1998-03-13 16:12:45', 12, 3),
	(36, 'Iure delectus nemo corrupti vero accusamus temporibus.', '1972-08-26 14:27:10', 12, 4),
	(37, 'Laborum dolores debitis aut non nihil provident dolores aut.', '1987-05-27 05:58:27', 13, 5),
	(38, 'Id dolor illum est sit magnam omnis nisi.', '2018-09-26 04:38:53', 13, 1),
	(39, 'Non saepe et et fuga aut sed eos molestiae.', '1994-06-03 18:19:48', 13, 2),
	(40, 'Nesciunt voluptatem minus perferendis qui et repellat.', '1989-12-26 16:14:49', 13, 3),
	(41, 'Consequatur repellendus deleniti quia illo non soluta harum.', '2011-07-30 12:08:05', 14, 4),
	(42, 'Rerum sint consequatur ut itaque.', '1981-09-13 19:15:02', 14, 5),
	(43, 'Sint autem atque et fuga tempore et laborum.', '2020-03-10 07:57:46', 14, 1),
	(44, 'Labore non omnis sunt officiis in iste ex.', '2007-07-04 11:28:48', 14, 2),
	(45, 'Qui qui nihil nesciunt numquam accusantium.', '2009-01-18 20:33:29', 15, 3),
	(46, 'Eum aspernatur odio distinctio et culpa.', '2015-10-31 13:04:39', 15, 4),
	(47, 'Eaque id voluptate reiciendis iure aut nesciunt assumenda.', '1990-12-12 05:30:09', 15, 5),
	(48, 'Beatae sint perspiciatis nobis omnis.', '1982-05-07 04:52:30', 15, 1),
	(49, 'Neque vel quos doloribus sed.', '1974-12-16 10:10:11', 16, 2),
	(50, 'Qui culpa maiores tempore deleniti id nihil similique eos.', '2002-07-15 10:39:36', 16, 3),
	(51, 'Et ex accusamus quidem vitae eligendi doloribus.', '1994-11-13 02:34:52', 16, 4),
	(52, 'Quae enim dolorem laborum qui.', '1978-11-04 16:48:31', 16, 5),
	(53, 'Accusamus consectetur molestiae excepturi dolores delectus.', '1977-11-28 14:48:13', 17, 1),
	(54, 'Accusamus repellat aperiam blanditiis.', '1981-03-03 06:05:04', 17, 2),
	(55, 'Suscipit dolore vitae sit dignissimos.', '2023-04-07 15:52:48', 17, 3),
	(56, 'Fuga repellat placeat repellat et animi iste laboriosam.', '1993-12-18 06:53:51', 17, 4),
	(57, 'Quos ut rerum maiores accusantium qui omnis ut tenetur.', '2016-04-26 15:46:50', 18, 5),
	(58, 'Cupiditate consectetur architecto voluptates accusantium corporis nihil.', '1993-02-20 18:31:42', 18, 1),
	(59, 'Accusantium quod molestiae quaerat unde omnis neque.', '2009-02-01 18:07:09', 18, 2),
	(60, 'Non assumenda non doloremque labore odio nulla.', '1980-09-15 03:29:08', 18, 3),
	(61, 'Qui deserunt nihil aut molestiae iste dolor quod.', '1986-10-05 02:36:32', 19, 4),
	(62, 'Quia nobis quasi est doloremque at eveniet.', '2020-11-20 03:45:57', 19, 5),
	(63, 'Ipsa recusandae itaque doloremque harum.', '1972-12-18 14:03:07', 19, 1),
	(64, 'Aut ut et sint minima tempora omnis libero.', '1986-12-19 21:40:41', 19, 2),
	(65, 'Aliquid velit consectetur sit enim asperiores et ut.', '1996-08-04 12:41:54', 20, 3),
	(66, 'Quia necessitatibus perspiciatis commodi.', '2018-04-26 17:04:12', 20, 4),
	(67, 'Qui ut repellat est minus delectus velit.', '2002-07-12 10:07:49', 20, 5),
	(68, 'Rem maxime ut expedita dolorum.', '2012-12-01 02:42:26', 20, 1),
	(69, 'Nobis laborum qui autem odio.', '1978-10-22 09:06:01', 21, 2),
	(70, 'Et porro et voluptatem dolor enim quis qui.', '1977-03-19 16:11:16', 21, 3),
	(71, 'Aut assumenda consequatur illum dolorum reprehenderit.', '2004-04-09 23:10:00', 21, 4),
	(72, 'Voluptas quas et aperiam harum soluta.', '2018-11-07 04:05:15', 21, 5),
	(73, 'reponse test', '2024-05-06 19:08:16', 19, 17);

-- Listage de la structure de table forum. topic
CREATE TABLE IF NOT EXISTS `topic` (
  `id_topic` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dateCreation` datetime NOT NULL,
  `category_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id_topic`),
  KEY `category_id` (`category_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `topic_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id_category`),
  CONSTRAINT `topic_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table forum.topic : ~10 rows (environ)
INSERT INTO `topic` (`id_topic`, `title`, `dateCreation`, `category_id`, `user_id`) VALUES
	(12, 'Sint ipsa voluptatem culpa ut et necessitatibus voluptatem.', '2001-03-22 15:07:44', 2, 5),
	(13, 'Ad dolorem nisi fugiat iure quo dolorem aliquid ex.', '2004-02-28 10:28:27', 1, 1),
	(14, 'Vero accusamus cumque voluptatem rerum modi.', '1994-01-10 02:45:54', 2, 1),
	(15, 'Inventore eius animi esse sit aut illo eos sed.', '2009-07-25 18:40:16', 7, 5),
	(16, 'Quasi qui et rem aliquid voluptas vel.', '1981-11-13 06:43:48', 8, 5),
	(17, 'Sequi quisquam mollitia molestiae atque autem illum.', '2006-01-25 07:42:40', 10, 4),
	(18, 'ceci est encore un topic', '2017-05-08 06:53:46', 7, 3),
	(19, 'ceci est un topic ', '2018-12-06 00:16:26', 8, 2),
	(20, 'Sed enim rerum suscipit distinctio consequatur voluptas rerum.', '1995-10-01 18:08:29', 7, 1),
	(21, 'Qui autem velit at consequuntur nemo delectus.', '2015-11-05 11:27:22', 8, 2);

-- Listage de la structure de table forum. user
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tokenValidity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dateRegister` datetime NOT NULL,
  `role` json NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table forum.user : ~6 rows (environ)
INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `token`, `tokenValidity`, `dateRegister`, `role`) VALUES
	(1, 'USER', '$2y$10$H9rQRILvS6cvmnWbMvPx5uT2tKB2EdVl9HzFgUUaTHAjOpbgKBUii', 'user@gmail.com', NULL, '', '2024-04-30 03:19:58', '["ROLE_USER"]'),
	(2, 'Anthony', '$2y$10$KIW6cwQuDkNebwFKrVgMoOicp/TVWs9dmRBxtif.Ymhptwwqh/ad.', 'anthony@gmail.com', NULL, '', '2024-04-30 09:33:53', '["ROLE_USER", "ROLE_EDITOR", "ROLE_ADMIN"]'),
	(3, 'ADMIN', '$2y$10$RGWOi0OEkQ0w8Vsw6RLlC.9jXruxpZwUI1cvvGrQV4ik.fF3SB5pS', 'admin@gmail.com', NULL, '', '2024-05-01 14:53:35', '["ROLE_USER", "ROLE_CONTRIBUTOR"]'),
	(4, 'EDITOR1', '$2y$10$6.6P.xVHP/6x318RkIHL4unZD6Itq23z9Kn0iBld/NdgMSaLCySS2', 'editor1@gmail.com', NULL, '', '2024-05-01 15:56:17', '["ROLE_USER", "ROLE_EDITOR"]'),
	(5, 'EDITOR', '$2y$10$Z7VoC3eb0St44VeRnM/oOOc3h9Ai/YnwaaEiSWUjOI4FbPZ/o10pi', 'editor@gmail.com', NULL, '', '2024-05-01 15:57:35', '["ROLE_USER", "ROLE_EDITOR"]'),
	(17, 'rootroot', '$2y$10$M.kUGMhoJ7oTR6Evq95dsOoYvfsHeWCla9059E2MVJriyh/8VezIK', 'root@gmail.com', NULL, '2024-06-04 15:31:21', '2024-05-05 15:01:27', '["ROLE_USER"]');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
