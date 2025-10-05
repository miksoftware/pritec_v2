-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla pritec_v2.clients
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombres del cliente',
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Apellidos del cliente',
  `identification` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de identificación',
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de teléfono',
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Dirección completa',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Correo electrónico',
  `status` enum('active','inactive','deleted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active' COMMENT 'Estado del cliente',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identification` (`identification`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_identification` (`identification`),
  KEY `idx_email` (`email`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de clientes';

-- Volcando datos para la tabla pritec_v2.clients: ~3 rows (aproximadamente)
INSERT INTO `clients` (`id`, `first_name`, `last_name`, `identification`, `phone`, `address`, `email`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Carmen Rosa rogelio', 'González', '66778899', '3134455667', 'Transversal 67 #89-01', 'prueba1@correo.com', 'active', '2025-09-01 03:04:10', '2025-09-01 03:33:29'),
	(4, 'DANIEL DAVID', 'CHAVARRO', '123123123', '3202187321', 'CARRERA49 # 22-05\r\nCASA', 'DANIELCR7122@GMAIL.COM', 'active', '2025-09-01 03:38:01', '2025-09-03 03:19:40'),
	(5, 'DANIEL DAVID', 'CHAVARRO', '12312312313', '3202187321', 'CARRERA49 # 22-05\r\nCASA', 'DANIELCR7122@GMAIL.COMasd', 'deleted', '2025-09-03 04:25:54', '2025-09-03 04:26:04');

-- Volcando estructura para tabla pritec_v2.system_settings
CREATE TABLE IF NOT EXISTS `system_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`),
  KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configuraciones del sistema';

-- Volcando datos para la tabla pritec_v2.system_settings: ~5 rows (aproximadamente)
INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'app_name', 'Pritec v2.0', 'Nombre de la aplicación', '2025-08-28 03:00:21', '2025-08-28 03:00:21'),
	(2, 'app_version', '2.0.0', 'Versión de la aplicación', '2025-08-28 03:00:21', '2025-08-28 03:00:21'),
	(3, 'maintenance_mode', '0', 'Modo de mantenimiento (0=off, 1=on)', '2025-08-28 03:00:21', '2025-08-28 03:00:21'),
	(4, 'registration_enabled', '1', 'Permitir registro de nuevos usuarios (0=no, 1=si)', '2025-08-28 03:00:21', '2025-08-28 03:00:21'),
	(5, 'session_timeout', '3600', 'Tiempo de expiración de sesión en segundos', '2025-08-28 03:00:21', '2025-08-28 03:00:21');

-- Volcando estructura para tabla pritec_v2.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_status` (`status`),
  KEY `idx_users_created_at` (`created_at`),
  KEY `idx_users_last_login` (`last_login`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de usuarios del sistema';

-- Volcando datos para la tabla pritec_v2.users: ~2 rows (aproximadamente)
INSERT INTO `users` (`id`, `username`, `full_name`, `email`, `password`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
	(2, 'daniels', 'daniels', 'admin@admin.co', '$2y$10$v6xIjbgO/xMwIfaTtZUOkOkKd1vLHE.kagQhhNE9wOwpbkaoL97Re', 'active', '2025-10-02 22:14:54', '2025-08-28 02:04:19', '2025-10-03 03:14:54'),
	(3, '123123123', 'asddsaads', 'admiasdn@pritec.com', '$2y$10$qj./whoqsnSrCmF1PTvR.uu6ZVxDXkcrGgfrSSEkxtMHrQ1TuIO3a', 'active', NULL, '2025-08-28 03:08:19', '2025-09-03 03:26:22');

-- Volcando estructura para tabla pritec_v2.user_login_logs
CREATE TABLE IF NOT EXISTS `user_login_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `login_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_login_at` (`login_at`),
  CONSTRAINT `user_login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registro de inicios de sesión de usuarios';

-- Volcando datos para la tabla pritec_v2.user_login_logs: ~6 rows (aproximadamente)
INSERT INTO `user_login_logs` (`id`, `user_id`, `ip_address`, `user_agent`, `login_at`) VALUES
	(1, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 OPR/120.0.0.0', '2025-08-28 03:04:27'),
	(2, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 OPR/120.0.0.0', '2025-08-28 03:09:09'),
	(3, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 OPR/120.0.0.0', '2025-08-28 03:11:48'),
	(4, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 OPR/120.0.0.0', '2025-08-28 04:05:13'),
	(5, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-29 03:25:46'),
	(6, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-01 02:29:38'),
	(7, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-02 01:03:30'),
	(8, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-03 02:14:31'),
	(9, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-09-08 02:16:48'),
	(10, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-03 03:14:54');

-- Volcando estructura para tabla pritec_v2.vehicle_pieces
CREATE TABLE IF NOT EXISTS `vehicle_pieces` (
  `id` int NOT NULL AUTO_INCREMENT,
  `section_id` int NOT NULL,
  `piece_number` int NOT NULL,
  `piece_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_x` decimal(5,2) DEFAULT NULL,
  `position_y` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_piece_number_per_section` (`section_id`,`piece_number`),
  KEY `idx_vehicle_pieces_section` (`section_id`),
  CONSTRAINT `vehicle_pieces_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `vehicle_sections` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla pritec_v2.vehicle_pieces: ~2 rows (aproximadamente)
INSERT INTO `vehicle_pieces` (`id`, `section_id`, `piece_number`, `piece_name`, `position_x`, `position_y`, `created_at`, `updated_at`) VALUES
	(9, 4, 3, 'c', 348.00, 117.00, '2025-09-03 03:24:57', '2025-09-03 03:38:29'),
	(10, 4, 2, 'b', 180.00, 244.00, '2025-09-03 03:25:13', '2025-09-03 03:25:13');

-- Volcando estructura para tabla pritec_v2.vehicle_sections
CREATE TABLE IF NOT EXISTS `vehicle_sections` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vehicle_type_id` int NOT NULL,
  `section_name` enum('carroceria','estructura','chasis') COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_section_per_vehicle` (`vehicle_type_id`,`section_name`),
  KEY `idx_vehicle_sections_vehicle_type` (`vehicle_type_id`),
  CONSTRAINT `vehicle_sections_ibfk_1` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla pritec_v2.vehicle_sections: ~3 rows (aproximadamente)
INSERT INTO `vehicle_sections` (`id`, `vehicle_type_id`, `section_name`, `image_path`, `created_at`, `updated_at`) VALUES
	(4, 2, 'carroceria', 'section_4_1756775627.png', '2025-09-02 00:13:34', '2025-09-02 00:13:47'),
	(5, 2, 'estructura', 'section_5_1756775634.png', '2025-09-02 00:13:34', '2025-09-02 00:13:54'),
	(6, 2, 'chasis', 'section_6_1756775642.png', '2025-09-02 00:13:34', '2025-09-02 00:14:02');

-- Volcando estructura para tabla pritec_v2.vehicle_types
CREATE TABLE IF NOT EXISTS `vehicle_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` enum('carro','moto') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vehicle_types_status` (`status`),
  KEY `idx_vehicle_types_type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla pritec_v2.vehicle_types: ~2 rows (aproximadamente)
INSERT INTO `vehicle_types` (`id`, `type`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
	(2, 'carro', 'Veterinaria', '', 'active', '2025-09-02 00:13:34', '2025-09-02 00:13:34');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
