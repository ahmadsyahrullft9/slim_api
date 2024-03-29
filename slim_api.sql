/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `slim_api` /*!40100 DEFAULT CHARACTER SET armscii8 COLLATE armscii8_bin */;
USE `slim_api`;

CREATE TABLE IF NOT EXISTS `mstuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE armscii8_bin DEFAULT NULL,
  `alamat` text COLLATE armscii8_bin DEFAULT NULL,
  `umur` int(11) DEFAULT 0,
  `tgl_lahir` date DEFAULT curdate(),
  `gender` enum('L','P') COLLATE armscii8_bin DEFAULT 'L',
  `username` varchar(50) COLLATE armscii8_bin DEFAULT NULL,
  `userpass` text COLLATE armscii8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

CREATE TABLE IF NOT EXISTS `user_key` (
  `user_id` varchar(255) COLLATE armscii8_bin NOT NULL,
  `user_email` varchar(120) COLLATE armscii8_bin DEFAULT NULL,
  `user_password` text COLLATE armscii8_bin DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
