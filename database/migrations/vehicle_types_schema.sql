-- Crear tablas para el sistema de tipos de vehículos

-- Tabla principal de tipos de vehículos
CREATE TABLE vehicle_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('carro', 'moto') NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de secciones del vehículo (carrocería, estructura, chasis)
CREATE TABLE vehicle_sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_type_id INT NOT NULL,
    section_name ENUM('carroceria', 'estructura', 'chasis') NOT NULL,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_type_id) REFERENCES vehicle_types(id) ON DELETE CASCADE,
    UNIQUE KEY unique_section_per_vehicle (vehicle_type_id, section_name)
);

-- Tabla de piezas del vehículo
CREATE TABLE vehicle_pieces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,
    piece_number INT NOT NULL,
    piece_name VARCHAR(100) NOT NULL,
    position_x DECIMAL(5,2), -- Coordenada X en la imagen (porcentaje)
    position_y DECIMAL(5,2), -- Coordenada Y en la imagen (porcentaje)
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (section_id) REFERENCES vehicle_sections(id) ON DELETE CASCADE,
    UNIQUE KEY unique_piece_number_per_section (section_id, piece_number)
);

-- Índices para mejorar rendimiento
CREATE INDEX idx_vehicle_types_status ON vehicle_types(status);
CREATE INDEX idx_vehicle_types_type ON vehicle_types(type);
CREATE INDEX idx_vehicle_sections_vehicle_type ON vehicle_sections(vehicle_type_id);
CREATE INDEX idx_vehicle_pieces_section ON vehicle_pieces(section_id);
