<?php
/**
 * Modelo Client
 * Maneja las operaciones CRUD para la tabla de clientes
 */

require_once APP_PATH . '/core/Model.php';

class Client extends Model {
    protected $table = 'clients';
    protected $fillable = [
        'first_name', 
        'last_name', 
        'identification', 
        'phone', 
        'address', 
        'email', 
        'status'
    ];
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Obtener todos los clientes con paginación y filtros
     */
    public function getAll($page = 1, $limit = 10, $search = '', $status = '') {
        $offset = ($page - 1) * $limit;
        
        $whereConditions = ["status != 'deleted'"]; // Excluir eliminados
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = "(first_name LIKE ? OR last_name LIKE ? OR identification LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($status)) {
            $whereConditions[] = "status = ?";
            $params[] = $status;
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        
        $sql = "SELECT * FROM clients {$whereClause} ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Contar total de clientes con filtros
     */
    public function count($search = '', $status = '') {
        $whereConditions = ["status != 'deleted'"]; // Excluir eliminados
        $params = [];
        
        if (!empty($search)) {
            $whereConditions[] = "(first_name LIKE ? OR last_name LIKE ? OR identification LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($status)) {
            $whereConditions[] = "status = ?";
            $params[] = $status;
        }
        
        $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
        
        $sql = "SELECT COUNT(*) as total FROM clients {$whereClause}";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
    /**
     * Obtener cliente por ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM clients WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear nuevo cliente
     */
    public function create($data) {
        // Validar datos requeridos
        $requiredFields = ['first_name', 'last_name', 'identification', 'phone', 'address', 'email'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("El campo {$field} es requerido");
            }
        }
        
        // Validar email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El email no tiene un formato válido");
        }
        
        // Verificar que no exista identificación duplicada
        if ($this->existsByIdentification($data['identification'])) {
            throw new Exception("Ya existe un cliente con esta identificación");
        }
        
        // Verificar que no exista email duplicado
        if ($this->existsByEmail($data['email'])) {
            throw new Exception("Ya existe un cliente con este email");
        }
        
        $sql = "INSERT INTO clients (first_name, last_name, identification, phone, address, email, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $result = $stmt->execute([
            trim($data['first_name']),
            trim($data['last_name']),
            trim($data['identification']),
            trim($data['phone']),
            trim($data['address']),
            trim($data['email']),
            $data['status'] ?? 'active'
        ]);
        
        if ($result) {
            return $this->db->getConnection()->lastInsertId();
        }
        
        throw new Exception("Error al crear el cliente");
    }
    
    /**
     * Actualizar cliente
     */
    public function update($id, $data) {
        // Validar que el cliente existe
        if (!$this->getById($id)) {
            throw new Exception("Cliente no encontrado");
        }
        
        // Validar datos requeridos
        $requiredFields = ['first_name', 'last_name', 'identification', 'phone', 'address', 'email'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("El campo {$field} es requerido");
            }
        }
        
        // Validar email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El email no tiene un formato válido");
        }
        
        // Verificar identificación duplicada (excluyendo el actual)
        if ($this->existsByIdentification($data['identification'], $id)) {
            throw new Exception("Ya existe otro cliente con esta identificación");
        }
        
        // Verificar email duplicado (excluyendo el actual)
        if ($this->existsByEmail($data['email'], $id)) {
            throw new Exception("Ya existe otro cliente con este email");
        }
        
        $sql = "UPDATE clients SET 
                first_name = ?, 
                last_name = ?, 
                identification = ?, 
                phone = ?, 
                address = ?, 
                email = ?, 
                status = ?,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $result = $stmt->execute([
            trim($data['first_name']),
            trim($data['last_name']),
            trim($data['identification']),
            trim($data['phone']),
            trim($data['address']),
            trim($data['email']),
            $data['status'] ?? 'active',
            $id
        ]);
        
        if (!$result) {
            throw new Exception("Error al actualizar el cliente");
        }
        
        return true;
    }
    
    /**
     * Eliminar cliente (soft delete)
     */
    public function delete($id) {
        // Validar que el cliente existe
        if (!$this->getById($id)) {
            throw new Exception("Cliente no encontrado");
        }
        
        $sql = "UPDATE clients SET status = 'deleted', updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $result = $stmt->execute([$id]);
        
        if (!$result) {
            throw new Exception("Error al eliminar el cliente");
        }
        
        return true;
    }
    
    /**
     * Desactivar cliente (marcar como inactivo)
     */
    public function deactivate($id) {
        // Validar que el cliente existe
        if (!$this->getById($id)) {
            throw new Exception("Cliente no encontrado");
        }
        
        $sql = "UPDATE clients SET status = 'inactive', updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $result = $stmt->execute([$id]);
        
        if (!$result) {
            throw new Exception("Error al desactivar el cliente");
        }
        
        return true;
    }
    
    /**
     * Activar cliente
     */
    public function activate($id) {
        $sql = "UPDATE clients SET status = 'active', updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $result = $stmt->execute([$id]);
        
        if (!$result) {
            throw new Exception("Error al activar el cliente");
        }
        
        return true;
    }
    
    /**
     * Verificar si existe cliente por identificación
     */
    public function existsByIdentification($identification, $excludeId = null) {
        $sql = "SELECT id FROM clients WHERE identification = ?";
        $params = [$identification];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    
    /**
     * Verificar si existe cliente por email
     */
    public function existsByEmail($email, $excludeId = null) {
        $sql = "SELECT id FROM clients WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    
    /**
     * Buscar clientes por término
     */
    public function search($term, $limit = 10) {
        $sql = "SELECT id, CONCAT(first_name, ' ', last_name) as full_name, identification, email, phone
                FROM clients 
                WHERE status = 'active' 
                AND (first_name LIKE ? OR last_name LIKE ? OR identification LIKE ? OR email LIKE ?)
                ORDER BY first_name, last_name
                LIMIT ?";
        
        $searchTerm = "%{$term}%";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $limit]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener todos los clientes activos
     */
    public function getAllActive() {
        $sql = "SELECT id, CONCAT(first_name, ' ', last_name) as name, identification 
                FROM clients 
                WHERE status = 'active'
                ORDER BY first_name, last_name";
                
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener estadísticas de clientes
     */
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN status = 'deleted' THEN 1 ELSE 0 END) as deleted,
                SUM(CASE WHEN DATE(created_at) = CURDATE() AND status != 'deleted' THEN 1 ELSE 0 END) as today,
                SUM(CASE WHEN WEEK(created_at) = WEEK(NOW()) AND YEAR(created_at) = YEAR(NOW()) AND status != 'deleted' THEN 1 ELSE 0 END) as this_week,
                SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) AND status != 'deleted' THEN 1 ELSE 0 END) as this_month
                FROM clients";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();
        
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Restar los eliminados del total para mostrar solo activos e inactivos
        $stats['total'] = $stats['total'] - $stats['deleted'];
        
        return $stats;
    }
}
