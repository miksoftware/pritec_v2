<?php

class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new Exception("Vista no encontrada: $view");
        }
    }
    
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function redirect($url) {
        header('Location: ' . APP_URL . ltrim($url, '/'));
        exit;
    }
    
    protected function back() {
        $referer = $_SERVER['HTTP_REFERER'] ?? APP_URL;
        header('Location: ' . $referer);
        exit;
    }
    
    protected function input($key, $default = null) {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
    
    protected function validate($rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $this->input($field);
            $ruleArray = explode('|', $rule);
            
            foreach ($ruleArray as $r) {
                if ($r === 'required' && empty($value)) {
                    $errors[$field] = "El campo $field es requerido";
                    break;
                }
                
                if (strpos($r, 'min:') === 0 && strlen($value) < intval(substr($r, 4))) {
                    $errors[$field] = "El campo $field debe tener al menos " . substr($r, 4) . " caracteres";
                    break;
                }
                
                if (strpos($r, 'max:') === 0 && strlen($value) > intval(substr($r, 4))) {
                    $errors[$field] = "El campo $field no puede tener más de " . substr($r, 4) . " caracteres";
                    break;
                }
                
                if ($r === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "El campo $field debe ser un email válido";
                    break;
                }
                
                if ($r === 'unique_email') {
                    $user = new User();
                    if ($user->findByEmail($value)) {
                        $errors[$field] = "Este email ya está registrado";
                        break;
                    }
                }
                
                if ($r === 'unique_username') {
                    $user = new User();
                    if ($user->findByUsername($value)) {
                        $errors[$field] = "Este nombre de usuario ya está en uso";
                        break;
                    }
                }
            }
        }
        
        return $errors;
    }
    
    protected function generateCSRFToken() {
        if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        return $_SESSION[CSRF_TOKEN_NAME];
    }
    
    protected function verifyCSRFToken() {
        $token = $this->input(CSRF_TOKEN_NAME);
        return isset($_SESSION[CSRF_TOKEN_NAME]) && hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
    }
}
