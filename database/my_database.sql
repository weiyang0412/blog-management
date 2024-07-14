-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
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

-- Dumping structure for table my_database.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table my_database.posts: ~9 rows (approximately)
INSERT INTO `posts` (`id`, `user_id`, `category`, `title`, `excerpt`, `body`, `thumbnail`, `created_at`, `updated_at`) VALUES
	(3, 2, 'lifestyle', 'POST 1', 'POST 1', 'POST 8', '6689582f6d170_dreamstime_l_124110584.jpg', '2024-07-06 04:40:04', '2024-07-06 15:57:08'),
	(4, 2, 'food', 'POST 2', 'POST 2', 'POST 2', '6688d86ee8ca9_Dollarphotoclub_76404227-copy.jpg', '2024-07-06 05:38:54', '2024-07-06 06:32:36'),
	(6, 2, 'food', 'POST 3', 'POST 3', 'POST 3', '66895846f09c4_dreamstime_l_124110584.jpg', '2024-07-06 11:52:07', '2024-07-06 14:44:22'),
	(7, 2, 'food', 'POST 4', 'POST 4', 'POST 4', '668930b658cc8_Dollarphotoclub_76404227-copy.jpg', '2024-07-06 11:55:34', '2024-07-06 12:28:37'),
	(8, 2, 'food', 'POST 5', 'POST 5', 'POST 5', '6689393928a65_Dollarphotoclub_76404227-copy.jpg', '2024-07-06 12:31:53', '2024-07-06 12:31:53'),
	(9, 2, 'food', 'POST 6', 'POST 6', 'POST 6', '668947fe52e89_image2.jpg', '2024-07-06 13:34:54', '2024-07-06 13:34:54'),
	(10, 2, 'food', 'POST 7', 'POST 7', 'POST 7', '66895c871bee9_dreamstime_l_124110584.jpg', '2024-07-06 14:16:32', '2024-07-06 15:02:31'),
	(11, 2, 'lifestyle', 'POST 8', 'POST 8', 'public function getPosts(Request $request, Response $response, $args)     {         try {             $conn = $this->db->connect();             $sql = "SELECT * FROM posts";             $stmt = $conn->prepare($sql);             $stmt->execute();             $result = $stmt->fetchAll(\\PDO::FETCH_ASSOC);             $payload = json_encode($result);             $response->getBody()->write($payload);             return $response->withHeader(\'Content-Type\', \'application/json\');         } catch (PDOException $e) {             $error = ["error" => "Error: " . $e->getMessage()];             $payload = json_encode($error);             $response->getBody()->write($payload);             return $response->withHeader(\'Content-Type\', \'application/json\')->withStatus(500);         }     }', '6689635be0419_image4.png', '2024-07-06 15:31:39', '2024-07-07 11:47:11'),
	(12, 2, 'food', 'POST 9', 'POST 9', 'POST 9', '668aa9ed8fd42_Dollarphotoclub_76404227-copy.jpg', '2024-07-07 14:45:01', '2024-07-07 14:45:01');

-- Dumping structure for table my_database.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table my_database.users: ~5 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
	(1, 'admin', 'admin123@gmail.com', '$2y$10$.xGErAmjiTeSUjUvqqGt9.vLoLZvIH73xuIHVAZFEDzOKlQMYUhDq', 'admin'),
	(2, 'weiyang', 'weiyang.wyc@gmail.com', '$2y$10$FrNBnjTVZcp5tOZekIjYLO7r2yALjQ7golzkkTK4L55dFqkqT7azq', 'admin'),
	(4, 'test', 'test@gmail.com', '$2y$10$QHmysNwvDHAKlzeAhbOq7.pw19r1L7sCTvaohTvTl.MCL7GAXaa2a', 'teacher'),
	(6, 'meimei', 'meimei@gmail.com', '$2y$10$sI7NlkpFydWiQAzr39XZxO.h164JbUdZw1D5TpNhFf8tGjIgXQE7.', 'teacher'),
	(7, 'test1', 'test1@gmail.com', '$2y$10$ffcSdlLsf05pm71hdwQViuw8/qL5PaEBbGkhdEsejF4.SDgmTxYIK', 'participant');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
