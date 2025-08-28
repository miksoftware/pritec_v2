-- Base de datos para Pritec v2.0
-- Sistema de Peritajes

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS `pritec_v2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `pritec_v2`;

-- Tabla de usuarios
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_username` (`username`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de logs de inicio de sesión
CREATE TABLE `user_login_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text,
  `login_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_login_at` (`login_at`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador por defecto
-- Usuario: admin, Contraseña: admin123
INSERT INTO `users` (`username`, `full_name`, `email`, `password`, `status`) VALUES
('admin', 'Administrador del Sistema', 'admin@pritec.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');

-- Tabla para futuras configuraciones del sistema
CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL UNIQUE,
  `setting_value` text,
  `description` varchar(255),
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Configuraciones básicas del sistema
INSERT INTO `system_settings` (`setting_key`, `setting_value`, `description`) VALUES
('app_name', 'Pritec v2.0', 'Nombre de la aplicación'),
('app_version', '2.0.0', 'Versión de la aplicación'),
('maintenance_mode', '0', 'Modo de mantenimiento (0=off, 1=on)'),
('registration_enabled', '1', 'Permitir registro de nuevos usuarios (0=no, 1=si)'),
('session_timeout', '3600', 'Tiempo de expiración de sesión en segundos');

-- Índices adicionales para optimización
CREATE INDEX `idx_users_created_at` ON `users`(`created_at`);
CREATE INDEX `idx_users_last_login` ON `users`(`last_login`);

-- Comentarios en las tablas
ALTER TABLE `users` COMMENT = 'Tabla de usuarios del sistema';
ALTER TABLE `user_login_logs` COMMENT = 'Registro de inicios de sesión de usuarios';
ALTER TABLE `system_settings` COMMENT = 'Configuraciones del sistema';
