<?php

require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/models/User.php';

class AuthController extends Controller {
    private $user;
    
    public function __construct() {
        parent::__construct();
        $this->user = new User();
    }
    
    public function login() {
        // Si ya está autenticado, redirigir al dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        
        $this->view('auth/login', [
            'title' => 'Iniciar Sesión',
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function register() {
        // Si ya está autenticado, redirigir al dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        
        $this->view('auth/register', [
            'title' => 'Registrarse',
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('login');
        }
        
        // Verificar token CSRF
        if (!$this->verifyCSRFToken()) {
            $this->json([
                'success' => false,
                'message' => 'Token de seguridad inválido'
            ], 403);
        }
        
        // Validar datos
        $errors = $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!empty($errors)) {
            $this->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $errors
            ], 400);
        }
        
        $email = $this->input('email');
        $password = $this->input('password');
        
        // Buscar usuario
        $userData = $this->user->findByEmail($email);
        
        if (!$userData) {
            $this->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }
        
        // Verificar si el usuario está activo
        if ($userData['status'] !== 'active') {
            $this->json([
                'success' => false,
                'message' => 'Tu cuenta está desactivada. Contacta al administrador.'
            ], 401);
        }
        
        // Verificar contraseña
        if (!$this->user->verifyPassword($password, $userData['password'])) {
            $this->json([
                'success' => false,
                'message' => 'Credenciales incorrectas'
            ], 401);
        }
        
        // Iniciar sesión
        $this->startUserSession($userData);
        
        // Registrar login
        $this->user->updateLastLogin($userData['id']);
        $this->user->recordLogin($userData['id']);
        
        $this->json([
            'success' => true,
            'message' => '¡Bienvenido! Iniciando sesión...',
            'redirect' => 'dashboard'
        ]);
    }
    
    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('register');
        }
        
        // Verificar token CSRF
        if (!$this->verifyCSRFToken()) {
            $this->json([
                'success' => false,
                'message' => 'Token de seguridad inválido'
            ], 403);
        }
        
        // Validar datos
        $errors = $this->validate([
            'username' => 'required|min:3|max:50|unique_username',
            'full_name' => 'required|min:2|max:100',
            'email' => 'required|email|unique_email',
            'password' => 'required|min:6',
            'password_confirm' => 'required'
        ]);
        
        // Verificar que las contraseñas coincidan
        if ($this->input('password') !== $this->input('password_confirm')) {
            $errors['password_confirm'] = 'Las contraseñas no coinciden';
        }
        
        if (!empty($errors)) {
            $this->json([
                'success' => false,
                'message' => 'Por favor corrige los errores',
                'errors' => $errors
            ], 400);
        }
        
        try {
            // Crear usuario
            $userId = $this->user->createUser([
                'username' => $this->input('username'),
                'full_name' => $this->input('full_name'),
                'email' => $this->input('email'),
                'password' => $this->input('password'),
                'status' => 'active'
            ]);
            
            $this->json([
                'success' => true,
                'message' => '¡Registro exitoso! Ya puedes iniciar sesión.',
                'redirect' => 'login'
            ]);
            
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => 'Error al crear la cuenta. Intenta de nuevo.'
            ], 500);
        }
    }
    
    public function logout() {
        // Destruir sesión
        session_destroy();
        
        // Regenerar ID de sesión
        session_start();
        session_regenerate_id(true);
        
        $this->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente',
            'redirect' => 'login'
        ]);
    }
    
    private function startUserSession($userData) {
        // Regenerar ID de sesión por seguridad
        session_regenerate_id(true);
        
        // Establecer datos de sesión
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['full_name'] = $userData['full_name'];
        $_SESSION['email'] = $userData['email'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
    }
    
    public function checkAuth() {
        $isAuthenticated = isset($_SESSION['user_id']) && 
                          isset($_SESSION['logged_in']) && 
                          $_SESSION['logged_in'] === true;
        
        if ($isAuthenticated) {
            // Verificar si el usuario sigue activo
            $user = $this->user->find($_SESSION['user_id']);
            if (!$user || $user['status'] !== 'active') {
                session_destroy();
                $isAuthenticated = false;
            }
        }
        
        $this->json([
            'authenticated' => $isAuthenticated,
            'user' => $isAuthenticated ? [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'full_name' => $_SESSION['full_name'],
                'email' => $_SESSION['email']
            ] : null
        ]);
    }
}
