-- Migración: Agregar columnas de observaciones para pasos 3, 4 y 5
-- Fecha: 2025-10-11
-- Descripción: Agrega campos para guardar observaciones generales de inspecciones

-- 1. Agregar columnas de observaciones en la tabla expertises
ALTER TABLE `expertises`
ADD COLUMN `observaciones_carroceria` TEXT NULL COMMENT 'Observaciones generales de inspección de carrocería (Paso 3)' AFTER `fecha_matricula`,
ADD COLUMN `observaciones_estructura` TEXT NULL COMMENT 'Observaciones generales de inspección de estructura (Paso 4)' AFTER `observaciones_carroceria`,
ADD COLUMN `observaciones_chasis` TEXT NULL COMMENT 'Observaciones generales de inspección de chasis (Paso 5)' AFTER `observaciones_estructura`;

-- 2. Eliminar columna observacion de expertise_inspections (no se usa)
ALTER TABLE `expertise_inspections`
DROP COLUMN `observacion`;
