-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for laporwarga
CREATE DATABASE IF NOT EXISTS `laporwarga` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `laporwarga`;

-- Dumping structure for table laporwarga.pengaduan
CREATE TABLE IF NOT EXISTS `pengaduan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_pelapor` varchar(100) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `isi_pengaduan` text NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `status` varchar(30) DEFAULT 'Menunggu',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table laporwarga.pengaduan: ~1 rows (approximately)
INSERT INTO `pengaduan` (`id`, `nama_pelapor`, `nik`, `no_telepon`, `kategori`, `judul`, `isi_pengaduan`, `lokasi`, `tanggal`, `status`, `created_at`) VALUES
	(2, 'Anis', '1012415333333333', '0888888888', 'Keamanan', 'pencurian', 'ada curian motor', 'home', '2026-10-30', 'Menunggu', '2026-10-30 17:05:53');

-- Dumping structure for table laporwarga.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table laporwarga.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
	(1, 'admin', '$2y$10$zjlt4d8VEmbYbrrY.9MFeuLUtpHUoXBw8h0kbnQeqaGUShKzeLn5S', '2026-10-30 16:38:41');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
