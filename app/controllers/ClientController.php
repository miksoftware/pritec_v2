<?php
/**
 * Controlador de Clientes
 * Maneja todas las operaciones CRUD para clientes
 */

require_once APP_PATH . '/models/Client.php';

class ClientController extends Controller {
    private $clientModel;
    
    public function __construct() {
        // Verificar que el usuario esté logueado
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header('Location: ' . APP_URL . 'login');
            exit();
        }
        
        parent::__construct();
        $this->clientModel = new Client();
    }
    
    /**
     * Mostrar lista de clientes
     */
    public function index() {
        try {
            $page = (int)($_GET['page'] ?? 1);
            $limit = 10;
            $search = $_GET['search'] ?? '';
            $status = $_GET['status'] ?? '';
            
            $clients = $this->clientModel->getAll($page, $limit, $search, $status);
            $totalClients = $this->clientModel->count($search, $status);
            $totalPages = ceil($totalClients / $limit);
            
            // Obtener estadísticas
            $stats = $this->clientModel->getStats();
            
            $data = [
                'clients' => $clients,
                'totalClients' => $totalClients,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'search' => $search,
                'status' => $status,
                'stats' => $stats,
                'title' => 'Gestión de Clientes',
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('clients/index', $data);
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create() {
        $data = [
            'title' => 'Nuevo Cliente',
            'csrf_token' => $this->generateCSRFToken()
        ];
        
        $this->view('clients/create', $data);
    }
    
    /**
     * Procesar creación de cliente
     */
    public function store() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            $clientData = [
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'identification' => $_POST['identification'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'email' => $_POST['email'] ?? '',
                'status' => $_POST['status'] ?? 'active'
            ];
            
            $clientId = $this->clientModel->create($clientData);
            
            if ($this->isAjaxRequest()) {
                $this->json([
                    'success' => true,
                    'message' => 'Cliente creado exitosamente',
                    'client_id' => $clientId,
                    'redirect' => APP_URL . 'clients'
                ]);
            } else {
                $_SESSION['success'] = 'Cliente creado exitosamente';
                $this->redirect('clients');
            }
            
        } catch (Exception $e) {
            if ($this->isAjaxRequest()) {
                $this->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            } else {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('clients/create');
            }
        }
    }
    
    /**
     * Mostrar cliente específico
     */
    public function show($id) {
        try {
            $client = $this->clientModel->getById($id);
            
            if (!$client) {
                throw new Exception('Cliente no encontrado');
            }
            
            $data = [
                'client' => $client,
                'title' => 'Detalles del Cliente',
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('clients/show', $data);
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit($id) {
        try {
            $client = $this->clientModel->getById($id);
            
            if (!$client) {
                throw new Exception('Cliente no encontrado');
            }
            
            $data = [
                'client' => $client,
                'title' => 'Editar Cliente',
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('clients/edit', $data);
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }
    
    /**
     * Procesar actualización de cliente
     */
    public function update($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            $clientData = [
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'identification' => $_POST['identification'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'email' => $_POST['email'] ?? '',
                'status' => $_POST['status'] ?? 'active'
            ];
            
            $this->clientModel->update($id, $clientData);
            
            if ($this->isAjaxRequest()) {
                $this->json([
                    'success' => true,
                    'message' => 'Cliente actualizado exitosamente',
                    'redirect' => APP_URL . 'clients'
                ]);
            } else {
                $_SESSION['success'] = 'Cliente actualizado exitosamente';
                $this->redirect('clients');
            }
            
        } catch (Exception $e) {
            if ($this->isAjaxRequest()) {
                $this->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            } else {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect("clients/edit/{$id}");
            }
        }
    }
    
    /**
     * Eliminar cliente (soft delete)
     */
    public function destroy($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            $this->clientModel->delete($id);
            
            if ($this->isAjaxRequest()) {
                $this->json([
                    'success' => true,
                    'message' => 'Cliente eliminado exitosamente'
                ]);
            } else {
                $_SESSION['success'] = 'Cliente eliminado exitosamente';
                $this->redirect('clients');
            }
            
        } catch (Exception $e) {
            if ($this->isAjaxRequest()) {
                $this->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            } else {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('clients');
            }
        }
    }
    
    /**
     * Desactivar cliente
     */
    public function deactivate($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            $this->clientModel->deactivate($id);
            
            if ($this->isAjaxRequest()) {
                $this->json([
                    'success' => true,
                    'message' => 'Cliente desactivado exitosamente'
                ]);
            } else {
                $_SESSION['success'] = 'Cliente desactivado exitosamente';
                $this->redirect('clients');
            }
            
        } catch (Exception $e) {
            if ($this->isAjaxRequest()) {
                $this->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            } else {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('clients');
            }
        }
    }
    
    /**
     * Activar cliente
     */
    public function activate($id) {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            $this->clientModel->activate($id);
            
            if ($this->isAjaxRequest()) {
                $this->json([
                    'success' => true,
                    'message' => 'Cliente activado exitosamente'
                ]);
            } else {
                $_SESSION['success'] = 'Cliente activado exitosamente';
                $this->redirect('clients');
            }
            
        } catch (Exception $e) {
            if ($this->isAjaxRequest()) {
                $this->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            } else {
                $_SESSION['error'] = $e->getMessage();
                $this->redirect('clients');
            }
        }
    }
    
    /**
     * Buscar clientes via AJAX
     */
    public function search() {
        try {
            $term = $_GET['term'] ?? '';
            $limit = (int)($_GET['limit'] ?? 10);
            
            if (strlen($term) < 2) {
                $this->json([
                    'success' => false,
                    'message' => 'El término de búsqueda debe tener al menos 2 caracteres'
                ]);
                return;
            }
            
            $clients = $this->clientModel->search($term, $limit);
            
            $this->json([
                'success' => true,
                'clients' => $clients
            ]);
            
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Obtener estadísticas de clientes
     */
    public function stats() {
        try {
            $stats = $this->clientModel->getStats();
            
            $this->json([
                'success' => true,
                'stats' => $stats
            ]);
            
        } catch (Exception $e) {
            $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Exportar clientes a CSV
     */
    public function export() {
        try {
            $search = $_GET['search'] ?? '';
            $status = $_GET['status'] ?? '';
            
            $clients = $this->clientModel->getAll(1, 10000, $search, $status); // Obtener todos
            
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="clientes_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // Escribir BOM para UTF-8
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Encabezados
            fputcsv($output, [
                'ID',
                'Nombres',
                'Apellidos',
                'Identificación',
                'Teléfono',
                'Dirección',
                'Correo Electrónico',
                'Estado',
                'Fecha de Creación',
                'Última Actualización'
            ]);
            
            // Datos
            foreach ($clients as $client) {
                fputcsv($output, [
                    $client['id'],
                    $client['first_name'],
                    $client['last_name'],
                    $client['identification'],
                    $client['phone'],
                    $client['address'],
                    $client['email'],
                    $client['status'] === 'active' ? 'Activo' : 'Inactivo',
                    $client['created_at'],
                    $client['updated_at']
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al exportar: ' . $e->getMessage();
            $this->redirect('clients');
        }
    }
    
    /**
     * Manejar errores
     */
    private function handleError($message) {
        if ($this->isAjaxRequest()) {
            $this->json([
                'success' => false,
                'message' => $message
            ]);
        } else {
            $_SESSION['error'] = $message;
            $this->redirect('clients');
        }
    }
}
