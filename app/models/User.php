<?php

require_once APP_PATH . '/core/Model.php';

class User extends Model {
    protected $table = 'users';
    protected $fillable = [
        'username', 
        'full_name', 
        'email', 
        'password', 
        'status'
    ];
    
    public function findByEmail($email) {
        return $this->findBy('email', $email);
    }
    
    public function findByUsername($username) {
        return $this->findBy('username', $username);
    }
    
    public function createUser($data) {
        // Hash de la contraseña
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], HASH_ALGO);
        }
        
        // Estado activo por defecto
        $data['status'] = $data['status'] ?? 'active';
        
        return $this->create($data);
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function updateLastLogin($userId) {
        $sql = "UPDATE {$this->table} SET last_login = NOW(), updated_at = NOW() WHERE id = ?";
        return $this->db->query($sql, [$userId]);
    }
    
    public function recordLogin($userId, $ipAddress = null, $userAgent = null) {
        $ipAddress = $ipAddress ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $userAgent ?? $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        
        $sql = "INSERT INTO user_login_logs (user_id, ip_address, user_agent, login_at) VALUES (?, ?, ?, NOW())";
        return $this->db->query($sql, [$userId, $ipAddress, $userAgent]);
    }
    
    public function getLoginHistory($userId, $limit = 10) {
        $sql = "SELECT * FROM user_login_logs WHERE user_id = ? ORDER BY login_at DESC LIMIT ?";
        $stmt = $this->db->query($sql, [$userId, $limit]);
        return $stmt->fetchAll();
    }
    
    public function isActive($userId) {
        $user = $this->find($userId);
        return $user && $user['status'] === 'active';
    }
    
    public function activate($userId) {
        return $this->update($userId, ['status' => 'active']);
    }
    
    public function deactivate($userId) {
        return $this->update($userId, ['status' => 'inactive']);
    }
    
    public function getActiveUsers() {
        return $this->where(['status' => 'active']);
    }
    
    public function getInactiveUsers() {
        return $this->where(['status' => 'inactive']);
    }
    
    /**
     * Obtener todos los usuarios para administración
     */
    public function getAllUsers() {
        try {
            $sql = "SELECT id, username, email, full_name, status, created_at, last_login 
                    FROM {$this->table} 
                    ORDER BY created_at DESC";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log("Error getting all users: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Buscar usuario por ID
     */
    public function findById($id) {
        try {
            $sql = "SELECT id, username, email, full_name, status, created_at, last_login 
                    FROM {$this->table} 
                    WHERE id = ?";
            
            $stmt = $this->db->query($sql, [$id]);
            return $stmt->fetch();
            
        } catch (Exception $e) {
            error_log("Error finding user by ID: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Crear usuario con validaciones
     */
    public function create($data) {
        try {
            // Preparar datos para inserción
            $userData = [
                'username' => $data['username'],
                'email' => $data['email'],
                'full_name' => $data['full_name'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'status' => $data['status'] ?? 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $columns = implode(',', array_keys($userData));
            $placeholders = ':' . implode(', :', array_keys($userData));
            
            $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
            $this->db->query($sql, $userData);
            
            return $this->db->lastInsertId();
            
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar usuario
     */
    public function update($id, $data) {
        try {
            // Preparar campos a actualizar
            $updateData = [];
            
            if (isset($data['username'])) {
                $updateData['username'] = $data['username'];
            }
            
            if (isset($data['email'])) {
                $updateData['email'] = $data['email'];
            }
            
            if (isset($data['full_name'])) {
                $updateData['full_name'] = $data['full_name'];
            }
            
            if (isset($data['password'])) {
                $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
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
            error_log("Error updating user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Eliminar usuario
     */
    public function delete($id) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            return $this->db->query($sql, [$id]);
            
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualizar solo el estado del usuario
     */
    public function updateStatus($id, $status) {
        try {
            $sql = "UPDATE {$this->table} SET status = ?, updated_at = ? WHERE id = ?";
            return $this->db->query($sql, [$status, date('Y-m-d H:i:s'), $id]);
            
        } catch (Exception $e) {
            error_log("Error updating user status: " . $e->getMessage());
            return false;
        }
    }
}
