-- Migración: Agregar campos de cantidad de amortiguadores para motos
-- Fecha: 2025-12-18

ALTER TABLE expertises 
ADD COLUMN cant_amortiguadores_delanteros TINYINT DEFAULT 1 AFTER amortiguador_posterior_derecho,
ADD COLUMN cant_amortiguadores_traseros TINYINT DEFAULT 1 AFTER cant_amortiguadores_delanteros;

-- Actualizar registros existentes (por defecto 1 para motos, 2 para carros)
UPDATE expertises e
JOIN vehicle_types vt ON e.tipo_vehiculo = vt.id
SET e.cant_amortiguadores_delanteros = CASE WHEN vt.type = 'moto' THEN 1 ELSE 2 END,
    e.cant_amortiguadores_traseros = CASE WHEN vt.type = 'moto' THEN 1 ELSE 2 END;
