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

-- Volcando estructura para tabla pritec_v2.expertises
CREATE TABLE IF NOT EXISTS `expertises` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código único del peritaje',
  `client_id` int unsigned NOT NULL COMMENT 'ID del cliente',
  `user_id` int unsigned NOT NULL COMMENT 'ID del usuario que creó el peritaje',
  `vehicle_type_id` int unsigned DEFAULT NULL COMMENT 'ID del tipo de vehículo',
  `service_date` date NOT NULL COMMENT 'Fecha del servicio',
  `service_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de servicio',
  `service_for` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Servicio para',
  `agreement` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Convenio',
  `placa` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Placa del vehículo',
  `marca` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Marca',
  `linea` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Línea',
  `modelo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Modelo/Año',
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Color',
  `clase_vehiculo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Clase de vehículo',
  `tipo_vehiculo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipo de vehículo',
  `tipo_carroceria` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipo de carrocería',
  `tipo_combustible` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipo de combustible',
  `numero_motor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de motor',
  `numero_chasis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de chasis',
  `numero_serie` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de serie',
  `vin` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'VIN',
  `kilometraje` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Kilometraje',
  `cilindrada` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cilindrada',
  `capacidad_carga` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Capacidad de carga',
  `numero_ejes` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de ejes',
  `numero_pasajeros` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de pasajeros',
  `fecha_matricula` date DEFAULT NULL COMMENT 'Fecha de matrícula',
  `llanta_anterior_izquierda` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje llanta ant. izq.',
  `llanta_anterior_derecha` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje llanta ant. der.',
  `llanta_posterior_izquierda` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje llanta post. izq.',
  `llanta_posterior_derecha` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje llanta post. der.',
  `observaciones_llantas` text COLLATE utf8mb4_unicode_ci COMMENT 'Observaciones de llantas',
  `amortiguador_anterior_izquierdo` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje amortiguador ant. izq.',
  `amortiguador_anterior_derecho` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje amortiguador ant. der.',
  `amortiguador_posterior_izquierdo` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje amortiguador post. izq.',
  `amortiguador_posterior_derecho` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje amortiguador post. der.',
  `observaciones_amortiguadores` text COLLATE utf8mb4_unicode_ci COMMENT 'Observaciones de amortiguadores',
  `prueba_bateria` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje prueba batería',
  `prueba_arranque` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje prueba arranque',
  `carga_bateria` decimal(5,2) DEFAULT '0.00' COMMENT 'Porcentaje carga batería',
  `observaciones_bateria` text COLLATE utf8mb4_unicode_ci COMMENT 'Observaciones de batería',
  `motor_sistemas_data` json DEFAULT NULL COMMENT 'Datos de motor y sistemas en formato JSON',
  `observaciones_motor` text COLLATE utf8mb4_unicode_ci COMMENT 'Observaciones generales de motor',
  `observaciones_interior` text COLLATE utf8mb4_unicode_ci COMMENT 'Observaciones generales de interior',
  `fugas_niveles_data` json DEFAULT NULL COMMENT 'Datos de fugas y niveles en formato JSON',
  `prueba_ruta` text COLLATE utf8mb4_unicode_ci COMMENT 'Observaciones de prueba de ruta',
  `observaciones_fugas` text COLLATE utf8mb4_unicode_ci COMMENT 'Observaciones generales de fugas',
  `status` enum('draft','in_progress','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft' COMMENT 'draft=recién creado, in_progress=avanzando pasos, completed=finalizado',
  `current_step` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'Paso actual del peritaje (1-12)',
  `total_fotos` int DEFAULT '0' COMMENT 'Total de fotografías adjuntas',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de creación',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualización',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `idx_client` (`client_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_placa` (`placa`),
  KEY `idx_codigo` (`codigo`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_status_current_step` (`status`,`current_step`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabla principal de peritajes completos';

-- Volcando datos para la tabla pritec_v2.expertises: ~3 rows (aproximadamente)
INSERT INTO `expertises` (`id`, `codigo`, `client_id`, `user_id`, `vehicle_type_id`, `service_date`, `service_number`, `service_for`, `agreement`, `placa`, `marca`, `linea`, `modelo`, `color`, `clase_vehiculo`, `tipo_vehiculo`, `tipo_carroceria`, `tipo_combustible`, `numero_motor`, `numero_chasis`, `numero_serie`, `vin`, `kilometraje`, `cilindrada`, `capacidad_carga`, `numero_ejes`, `numero_pasajeros`, `fecha_matricula`, `llanta_anterior_izquierda`, `llanta_anterior_derecha`, `llanta_posterior_izquierda`, `llanta_posterior_derecha`, `observaciones_llantas`, `amortiguador_anterior_izquierdo`, `amortiguador_anterior_derecho`, `amortiguador_posterior_izquierdo`, `amortiguador_posterior_derecho`, `observaciones_amortiguadores`, `prueba_bateria`, `prueba_arranque`, `carga_bateria`, `observaciones_bateria`, `motor_sistemas_data`, `observaciones_motor`, `observaciones_interior`, `fugas_niveles_data`, `prueba_ruta`, `observaciones_fugas`, `status`, `current_step`, `total_fotos`, `created_at`, `updated_at`) VALUES
	(1, 'PRT-20251005-AD63D6', 4, 2, 2, '2025-10-05', '123', '123', '123', '123', '876', '876', '876', '87', '6876', NULL, '87', NULL, '76', '68', '876', NULL, '68', '876', NULL, NULL, NULL, NULL, 12.00, 32.00, 32.00, 23.00, 'asd', 43.00, 43.00, 43.00, 43.00, 'dasdasd', 24.00, 34.00, 34.00, 'asasd', '{"estado_cardan": "Bueno", "estado_chapas": "Bueno", "estado_axiales": "Bueno", "estado_correas": "Bueno", "estado_rotulas": "Bueno", "estado_tijeras": "Bueno", "estado_alfombra": "Bueno", "estado_arranque": "Regular", "estado_crucetas": "Bueno", "estado_millaret": "Bueno", "estado_radiador": "Bueno", "tension_correas": "Regular", "estado_punta_eje": "Bueno", "respuesta_cardan": "", "respuesta_chapas": "", "estado_cinturones": "Bueno", "estado_terminales": "Regular", "respuesta_axiales": "", "respuesta_correas": "", "respuesta_rotulas": "", "respuesta_tijeras": "", "estado_calefaccion": "Bueno", "estado_carter_caja": "Bueno", "estado_filtro_aire": "Bueno", "estado_rodamientos": "Bueno", "respuesta_alfombra": "", "respuesta_arranque": "", "respuesta_crucetas": "", "respuesta_millaret": "", "respuesta_radiador": "", "estado_carter_motor": "Bueno", "estado_discos_freno": "Bueno", "estado_soporte_caja": "Bueno", "observaciones_motor": "", "respuesta_punta_eje": "", "estado_soporte_motor": "Regular", "respuesta_cinturones": "", "respuesta_terminales": "", "estado_caja_direccion": "Regular", "estado_pastilla_freno": "Bueno", "respuesta_calefaccion": "", "respuesta_carter_caja": "", "respuesta_filtro_aire": "", "respuesta_rodamientos": "", "estado_externo_bateria": "Bueno", "estado_tapiceria_techo": "Bueno", "observaciones_interior": "", "respuesta_carter_motor": "", "respuesta_discos_freno": "", "respuesta_soporte_caja": "", "estado_caja_velocidades": "Bueno", "respuesta_soporte_motor": "", "respuesta_caja_direccion": "", "respuesta_pastilla_freno": "", "estado_aire_acondicionado": "Bueno", "estado_mangueras_radiador": "Bueno", "estado_tapiceria_asientos": "Bueno", "respuesta_externo_bateria": "", "respuesta_tapiceria_techo": "", "respuesta_tension_correas": "", "respuesta_caja_velocidades": "", "respuesta_aire_acondicionado": "", "respuesta_mangueras_radiador": "", "respuesta_tapiceria_asientos": ""}', '', '', '{"prueba_ruta": "", "observaciones_fugas": "", "respuesta_fuga_aceite_motor": "", "respuesta_nivel_aceite_motor": "123", "respuesta_estado_tubo_exhosto": "", "respuesta_fuga_liquido_frenos": "", "respuesta_nivel_liquido_frenos": "", "respuesta_estado_tuberia_frenos": "", "respuesta_nivel_liquido_embrague": "", "respuesta_fuga_tanque_combustible": "", "respuesta_viscosidad_aceite_motor": "", "respuesta_nivel_agua_limpiavidrios": "", "respuesta_nivel_refrigerante_motor": "", "respuesta_estado_tanque_silenciador": "", "respuesta_fuga_liquido_bomba_embrague": "", "respuesta_fuga_aceite_caja_transmision": "", "respuesta_fuga_aceite_caja_velocidades": "", "respuesta_estado_tanque_catalizador_gases": "", "respuesta_fuga_aceite_direccion_hidraulica": "", "respuesta_estado_guardapolvo_caja_direccion": "", "respuesta_nivel_aceite_direccion_hidraulica": ""}', '', '', 'completed', 12, 0, '2025-10-06 03:19:38', '2025-10-08 02:16:29'),
	(2, 'PRT-20251007-C256E1', 4, 2, 2, '2025-10-05', '123', '123', NULL, '123', '876', '876', '876', '87', NULL, NULL, NULL, '', '76', '68', NULL, '', '68', NULL, NULL, NULL, NULL, NULL, 12.00, 32.00, 32.00, 23.00, 'asd', 43.00, 43.00, 43.00, 43.00, 'dasdasd', 24.00, 34.00, 34.00, 'asasd', '{"estado_panel": "", "estado_bocina": "", "estado_bujias": "", "estado_cables": "", "estado_clutch": "", "estado_airbags": "", "estado_espejos": "", "estado_embrague": "", "estado_mangueras": "", "estado_tapiceria": "", "estado_cinturones": "", "estado_freno_mano": "", "estado_filtro_aire": "", "estado_pedal_freno": "", "estado_transmision": "", "estado_aceite_motor": "", "estado_banda_tiempo": "", "estado_limpiabrisas": "", "estado_pedal_clutch": "", "estado_refrigerante": "", "observaciones_motor": "", "tension_correa_aire": "", "estado_liquido_frenos": "", "estado_luces_traseras": "", "estado_sistema_escape": "", "observaciones_interior": "", "estado_banda_accesorios": "", "estado_luces_delanteras": "", "estado_liquido_direccion": "", "tension_correa_direccion": "", "estado_aire_acondicionado": "", "estado_filtro_combustible": "", "tension_correa_alternador": ""}', '', '', '{"estado_gato": "", "prueba_ruta": "", "fuga_combustible": "", "fuga_transmision": "", "fuga_aceite_motor": "", "fuga_refrigerante": "", "nivel_aceite_motor": "", "nivel_refrigerante": "", "estado_herramientas": "", "fuga_liquido_frenos": "", "observaciones_fugas": "", "nivel_liquido_frenos": "", "presion_neumatico_ad": "", "presion_neumatico_ai": "", "presion_neumatico_pd": "", "presion_neumatico_pi": "", "fuga_liquido_direccion": "", "nivel_liquido_direccion": "", "nivel_liquido_transmision": "", "nivel_liquido_limpiabrisas": "", "presion_neumatico_repuesto": ""}', '', '', 'completed', 12, 2, '2025-10-08 02:26:36', '2025-10-08 02:26:36'),
	(3, 'PRT-20251007-0F238A', 4, 2, 2, '2025-10-07', 'asddas', 'asdasd', 'asdads', 'asd123', '', '', '', '', '', NULL, '', NULL, '', '', '', NULL, '', '', NULL, NULL, NULL, NULL, 78.00, 78.00, 78.00, 87.00, 'asdasdadsasdasd', 89.00, 89.00, 89.00, 89.00, 'asdasdasd', 89.00, 89.00, 98.00, '', '{"estado_cardan": "Bueno", "estado_chapas": "Bueno", "estado_axiales": "Bueno", "estado_correas": "Regular", "estado_rotulas": "Bueno", "estado_tijeras": "Bueno", "estado_alfombra": "Bueno", "estado_arranque": "Regular", "estado_crucetas": "Bueno", "estado_millaret": "Bueno", "estado_radiador": "Bueno", "tension_correas": "Bueno", "estado_punta_eje": "Bueno", "respuesta_cardan": "", "respuesta_chapas": "", "estado_cinturones": "Bueno", "estado_terminales": "Bueno", "respuesta_axiales": "", "respuesta_correas": "", "respuesta_rotulas": "", "respuesta_tijeras": "", "estado_calefaccion": "Bueno", "estado_carter_caja": "Bueno", "estado_filtro_aire": "Bueno", "estado_rodamientos": "Bueno", "respuesta_alfombra": "", "respuesta_arranque": "", "respuesta_crucetas": "", "respuesta_millaret": "", "respuesta_radiador": "", "estado_carter_motor": "Bueno", "estado_discos_freno": "Bueno", "estado_soporte_caja": "Bueno", "observaciones_motor": "", "respuesta_punta_eje": "", "estado_soporte_motor": "Bueno", "respuesta_cinturones": "", "respuesta_terminales": "", "estado_caja_direccion": "Bueno", "estado_pastilla_freno": "Bueno", "respuesta_calefaccion": "", "respuesta_carter_caja": "", "respuesta_filtro_aire": "", "respuesta_rodamientos": "", "estado_externo_bateria": "Bueno", "estado_tapiceria_techo": "Bueno", "observaciones_interior": "", "respuesta_carter_motor": "", "respuesta_discos_freno": "", "respuesta_soporte_caja": "", "estado_caja_velocidades": "Bueno", "respuesta_soporte_motor": "", "respuesta_caja_direccion": "", "respuesta_pastilla_freno": "", "estado_aire_acondicionado": "Bueno", "estado_mangueras_radiador": "Bueno", "estado_tapiceria_asientos": "Bueno", "respuesta_externo_bateria": "", "respuesta_tapiceria_techo": "", "respuesta_tension_correas": "", "respuesta_caja_velocidades": "", "respuesta_aire_acondicionado": "", "respuesta_mangueras_radiador": "", "respuesta_tapiceria_asientos": ""}', '', '', '{"prueba_ruta": "", "observaciones_fugas": "", "respuesta_fuga_aceite_motor": "", "respuesta_nivel_aceite_motor": "", "respuesta_estado_tubo_exhosto": "", "respuesta_fuga_liquido_frenos": "", "respuesta_nivel_liquido_frenos": "", "respuesta_estado_tuberia_frenos": "", "respuesta_nivel_liquido_embrague": "", "respuesta_fuga_tanque_combustible": "", "respuesta_viscosidad_aceite_motor": "", "respuesta_nivel_agua_limpiavidrios": "", "respuesta_nivel_refrigerante_motor": "", "respuesta_estado_tanque_silenciador": "", "respuesta_fuga_liquido_bomba_embrague": "", "respuesta_fuga_aceite_caja_transmision": "", "respuesta_fuga_aceite_caja_velocidades": "", "respuesta_estado_tanque_catalizador_gases": "", "respuesta_fuga_aceite_direccion_hidraulica": "", "respuesta_estado_guardapolvo_caja_direccion": "", "respuesta_nivel_aceite_direccion_hidraulica": ""}', '', '', 'completed', 12, 2, '2025-10-08 01:34:24', '2025-10-08 02:37:31');

-- Volcando estructura para tabla pritec_v2.expertise_inspections
CREATE TABLE IF NOT EXISTS `expertise_inspections` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `expertise_id` int unsigned NOT NULL COMMENT 'ID del peritaje',
  `section` enum('carroceria','estructura','chasis') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sección inspeccionada',
  `pieza_id` int unsigned NOT NULL COMMENT 'ID de la pieza inspeccionada',
  `concepto_id` int unsigned NOT NULL COMMENT 'ID del concepto de inspección',
  `observacion` text COLLATE utf8mb4_unicode_ci COMMENT 'Observación de la inspección',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_expertise` (`expertise_id`),
  KEY `idx_section` (`section`),
  KEY `idx_pieza` (`pieza_id`),
  KEY `idx_concepto` (`concepto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Detalle de inspecciones de peritajes';

-- Volcando datos para la tabla pritec_v2.expertise_inspections: ~7 rows (aproximadamente)
INSERT INTO `expertise_inspections` (`id`, `expertise_id`, `section`, `pieza_id`, `concepto_id`, `observacion`, `created_at`) VALUES
	(3, 1, 'carroceria', 10, 10, NULL, '2025-10-06 04:22:46'),
	(5, 1, 'estructura', 11, 2, NULL, '2025-10-06 04:29:09'),
	(6, 1, 'chasis', 12, 11, NULL, '2025-10-06 04:29:25'),
	(7, 3, 'carroceria', 10, 12, NULL, '2025-10-08 02:35:15'),
	(8, 3, 'carroceria', 9, 13, NULL, '2025-10-08 02:35:15'),
	(9, 3, 'estructura', 11, 1, NULL, '2025-10-08 02:35:32'),
	(10, 3, 'chasis', 12, 14, NULL, '2025-10-08 02:35:38');

-- Volcando estructura para tabla pritec_v2.expertise_photos
CREATE TABLE IF NOT EXISTS `expertise_photos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `expertise_id` int unsigned NOT NULL COMMENT 'ID del peritaje',
  `nombre_original` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre original del archivo',
  `nombre_guardado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el que se guardó',
  `ruta` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ruta del archivo',
  `extension` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Extensión del archivo',
  `size` int NOT NULL COMMENT 'Tamaño en bytes',
  `orden` int DEFAULT '0' COMMENT 'Orden de visualización',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_expertise` (`expertise_id`),
  KEY `idx_orden` (`orden`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Fotografías de peritajes';

-- Volcando datos para la tabla pritec_v2.expertise_photos: ~6 rows (aproximadamente)
INSERT INTO `expertise_photos` (`id`, `expertise_id`, `nombre_original`, `nombre_guardado`, `ruta`, `extension`, `size`, `orden`, `created_at`) VALUES
	(1, 1, 'Captura de pantalla 2025-07-08 011553.png', 'expertise_68e5c8f6185e21.30284928.png', 'uploads/expertise/expertise_68e5c8f6185e21.30284928.png', 'png', 15206, 1, '2025-10-08 02:16:29'),
	(2, 1, 'Captura de pantalla 2025-07-08 195951.png', 'expertise_68e5c8f618f9f2.87580164.png', 'uploads/expertise/expertise_68e5c8f618f9f2.87580164.png', 'png', 29745, 2, '2025-10-08 02:16:29'),
	(3, 2, 'Captura de pantalla 2025-07-08 011553.png', 'expertise_68e5c9a1ba2821.76670326.png', 'uploads/expertise/expertise_68e5c9a1ba2821.76670326.png', 'png', 15206, 1, '2025-10-08 02:26:36'),
	(4, 2, 'Captura de pantalla 2025-07-08 195951.png', 'expertise_68e5c9a1c41d85.38113334.png', 'uploads/expertise/expertise_68e5c9a1c41d85.38113334.png', 'png', 29745, 2, '2025-10-08 02:26:36'),
	(5, 3, 'SOLICITUD CERTIFICADO_page-0001.jpg', 'expertise_68e5ce6266f897.39730708.jpg', 'uploads/expertise/expertise_68e5ce6266f897.39730708.jpg', 'jpg', 394436, 1, '2025-10-08 02:37:31'),
	(6, 3, 'logo_principal.png', 'expertise_68e5ce6267a672.79159901.png', 'uploads/expertise/expertise_68e5ce6267a672.79159901.png', 'png', 38936, 2, '2025-10-08 02:37:31');

-- Volcando estructura para tabla pritec_v2.inspection_concepts
CREATE TABLE IF NOT EXISTS `inspection_concepts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del concepto (ej: Bueno, Mala reparación)',
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Categoría: carroceria, estructura, chasis, all',
  `display_order` int DEFAULT '0' COMMENT 'Orden de visualización',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category`),
  KEY `idx_status` (`status`),
  KEY `idx_display_order` (`display_order`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Conceptos de inspección para peritajes';

-- Volcando datos para la tabla pritec_v2.inspection_concepts: ~14 rows (aproximadamente)
INSERT INTO `inspection_concepts` (`id`, `name`, `category`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Bueno', 'all', 1, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(2, 'Buena reparación', 'all', 2, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(3, 'Mala reparación', 'all', 3, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(4, 'Bien repintado', 'all', 4, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(5, 'Mal repintado', 'all', 5, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(6, 'Regular', 'all', 6, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(7, 'Regular (Oxidación- corrosión)', 'all', 7, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(8, 'Fisurado', 'all', 8, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(9, 'Deformidad media', 'all', 9, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(10, 'Deformidad fuerte', 'all', 10, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(11, 'Sumido', 'all', 11, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(12, 'Rayón', 'all', 12, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(13, 'Hermeticidad deficiente', 'carroceria', 13, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04'),
	(14, 'Regular (soldadura no original)', 'chasis', 14, 'active', '2025-10-05 02:11:04', '2025-10-05 02:11:04');

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
	(2, 'daniels', 'daniels', 'admin@admin.co', '$2y$10$v6xIjbgO/xMwIfaTtZUOkOkKd1vLHE.kagQhhNE9wOwpbkaoL97Re', 'active', '2025-10-07 21:11:16', '2025-08-28 02:04:19', '2025-10-08 02:11:16'),
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registro de inicios de sesión de usuarios';

-- Volcando datos para la tabla pritec_v2.user_login_logs: ~13 rows (aproximadamente)
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
	(10, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-03 03:14:54'),
	(11, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-05 22:57:17'),
	(12, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', '2025-10-06 04:19:15'),
	(13, 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-08 02:11:16');

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla pritec_v2.vehicle_pieces: ~4 rows (aproximadamente)
INSERT INTO `vehicle_pieces` (`id`, `section_id`, `piece_number`, `piece_name`, `position_x`, `position_y`, `created_at`, `updated_at`) VALUES
	(9, 4, 3, 'c', 348.00, 117.00, '2025-09-03 03:24:57', '2025-09-03 03:38:29'),
	(10, 4, 2, 'b', 180.00, 244.00, '2025-09-03 03:25:13', '2025-09-03 03:25:13'),
	(11, 5, 1, 'parte de esta', 129.00, 155.00, '2025-10-05 01:20:27', '2025-10-05 01:20:27'),
	(12, 6, 1, 'parte de otra', 174.00, 222.00, '2025-10-05 01:20:45', '2025-10-05 01:20:45');

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla pritec_v2.vehicle_sections: ~5 rows (aproximadamente)
INSERT INTO `vehicle_sections` (`id`, `vehicle_type_id`, `section_name`, `image_path`, `created_at`, `updated_at`) VALUES
	(4, 2, 'carroceria', 'section_4_1756775627.png', '2025-09-02 00:13:34', '2025-09-02 00:13:47'),
	(5, 2, 'estructura', 'section_5_1756775634.png', '2025-09-02 00:13:34', '2025-09-02 00:13:54'),
	(6, 2, 'chasis', 'section_6_1756775642.png', '2025-09-02 00:13:34', '2025-09-02 00:14:02'),
	(9, 4, 'estructura', NULL, '2025-10-05 04:02:39', '2025-10-05 04:02:39'),
	(10, 4, 'chasis', NULL, '2025-10-05 04:02:39', '2025-10-05 04:02:39');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla pritec_v2.vehicle_types: ~2 rows (aproximadamente)
INSERT INTO `vehicle_types` (`id`, `type`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
	(2, 'carro', 'Veterinaria', '', 'active', '2025-09-02 00:13:34', '2025-09-02 00:13:34'),
	(4, 'moto', 'asdasd', 'asd', 'active', '2025-10-05 04:02:39', '2025-10-05 04:02:39');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
