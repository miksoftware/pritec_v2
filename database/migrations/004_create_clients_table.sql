-- Migración: Crear tabla de clientes
-- Fecha: 2025-08-31
-- Descripción: Tabla para almacenar información de clientes

CREATE TABLE IF NOT EXISTS clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL COMMENT 'Nombres del cliente',
    last_name VARCHAR(100) NOT NULL COMMENT 'Apellidos del cliente',
    identification VARCHAR(50) NOT NULL UNIQUE COMMENT 'Número de identificación',
    phone VARCHAR(20) NOT NULL COMMENT 'Número de teléfono',
    address TEXT NOT NULL COMMENT 'Dirección completa',
    email VARCHAR(255) NOT NULL UNIQUE COMMENT 'Correo electrónico',
    status ENUM('active', 'inactive') DEFAULT 'active' COMMENT 'Estado del cliente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_identification (identification),
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla de clientes';

-- Insertar cliente de ejemplo
INSERT INTO clients (
    first_name, 
    last_name, 
    identification, 
    phone, 
    address, 
    email
) VALUES (
    'Carmen Rosa',
    'González',
    '66778899',
    '3134455667',
    'Transversal 67 #89-01',
    'prueba1@correo.com'
) ON DUPLICATE KEY UPDATE
    first_name = VALUES(first_name),
    last_name = VALUES(last_name),
    phone = VALUES(phone),
    address = VALUES(address),
    email = VALUES(email);
