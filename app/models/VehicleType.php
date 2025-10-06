<?php

require_once APP_PATH . '/core/Model.php';

class VehicleType extends Model {
    protected $table = 'vehicle_types';
    protected $fillable = [
        'type', 
        'name', 
        'description', 
        'status'
    ];
    
    /**
     * Obtener todos los tipos de vehículos
     */
    public function getAllVehicleTypes() {
        try {
            $sql = "SELECT id, type, name, description, status, created_at 
                    FROM {$this->table} 
                    ORDER BY created_at DESC";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error getting all vehicle types: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Buscar tipo de vehículo por ID
     */
    public function findById($id) {
        try {
            $sql = "SELECT id, type, name, description, status, created_at 
                    FROM {$this->table} 
                    WHERE id = ?";
            
            $stmt = $this->db->query($sql, [$id]);
            return $stmt->fetch();
            
        } catch (Exception $e) {
            error_log("Error finding vehicle type by ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crear tipo de vehículo
     */
    public function create($data) {
        try {
            $vehicleData = [
                'type' => $data['type'],
                'name' => $data['name'],
                'description' => $data['description'] ?? '',
                'status' => $data['status'] ?? 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $columns = implode(',', array_keys($vehicleData));
            $placeholders = ':' . implode(', :', array_keys($vehicleData));
            
            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
            $this->db->query($sql, $vehicleData);
            
            return $this->db->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Error creating vehicle type: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar tipo de vehículo
     */
    public function update($id, $data) {
        try {
            $updateData = [];
            
            if (isset($data['type'])) {
                $updateData['type'] = $data['type'];
            }
            
            if (isset($data['name'])) {
                $updateData['name'] = $data['name'];
            }
            
            if (isset($data['description'])) {
                $updateData['description'] = $data['description'];
            }
            
            if (isset($data['status'])) {
                $updateData['status'] = $data['status'];
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
            error_log("Error updating vehicle type: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar tipo de vehículo
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            return $this->db->query($sql, [$id]);
            
        } catch (Exception $e) {
            error_log("Error deleting vehicle type: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar solo el estado
     */
    public function updateStatus($id, $status) {
        try {
            $sql = "UPDATE {$this->table} SET status = ?, updated_at = ? WHERE id = ?";
            return $this->db->query($sql, [$status, date('Y-m-d H:i:s'), $id]);
            
        } catch (Exception $e) {
            error_log("Error updating vehicle type status: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener secciones de un tipo de vehículo
     */
    public function getSections($vehicleTypeId) {
        try {
            $sql = "SELECT vs.*, 
                           COUNT(vp.id) as pieces_count
                    FROM vehicle_sections vs 
                    LEFT JOIN vehicle_pieces vp ON vs.id = vp.section_id 
                    WHERE vs.vehicle_type_id = ? 
                    GROUP BY vs.id 
                    ORDER BY 
                        CASE vs.section_name 
                            WHEN 'carroceria' THEN 1 
                            WHEN 'estructura' THEN 2 
                            WHEN 'chasis' THEN 3 
                        END";
            
            $stmt = $this->db->query($sql, [$vehicleTypeId]);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error getting vehicle sections: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Verificar si el nombre ya existe
     */
    public function nameExists($name, $excludeId = null) {
        try {
            $sql = "SELECT id FROM {$this->table} WHERE name = ?";
            $params = [$name];
            
            if ($excludeId) {
                $sql .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            $stmt = $this->db->query($sql, $params);
            return $stmt->fetch() !== false;
            
        } catch (Exception $e) {
            error_log("Error checking vehicle type name: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener todos los tipos de vehículos activos
     */
    public function getAllActive() {
        try {
            $sql = "SELECT id, name 
                    FROM {$this->table} 
                    WHERE status = 'active'
                    ORDER BY name";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error getting active vehicle types: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener tipos de vehículos con paginación y búsqueda
     */
    public function getAllWithPagination($page = 1, $limit = 20, $search = '') {
        try {
            $offset = ($page - 1) * $limit;
            $params = [];
            
            $sql = "SELECT id, type, name, description, status, created_at 
                    FROM {$this->table}";
            
            // Agregar búsqueda si existe
            if (!empty($search)) {
                $sql .= " WHERE name LIKE ? OR description LIKE ?";
                $searchParam = "%{$search}%";
                $params = [$searchParam, $searchParam];
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}";
            
            $stmt = $this->db->query($sql, $params);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error getting vehicle types with pagination: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Contar total de tipos de vehículos (con filtro de búsqueda opcional)
     */
    public function count($search = '') {
        try {
            $params = [];
            
            $sql = "SELECT COUNT(*) as total FROM {$this->table}";
            
            // Agregar búsqueda si existe
            if (!empty($search)) {
                $sql .= " WHERE name LIKE ? OR description LIKE ?";
                $searchParam = "%{$search}%";
                $params = [$searchParam, $searchParam];
            }
            
            $stmt = $this->db->query($sql, $params);
            $result = $stmt->fetch();
            
            return (int)$result['total'];
            
        } catch (Exception $e) {
            error_log("Error counting vehicle types: " . $e->getMessage());
            return 0;
        }
    }
}
