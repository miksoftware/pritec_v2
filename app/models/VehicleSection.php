<?php

require_once APP_PATH . '/core/Model.php';

class VehicleSection extends Model {
    protected $table = 'vehicle_sections';
    protected $fillable = [
        'vehicle_type_id',
        'section_name', 
        'image_path'
    ];
    
    /**
     * Crear sección de vehículo
     */
    public function create($data) {
        try {
            $sectionData = [
                'vehicle_type_id' => $data['vehicle_type_id'],
                'section_name' => $data['section_name'],
                'image_path' => $data['image_path'] ?? null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $columns = implode(',', array_keys($sectionData));
            $placeholders = ':' . implode(', :', array_keys($sectionData));
            
            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
            $this->db->query($sql, $sectionData);
            
            return $this->db->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Error creating vehicle section: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener sección por ID
     */
    public function findById($id) {
        try {
            $sql = "SELECT vs.*, vt.name as vehicle_type_name, vt.type as vehicle_type
                    FROM {$this->table} vs
                    JOIN vehicle_types vt ON vs.vehicle_type_id = vt.id
                    WHERE vs.id = ?";
            
            $stmt = $this->db->query($sql, [$id]);
            return $stmt->fetch();
            
        } catch (Exception $e) {
            error_log("Error finding vehicle section: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar imagen de la sección
     */
    public function updateImage($id, $imagePath) {
        try {
            $sql = "UPDATE {$this->table} SET image_path = ?, updated_at = ? WHERE id = ?";
            return $this->db->query($sql, [$imagePath, date('Y-m-d H:i:s'), $id]);
            
        } catch (Exception $e) {
            error_log("Error updating section image: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verificar si la sección existe para un vehículo
     */
    public function sectionExists($vehicleTypeId, $sectionName) {
        try {
            $sql = "SELECT id FROM {$this->table} WHERE vehicle_type_id = ? AND section_name = ?";
            $stmt = $this->db->query($sql, [$vehicleTypeId, $sectionName]);
            return $stmt->fetch() !== false;
            
        } catch (Exception $e) {
            error_log("Error checking section existence: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener piezas de una sección
     */
    public function getPieces($sectionId) {
        try {
            $sql = "SELECT * FROM vehicle_pieces WHERE section_id = ? ORDER BY piece_number ASC";
            $stmt = $this->db->query($sql, [$sectionId]);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error getting section pieces: " . $e->getMessage());
            return [];
        }
    }
}
