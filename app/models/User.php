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
}
