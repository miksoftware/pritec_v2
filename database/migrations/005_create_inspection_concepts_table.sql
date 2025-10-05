-- ============================================
-- Tabla: inspection_concepts
-- Descripción: Conceptos de inspección para carrocería, estructura y chasis
-- ============================================

CREATE TABLE IF NOT EXISTS `inspection_concepts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del concepto (ej: Bueno, Mala reparación)',
  `category` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Categoría: carroceria, estructura, chasis, all',
  `display_order` int DEFAULT 0 COMMENT 'Orden de visualización',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_status` (`status`),
  KEY `idx_display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Conceptos de inspección para peritajes';

-- ============================================
-- Insertar Conceptos Iniciales
-- ============================================

-- Conceptos comunes a todas las categorías (all)
INSERT INTO `inspection_concepts` (`name`, `category`, `display_order`, `status`) VALUES
('Bueno', 'all', 1, 'active'),
('Buena reparación', 'all', 2, 'active'),
('Mala reparación', 'all', 3, 'active'),
('Bien repintado', 'all', 4, 'active'),
('Mal repintado', 'all', 5, 'active'),
('Regular', 'all', 6, 'active'),
('Regular (Oxidación- corrosión)', 'all', 7, 'active'),
('Fisurado', 'all', 8, 'active'),
('Deformidad media', 'all', 9, 'active'),
('Deformidad fuerte', 'all', 10, 'active'),
('Sumido', 'all', 11, 'active'),
('Rayón', 'all', 12, 'active');

-- Conceptos específicos de carrocería
INSERT INTO `inspection_concepts` (`name`, `category`, `display_order`, `status`) VALUES
('Hermeticidad deficiente', 'carroceria', 13, 'active');

-- Conceptos específicos de chasis
INSERT INTO `inspection_concepts` (`name`, `category`, `display_order`, `status`) VALUES
('Regular (soldadura no original)', 'chasis', 14, 'active');

-- ============================================
-- Fin del archivo
-- ============================================
