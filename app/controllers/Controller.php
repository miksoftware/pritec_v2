<?php
/**
 * Clase base para controladores
 */
class Controller {
    /**
     * Constructor de la clase
     */
    public function __construct() {
        // Iniciar sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Renderiza una vista
     * 
     * @param string $view Nombre de la vista
     * @param array $data Datos para la vista
     */
    protected function render($view, $data = []) {
        // Extraer los datos para que estén disponibles como variables
        extract($data);
        
        // Incluir el encabezado
        require_once APP_PATH . '/views/layouts/header.php';
        
        // Incluir la vista
        require_once APP_PATH . '/views/' . $view . '.php';
        
        // Incluir el pie de página
        require_once APP_PATH . '/views/layouts/footer.php';
    }
    
    /**
     * Renderiza una vista de error
     * 
     * @param string $message Mensaje de error
     */
    protected function renderError($message) {
        $data = [
            'title' => 'Error',
            'message' => $message
        ];
        
        $this->render('error', $data);
    }
    
    /**
     * Genera un token CSRF
     * 
     * @return string Token CSRF
     */
    protected function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Valida el token CSRF
     * 
     * @throws Exception si el token no es válido
     */
    protected function validateCSRFToken() {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception('Token CSRF no válido');
        }
    }
}
