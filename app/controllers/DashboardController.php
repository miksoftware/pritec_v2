<?php

require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/User.php';

class DashboardController extends Controller {
    private $user;
    
    public function __construct() {
        parent::__construct();
        $this->user = new User();
        $this->checkAuthentication();
    }
    
    private function checkAuthentication() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $this->redirect('login');
        }
        
        // Verificar si el usuario sigue activo
        $userData = $this->user->find($_SESSION['user_id']);
        if (!$userData || $userData['status'] !== 'active') {
            session_destroy();
            $this->redirect('login');
        }
    }
    
    public function index() {
        $userId = $_SESSION['user_id'];
        $userData = $this->user->find($userId);
        $loginHistory = $this->user->getLoginHistory($userId, 5);
        
        $this->view('dashboard/index', [
            'title' => 'Dashboard - ' . APP_NAME,
            'user' => $userData,
            'login_history' => $loginHistory
        ]);
    }
}
