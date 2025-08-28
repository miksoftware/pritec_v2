<?php

require_once APP_PATH . '/models/User.php';

class UserController extends Controller
{
    private $userModel;
    
    public function __construct()
    {
        // Verificar que el usuario esté logueado
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header('Location: ' . APP_URL . 'login');
            exit();
        }
        
        $this->userModel = new User();
    }
    
    /**
     * Listar todos los usuarios
     */
    public function index()
    {
        try {
            $users = $this->userModel->getAllUsers();
            $data = [
                'title' => 'Gestión de Usuarios - Pritec v2.0',
                'users' => $users,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('users/index', $data);
        } catch (Exception $e) {
            error_log("Error en UserController::index: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar la lista de usuarios';
            header('Location: ' . APP_URL . 'dashboard');
            exit();
        }
    }
    
    /**
     * Mostrar formulario para crear usuario
     */
    public function create()
    {
        $data = [
            'title' => 'Crear Usuario - Pritec v2.0',
            'csrf_token' => $this->generateCSRFToken()
        ];
        
        $this->view('users/create', $data);
    }
    
    /**
     * Guardar nuevo usuario
     */
    public function store()
    {
        header('Content-Type: application/json');
        
        try {
            // Verificar que sea POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            // Validar token CSRF
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token de seguridad inválido');
            }
            
            // Validar datos requeridos
            $required = ['username', 'email', 'full_name', 'password', 'confirm_password'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            // Validar que las contraseñas coincidan
            if ($_POST['password'] !== $_POST['confirm_password']) {
                throw new Exception('Las contraseñas no coinciden');
            }
            
            // Validar longitud de contraseña
            if (strlen($_POST['password']) < 6) {
                throw new Exception('La contraseña debe tener al menos 6 caracteres');
            }
            
            // Validar email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('El email no es válido');
            }
            
            // Verificar que no exista el usuario o email
            if ($this->userModel->findByUsername($_POST['username'])) {
                throw new Exception('El nombre de usuario ya existe');
            }
            
            if ($this->userModel->findByEmail($_POST['email'])) {
                throw new Exception('El email ya está registrado');
            }
            
            // Crear usuario
            $userData = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'full_name' => trim($_POST['full_name']),
                'password' => $_POST['password'],
                'status' => $_POST['status'] ?? 'active'
            ];
            
            $userId = $this->userModel->create($userData);
            
            if ($userId) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario creado exitosamente',
                    'redirect' => 'users'
                ]);
            } else {
                throw new Exception('Error al crear el usuario en la base de datos');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Mostrar formulario para editar usuario
     */
    public function edit($id)
    {
        try {
            $user = $this->userModel->findById($id);
            
            if (!$user) {
                $_SESSION['error'] = 'Usuario no encontrado';
                header('Location: ' . APP_URL . 'users');
                exit();
            }
            
            $data = [
                'title' => 'Editar Usuario - Pritec v2.0',
                'user' => $user,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('users/edit', $data);
        } catch (Exception $e) {
            error_log("Error en UserController::edit: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar el usuario';
            header('Location: ' . APP_URL . 'users');
            exit();
        }
    }
    
    /**
     * Actualizar usuario
     */
    public function update($id)
    {
        header('Content-Type: application/json');
        
        try {
            // Verificar que sea POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            // Validar token CSRF
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token de seguridad inválido');
            }
            
            // Verificar que el usuario existe
            $user = $this->userModel->findById($id);
            if (!$user) {
                throw new Exception('Usuario no encontrado');
            }
            
            // Validar datos requeridos
            $required = ['username', 'email', 'full_name'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            // Validar email
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception('El email no es válido');
            }
            
            // Verificar que no exista otro usuario con el mismo username o email
            $existingUser = $this->userModel->findByUsername($_POST['username']);
            if ($existingUser && $existingUser['id'] != $id) {
                throw new Exception('El nombre de usuario ya existe');
            }
            
            $existingEmail = $this->userModel->findByEmail($_POST['email']);
            if ($existingEmail && $existingEmail['id'] != $id) {
                throw new Exception('El email ya está registrado');
            }
            
            // Preparar datos para actualizar
            $userData = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'full_name' => trim($_POST['full_name']),
                'status' => $_POST['status'] ?? 'active'
            ];
            
            // Si se proporciona nueva contraseña
            if (!empty($_POST['password'])) {
                if ($_POST['password'] !== $_POST['confirm_password']) {
                    throw new Exception('Las contraseñas no coinciden');
                }
                
                if (strlen($_POST['password']) < 6) {
                    throw new Exception('La contraseña debe tener al menos 6 caracteres');
                }
                
                $userData['password'] = $_POST['password'];
            }
            
            // Actualizar usuario
            if ($this->userModel->update($id, $userData)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario actualizado exitosamente',
                    'redirect' => 'users'
                ]);
            } else {
                throw new Exception('Error al actualizar el usuario');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Eliminar usuario
     */
    public function delete($id)
    {
        header('Content-Type: application/json');
        
        try {
            // Verificar que sea POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            // Validar token CSRF
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['csrf_token']) || !isset($_SESSION[CSRF_TOKEN_NAME]) || !hash_equals($_SESSION[CSRF_TOKEN_NAME], $input['csrf_token'])) {
                throw new Exception('Token de seguridad inválido');
            }
            
            // Verificar que el usuario existe
            $user = $this->userModel->findById($id);
            if (!$user) {
                throw new Exception('Usuario no encontrado');
            }
            
            // No permitir eliminar el usuario actual
            if ($id == $_SESSION['user_id']) {
                throw new Exception('No puedes eliminar tu propia cuenta');
            }
            
            // Eliminar usuario
            if ($this->userModel->delete($id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Usuario eliminado exitosamente'
                ]);
            } else {
                throw new Exception('Error al eliminar el usuario');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Cambiar estado del usuario (activo/inactivo)
     */
    public function toggleStatus($id)
    {
        header('Content-Type: application/json');
        
        try {
            // Verificar que sea POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            // Validar token CSRF
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['csrf_token']) || !isset($_SESSION[CSRF_TOKEN_NAME]) || !hash_equals($_SESSION[CSRF_TOKEN_NAME], $input['csrf_token'])) {
                throw new Exception('Token de seguridad inválido');
            }
            
            // Verificar que el usuario existe
            $user = $this->userModel->findById($id);
            if (!$user) {
                throw new Exception('Usuario no encontrado');
            }
            
            // No permitir inactivar el usuario actual
            if ($id == $_SESSION['user_id']) {
                throw new Exception('No puedes cambiar el estado de tu propia cuenta');
            }
            
            // Cambiar estado
            $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
            
            if ($this->userModel->updateStatus($id, $newStatus)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Estado del usuario actualizado exitosamente',
                    'new_status' => $newStatus
                ]);
            } else {
                throw new Exception('Error al cambiar el estado del usuario');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
