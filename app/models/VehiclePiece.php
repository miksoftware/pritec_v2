<?php

require_once APP_PATH . '/core/Model.php';

class VehiclePiece extends Model {
    protected $table = 'vehicle_pieces';
    protected $fillable = [
        'section_id',
        'piece_number', 
        'piece_name',
        'position_x',
        'position_y'
    ];
    
    /**
     * Crear pieza de vehículo
     */
    public function create($data) {
        try {
            $pieceData = [
                'section_id' => $data['section_id'],
                'piece_number' => $data['piece_number'],
                'piece_name' => $data['piece_name'],
                'position_x' => $data['position_x'] ?? null,
                'position_y' => $data['position_y'] ?? null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $columns = implode(',', array_keys($pieceData));
            $placeholders = ':' . implode(', :', array_keys($pieceData));
            
            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
            $this->db->query($sql, $pieceData);
            
            return $this->db->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Error creating vehicle piece: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar posición de la pieza
     */
    public function updatePosition($id, $positionX, $positionY) {
        try {
            $sql = "UPDATE {$this->table} SET position_x = ?, position_y = ?, updated_at = ? WHERE id = ?";
            return $this->db->query($sql, [$positionX, $positionY, date('Y-m-d H:i:s'), $id]);
            
        } catch (Exception $e) {
            error_log("Error updating piece position: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar pieza
     */
    public function update($id, $data) {
        try {
            $updateData = [];
            
            if (isset($data['piece_number'])) {
                $updateData['piece_number'] = $data['piece_number'];
            }
            
            if (isset($data['piece_name'])) {
                $updateData['piece_name'] = $data['piece_name'];
            }
            
            if (isset($data['position_x'])) {
                $updateData['position_x'] = $data['position_x'];
            }
            
            if (isset($data['position_y'])) {
                $updateData['position_y'] = $data['position_y'];
            }
            
            if (empty($updateData)) {
                return false;
            }
            
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            
            $setParts = [];
            foreach ($updateData as $key => $value) {
                $setParts[] = "$key = :$key";
            }
            
            $updateData['id'] = $id;
            
            $sql = "UPDATE {$this->table} SET " . implode(', ', $setParts) . " WHERE id = :id";
            
            return $this->db->query($sql, $updateData);
            
        } catch (Exception $e) {
            error_log("Error updating vehicle piece: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar pieza
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            return $this->db->query($sql, [$id]);
            
        } catch (Exception $e) {
            error_log("Error deleting vehicle piece: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verificar si el número de pieza ya existe en la sección
     */
    public function pieceNumberExists($sectionId, $pieceNumber, $excludeId = null) {
        try {
            $sql = "SELECT id FROM {$this->table} WHERE section_id = ? AND piece_number = ?";
            $params = [$sectionId, $pieceNumber];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->query($sql, $params);
            return $stmt->fetch() !== false;
            
        } catch (Exception $e) {
            error_log("Error checking piece number: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener piezas de una sección ordenadas por número
     */
    public function getBySectionId($sectionId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE section_id = ? ORDER BY piece_number ASC";
            $stmt = $this->db->query($sql, [$sectionId]);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error getting pieces by section: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Encontrar pieza por ID
     */
    public function findById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = ?";
            $stmt = $this->db->query($sql, [$id]);
            return $stmt->fetch();
            
        } catch (Exception $e) {
            error_log("Error finding piece by ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar todas las piezas de una sección
     */
    public function deleteBySection($sectionId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE section_id = ?";
            return $this->db->query($sql, [$sectionId]);
            
        } catch (Exception $e) {
            error_log("Error deleting pieces by section: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Contar piezas de una sección
     */
    public function countBySection($sectionId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE section_id = ?";
            $stmt = $this->db->query($sql, [$sectionId]);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
            
        } catch (Exception $e) {
            error_log("Error counting pieces by section: " . $e->getMessage());
            return 0;
        }
    }
}
