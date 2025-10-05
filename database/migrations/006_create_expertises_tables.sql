-- =============================================
-- Migración: Crear tabla expertises (Peritajes)
-- Descripción: Tabla principal para almacenar los peritajes completos
-- Fecha: 2025-10-04
-- =============================================

-- Eliminar tabla si existe
DROP TABLE IF EXISTS `expertises`;

-- Crear tabla expertises
CREATE TABLE `expertises` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `codigo` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Código único del peritaje',
    
    -- Relaciones
    `client_id` INT(11) UNSIGNED NOT NULL COMMENT 'ID del cliente',
    `user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ID del usuario que creó el peritaje',
    `vehicle_type_id` INT(11) UNSIGNED NULL COMMENT 'ID del tipo de vehículo',
    
    -- Información del servicio (Paso 1)
    `service_date` DATE NOT NULL COMMENT 'Fecha del servicio',
    `service_number` VARCHAR(100) NULL COMMENT 'Número de servicio',
    `service_for` VARCHAR(100) NULL COMMENT 'Servicio para',
    `agreement` VARCHAR(100) NULL COMMENT 'Convenio',
    
    -- Datos del vehículo (Paso 2)
    `placa` VARCHAR(20) NOT NULL COMMENT 'Placa del vehículo',
    `marca` VARCHAR(100) NULL COMMENT 'Marca',
    `linea` VARCHAR(100) NULL COMMENT 'Línea',
    `modelo` VARCHAR(20) NULL COMMENT 'Modelo/Año',
    `color` VARCHAR(50) NULL COMMENT 'Color',
    `clase_vehiculo` VARCHAR(50) NULL COMMENT 'Clase de vehículo',
    `tipo_vehiculo` VARCHAR(50) NULL COMMENT 'Tipo de vehículo',
    `tipo_carroceria` VARCHAR(50) NULL COMMENT 'Tipo de carrocería',
    `tipo_combustible` VARCHAR(50) NULL COMMENT 'Tipo de combustible',
    `numero_motor` VARCHAR(100) NULL COMMENT 'Número de motor',
    `numero_chasis` VARCHAR(100) NULL COMMENT 'Número de chasis',
    `numero_serie` VARCHAR(100) NULL COMMENT 'Número de serie',
    `vin` VARCHAR(100) NULL COMMENT 'VIN',
    `kilometraje` VARCHAR(50) NULL COMMENT 'Kilometraje',
    `cilindrada` VARCHAR(50) NULL COMMENT 'Cilindrada',
    `capacidad_carga` VARCHAR(50) NULL COMMENT 'Capacidad de carga',
    `numero_ejes` VARCHAR(20) NULL COMMENT 'Número de ejes',
    `numero_pasajeros` VARCHAR(20) NULL COMMENT 'Número de pasajeros',
    `fecha_matricula` DATE NULL COMMENT 'Fecha de matrícula',
    
    -- Evaluaciones de porcentaje (Pasos 6, 7, 8)
    `llanta_anterior_izquierda` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Porcentaje llanta ant. izq.',
    `llanta_anterior_derecha` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Porcentaje llanta ant. der.',
    `llanta_posterior_izquierda` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Porcentaje llanta post. izq.',
    `llanta_posterior_derecha` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Porcentaje llanta post. der.',
    `observaciones_llantas` TEXT NULL COMMENT 'Observaciones de llantas',
    
    `amortiguador_anterior_izquierdo` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Porcentaje amortiguador ant. izq.',
    `amortiguador_anterior_derecho` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Porcentaje amortiguador ant. der.',
    `amortiguador_posterior_izquierdo` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Porcentaje amortiguador post. izq.',
    `amortiguador_posterior_derecho` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Porcentaje amortiguador post. der.',
    `observaciones_amortiguadores` TEXT NULL COMMENT 'Observaciones de amortiguadores',
    
    `prueba_bateria` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Porcentaje prueba batería',
    `prueba_arranque` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Porcentaje prueba arranque',
    `carga_bateria` DECIMAL(5,2) NULL DEFAULT 0 COMMENT 'Porcentaje carga batería',
    `observaciones_bateria` TEXT NULL COMMENT 'Observaciones de batería',
    
    -- Motor y Sistemas (Paso 9) - Almacenado como JSON
    `motor_sistemas_data` JSON NULL COMMENT 'Datos de motor y sistemas en formato JSON',
    `observaciones_motor` TEXT NULL COMMENT 'Observaciones generales de motor',
    `observaciones_interior` TEXT NULL COMMENT 'Observaciones generales de interior',
    
    -- Fugas y Niveles (Paso 10) - Almacenado como JSON
    `fugas_niveles_data` JSON NULL COMMENT 'Datos de fugas y niveles en formato JSON',
    `prueba_ruta` TEXT NULL COMMENT 'Observaciones de prueba de ruta',
    `observaciones_fugas` TEXT NULL COMMENT 'Observaciones generales de fugas',
    
    -- Control de estado
    `status` ENUM('draft', 'completed', 'approved', 'rejected') NOT NULL DEFAULT 'completed' COMMENT 'Estado del peritaje',
    `total_fotos` INT(11) NULL DEFAULT 0 COMMENT 'Total de fotografías adjuntas',
    
    -- Timestamps
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación',
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualización',
    
    PRIMARY KEY (`id`),
    INDEX `idx_client` (`client_id`),
    INDEX `idx_user` (`user_id`),
    INDEX `idx_placa` (`placa`),
    INDEX `idx_codigo` (`codigo`),
    INDEX `idx_status` (`status`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla principal de peritajes completos';

-- =============================================
-- Tabla: expertise_inspections
-- Descripción: Detalle de inspecciones (Pasos 3, 4, 5)
-- =============================================

DROP TABLE IF EXISTS `expertise_inspections`;

CREATE TABLE `expertise_inspections` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `expertise_id` INT(11) UNSIGNED NOT NULL COMMENT 'ID del peritaje',
    `section` ENUM('carroceria', 'estructura', 'chasis') NOT NULL COMMENT 'Sección inspeccionada',
    `pieza_id` INT(11) UNSIGNED NOT NULL COMMENT 'ID de la pieza inspeccionada',
    `concepto_id` INT(11) UNSIGNED NOT NULL COMMENT 'ID del concepto de inspección',
    `observacion` TEXT NULL COMMENT 'Observación de la inspección',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    INDEX `idx_expertise` (`expertise_id`),
    INDEX `idx_section` (`section`),
    INDEX `idx_pieza` (`pieza_id`),
    INDEX `idx_concepto` (`concepto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Detalle de inspecciones de peritajes';

-- =============================================
-- Tabla: expertise_photos
-- Descripción: Fotografías adjuntas (Paso 11)
-- =============================================

DROP TABLE IF EXISTS `expertise_photos`;

CREATE TABLE `expertise_photos` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `expertise_id` INT(11) UNSIGNED NOT NULL COMMENT 'ID del peritaje',
    `nombre_original` VARCHAR(255) NOT NULL COMMENT 'Nombre original del archivo',
    `nombre_guardado` VARCHAR(255) NOT NULL COMMENT 'Nombre con el que se guardó',
    `ruta` VARCHAR(500) NOT NULL COMMENT 'Ruta del archivo',
    `extension` VARCHAR(10) NOT NULL COMMENT 'Extensión del archivo',
    `size` INT(11) NOT NULL COMMENT 'Tamaño en bytes',
    `orden` INT(11) NULL DEFAULT 0 COMMENT 'Orden de visualización',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (`id`),
    INDEX `idx_expertise` (`expertise_id`),
    INDEX `idx_orden` (`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Fotografías de peritajes';

-- Insertar mensaje de éxito
SELECT 'Tablas de expertises creadas exitosamente' AS mensaje;
