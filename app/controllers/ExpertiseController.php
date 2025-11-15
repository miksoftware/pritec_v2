<?php
/**
 * Controlador de Peritajes
 * Maneja todas las operaciones para peritajes completos y básicos
 */

require_once APP_PATH . '/models/Expertise.php';

class ExpertiseController extends Controller {
    
    private $expertiseModel;
    
    public function __construct() {
        // Verificar que el usuario esté logueado
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header('Location: ' . APP_URL . 'login');
            exit();
        }
        
        parent::__construct();
        
        // Inicializar modelo
        $this->expertiseModel = new Expertise();
    }
    
    /**
     * Mostrar lista de peritajes completos
     */
    public function index() {
        try {
            // Obtener filtros y paginación
            $search = $_GET['search'] ?? '';
            $month = $_GET['month'] ?? '';
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = 20; // Registros por página
            
            // Obtener peritajes en progreso del usuario
            $inProgress = $this->expertiseModel->getInProgressByUser($_SESSION['user_id']);
            
            // Obtener peritajes completados con paginación
            $expertises = $this->expertiseModel->getAllWithRelations($page, $limit, $search, $month);
            
            // Obtener total para la paginación
            $totalRecords = $this->expertiseModel->count($search, $month);
            $totalPages = ceil($totalRecords / $limit);
            
            // Generar HTML de paginación (siempre se mostrará la info del total)
            $pagination = renderPagination($page, $totalPages, $totalRecords, '', $limit);
            
            $data = [
                'title' => 'Peritajes Completos',
                'expertises' => $expertises,
                'inProgress' => $inProgress,
                'pagination' => $pagination,
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_records' => $totalRecords,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('expertise/index', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al cargar peritajes: ' . $e->getMessage();
            $this->redirect('dashboard');
        }
    }
    
    /**
     * Mostrar formulario para crear nuevo peritaje (Paso 1)
     */
    public function create() {
        try {
            $data = [
                'title' => 'Nuevo Peritaje Completo - Paso 1',
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('expertise/create', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise');
        }
    }
    
    /**
     * Guardar datos del paso 1 (Información del Servicio y Cliente)
     */
    public function store() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Validar datos requeridos
            $required = ['service_date', 'service_number', 'service_for', 'client_id'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo {$field} es requerido");
                }
            }
            
            // Verificar si ya existe un borrador del usuario
            $existingDraft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
            
            if ($existingDraft) {
                // Ya hay un borrador, preguntar al usuario qué hacer
                $_SESSION['pending_draft'] = $existingDraft['id'];
                $_SESSION['warning'] = 'Ya tienes un peritaje en progreso. ¿Deseas continuar con ese peritaje o crear uno nuevo?';
                $this->redirect('expertise/create');
                return;
            }
            
            // Preparar datos para crear borrador
            $data = [
                'client_id' => $_POST['client_id'],
                'user_id' => $_SESSION['user_id'],
                'service_date' => $_POST['service_date'],
                'service_number' => $_POST['service_number'],
                'service_for' => $_POST['service_for'],
                'agreement' => $_POST['agreement'] ?? '',
                'placa' => '' // Temporal, se completará en paso 2
            ];
            
            // Crear borrador en BD
            $expertiseId = $this->expertiseModel->createDraft($data);
            
            if (!$expertiseId) {
                throw new Exception('Error al crear el peritaje');
            }
            
            // Guardar ID en sesión
            $_SESSION['expertise_id'] = $expertiseId;
            
            // Mensaje de éxito
            $_SESSION['success'] = 'Peritaje iniciado correctamente. Continúa con los datos del vehículo.';
            
            // Redirigir al paso 2 (Datos del Vehículo)
            $this->redirect('expertise/step2');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/create');
        }
    }
    
    /**
     * Buscar clientes (AJAX)
     */
    public function searchClients() {
        try {
            header('Content-Type: application/json');
            
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            
            if (strlen($search) < 3) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Debe ingresar al menos 3 caracteres'
                ]);
                return;
            }
            
            // Usar modelo para buscar clientes
            $clients = $this->expertiseModel->searchClients($search);
            
            echo json_encode([
                'success' => true,
                'count' => count($clients),
                'clients' => $clients
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Mostrar paso 2: Datos del Vehículo
     */
    public function step2() {
        try {
            // Obtener expertise_id de la sesión o recuperar último borrador
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                // Intentar recuperar último borrador del usuario
                $draft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
                
                if ($draft) {
                    $expertiseId = $draft['id'];
                    $_SESSION['expertise_id'] = $expertiseId;
                } else {
                    $_SESSION['error'] = 'Debe completar el Paso 1 primero';
                    $this->redirect('expertise/create');
                    return;
                }
            }
            
            // Obtener datos del expertise desde BD
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                throw new Exception('Peritaje no encontrado');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                throw new Exception('No tienes permiso para editar este peritaje');
            }
            
            $data = [
                'title' => 'Nuevo Peritaje Completo - Paso 2',
                'csrf_token' => $this->generateCSRFToken(),
                'expertise' => $expertise
            ];
            
            $this->view('expertise/step2', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/create');
        }
    }
    
    /**
     * Buscar tipos de vehículos (AJAX)
     */
    public function searchVehicleTypes() {
        try {
            header('Content-Type: application/json');
            
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            
            // Usar modelo para buscar tipos de vehículos
            $vehicleTypes = $this->expertiseModel->searchVehicleTypes($search);
            
            echo json_encode([
                'success' => true,
                'count' => count($vehicleTypes),
                'vehicle_types' => $vehicleTypes
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Guardar datos del paso 2 (Datos del Vehículo)
     */
    public function saveStep2() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Obtener expertise_id
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                throw new Exception('No se encontró el peritaje en progreso');
            }
            
            // Validar datos requeridos
            if (empty($_POST['tipo_vehiculo']) || empty($_POST['placa'])) {
                throw new Exception('El tipo de vehículo y la placa son requeridos');
            }
            
            // Preparar datos para actualizar
            $vehicleTypeId = intval($_POST['tipo_vehiculo']); // ID del tipo de vehículo
            
            $data = [
                'tipo_vehiculo' => $vehicleTypeId,  // ID numérico del tipo de vehículo
                'placa' => $_POST['placa'],
                'marca' => $_POST['marca'] ?? null,
                'linea' => $_POST['linea'] ?? null,
                'modelo' => $_POST['modelo'] ?? null,
                'color' => $_POST['color'] ?? null,
                'clase_vehiculo' => $_POST['clase'] ?? null,
                'tipo_carroceria' => $_POST['tipo_carroceria'] ?? null,
                'tipo_combustible' => null,
                'numero_motor' => $_POST['no_motor'] ?? null,
                'numero_chasis' => $_POST['no_chasis'] ?? null,
                'numero_serie' => $_POST['no_serie'] ?? null,
                'vin' => null,
                'kilometraje' => !empty($_POST['kilometraje']) ? intval($_POST['kilometraje']) : null,
                'cilindrada' => $_POST['cilindraje'] ?? null,
                'capacidad_carga' => null,
                'numero_ejes' => null,
                'numero_pasajeros' => null,
                'fecha_matricula' => null
            ];
            
            // Actualizar en BD
            $updated = $this->expertiseModel->updateStep2($expertiseId, $data);
            
            if (!$updated) {
                throw new Exception('Error al actualizar los datos del vehículo');
            }
            
            // Obtener el tipo de vehículo para decidir el siguiente paso
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            $vehicleType = $expertise['tipo_vehiculo_type'] ?? 'carro';
            
            $_SESSION['success'] = 'Datos del vehículo guardados correctamente';
            
            // Si es moto, saltar al paso 4 (las motos no tienen carrocería)
            if ($vehicleType === 'moto') {
                $this->redirect('expertise/step4');
            } else {
                // Si es carro, ir al paso 3 (Inspección Carrocería)
                $this->redirect('expertise/step3');
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step2');
        }
    }
    
    /**
     * Mostrar paso 3: Inspección Visual Externa (Carrocería)
     */
    public function step3() {
        try {
            // Obtener expertise_id de la sesión o recuperar último borrador
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                // Intentar recuperar último borrador del usuario
                $draft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
                
                if ($draft) {
                    $expertiseId = $draft['id'];
                    $_SESSION['expertise_id'] = $expertiseId;
                } else {
                    $_SESSION['error'] = 'Debe completar los pasos anteriores primero';
                    $this->redirect('expertise/create');
                    return;
                }
            }
            
            // Obtener datos del expertise desde BD
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                throw new Exception('Peritaje no encontrado');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                throw new Exception('No tienes permiso para editar este peritaje');
            }
            
            // Obtener inspecciones existentes de carrocería
            $existingInspections = $this->expertiseModel->getInspectionsBySection($expertiseId, 'carroceria');
            
            $data = [
                'title' => 'Nuevo Peritaje Completo - Paso 3',
                'csrf_token' => $this->generateCSRFToken(),
                'expertise' => $expertise,
                'existingInspections' => $existingInspections
            ];
            
            $this->view('expertise/step3', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step2');
        }
    }
    
    /**
     * Obtener piezas de carrocería por tipo de vehículo (AJAX)
     */
    public function getPiecesByVehicleType() {
        try {
            header('Content-Type: application/json');
            
            $vehicleTypeId = isset($_GET['vehicle_type_id']) ? intval($_GET['vehicle_type_id']) : 0;
            $section = isset($_GET['section']) ? $_GET['section'] : 'carroceria';
            
            if ($vehicleTypeId <= 0) {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID de tipo de vehículo inválido'
                ]);
                return;
            }
            
            // Usar modelo para obtener piezas
            $pieces = $this->expertiseModel->getPiecesByVehicleTypeAndSection($vehicleTypeId, $section);
            
            echo json_encode([
                'success' => true,
                'count' => count($pieces),
                'pieces' => $pieces
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Obtener conceptos de inspección por categoría (AJAX)
     */
    public function getInspectionConcepts() {
        try {
            header('Content-Type: application/json');
            
            $category = isset($_GET['category']) ? $_GET['category'] : 'carroceria';
            
            // Usar modelo para obtener conceptos
            $concepts = $this->expertiseModel->getInspectionConceptsByCategory($category);
            
            echo json_encode([
                'success' => true,
                'count' => count($concepts),
                'concepts' => $concepts
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Guardar datos del paso 3 (Inspección Carrocería)
     */
    public function saveStep3() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Obtener expertise_id
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                throw new Exception('No se encontró el peritaje en progreso');
            }
            
            // Obtener arrays de piezas y conceptos
            $piezas = isset($_POST['pieza_id']) ? $_POST['pieza_id'] : [];
            $conceptos = isset($_POST['concepto_id']) ? $_POST['concepto_id'] : [];
            
            // Validar que haya al menos una inspección
            if (empty($piezas) || count($piezas) === 0) {
                throw new Exception('Debe agregar al menos una inspección de pieza');
            }
            
            // Construir array de inspecciones
            $inspecciones = [];
            for ($i = 0; $i < count($piezas); $i++) {
                if (!empty($piezas[$i]) && !empty($conceptos[$i])) {
                    $inspecciones[] = [
                        'pieza_id' => $piezas[$i],
                        'concepto_id' => $conceptos[$i]
                    ];
                }
            }
            
            // Validar que haya al menos una inspección válida
            if (empty($inspecciones)) {
                throw new Exception('Debe completar al menos una inspección válida');
            }
            
            // Obtener observación general
            $observacionGeneral = $_POST['observaciones_carroceria'] ?? null;
            
            // Actualizar inspecciones de carrocería en BD
            $updated = $this->expertiseModel->updateInspections(
                $expertiseId, 
                'carroceria', 
                $inspecciones,
                3,
                $observacionGeneral
            );
            
            if (!$updated) {
                throw new Exception('Error al guardar la inspección de carrocería');
            }
            
            $_SESSION['success'] = 'Inspección de carrocería guardada correctamente';
            
            // Redirigir al paso 4 (Estructura)
            $this->redirect('expertise/step4');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step3');
        }
    }
    
    /**
     * Mostrar el paso 4: Inspección Visual Interna (Estructura)
     */
    public function step4() {
        try {
            // Obtener expertise_id de la sesión o recuperar último borrador
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                // Intentar recuperar último borrador del usuario
                $draft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
                
                if ($draft) {
                    $expertiseId = $draft['id'];
                    $_SESSION['expertise_id'] = $expertiseId;
                } else {
                    $_SESSION['error'] = 'Debe completar los pasos anteriores primero';
                    $this->redirect('expertise/create');
                    return;
                }
            }
            
            // Obtener datos del expertise desde BD
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                throw new Exception('Peritaje no encontrado');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                throw new Exception('No tienes permiso para editar este peritaje');
            }
            
            // Obtener inspecciones existentes de estructura
            $existingInspections = $this->expertiseModel->getInspectionsBySection($expertiseId, 'estructura');
            
            $data = [
                'title' => 'Nuevo Peritaje Completo - Paso 4',
                'csrf_token' => $this->generateCSRFToken(),
                'expertise' => $expertise,
                'existingInspections' => $existingInspections
            ];
            
            $this->view('expertise/step4', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step3');
        }
    }
    
    /**
     * Guardar datos del paso 4 (Inspección Estructura)
     */
    public function saveStep4() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Obtener expertise_id
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                throw new Exception('No se encontró el peritaje en progreso');
            }
            
            // Obtener arrays de piezas y conceptos
            $piezas = isset($_POST['pieza_id']) ? $_POST['pieza_id'] : [];
            $conceptos = isset($_POST['concepto_id']) ? $_POST['concepto_id'] : [];
            
            // Validar que haya al menos una inspección
            if (empty($piezas) || count($piezas) === 0) {
                throw new Exception('Debe agregar al menos una inspección de pieza');
            }
            
            // Construir array de inspecciones
            $inspecciones = [];
            for ($i = 0; $i < count($piezas); $i++) {
                if (!empty($piezas[$i]) && !empty($conceptos[$i])) {
                    $inspecciones[] = [
                        'pieza_id' => $piezas[$i],
                        'concepto_id' => $conceptos[$i]
                    ];
                }
            }
            
            // Validar que haya al menos una inspección válida
            if (empty($inspecciones)) {
                throw new Exception('Debe completar al menos una inspección válida');
            }
            
            // Obtener observación general
            $observacionGeneral = $_POST['observaciones_estructura'] ?? null;
            
            // Actualizar inspecciones de estructura en BD
            $updated = $this->expertiseModel->updateInspections(
                $expertiseId, 
                'estructura', 
                $inspecciones,
                4,
                $observacionGeneral
            );
            
            if (!$updated) {
                throw new Exception('Error al guardar la inspección de estructura');
            }
            
            $_SESSION['success'] = 'Inspección de estructura guardada correctamente';
            
            // Redirigir al paso 5
            $this->redirect('expertise/step5');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step4');
        }
    }
    
    /**
     * Mostrar el paso 5: Inspección Chasis
     */
    public function step5() {
        try {
            // Obtener expertise_id de la sesión o recuperar último borrador
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                // Intentar recuperar último borrador del usuario
                $draft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
                
                if ($draft) {
                    $expertiseId = $draft['id'];
                    $_SESSION['expertise_id'] = $expertiseId;
                } else {
                    $_SESSION['error'] = 'Debe completar los pasos anteriores primero';
                    $this->redirect('expertise/create');
                    return;
                }
            }
            
            // Obtener datos del expertise desde BD
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                throw new Exception('Peritaje no encontrado');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                throw new Exception('No tienes permiso para editar este peritaje');
            }
            
            // Obtener inspecciones existentes de chasis
            $existingInspections = $this->expertiseModel->getInspectionsBySection($expertiseId, 'chasis');
            
            $data = [
                'title' => 'Nuevo Peritaje Completo - Paso 5',
                'csrf_token' => $this->generateCSRFToken(),
                'expertise' => $expertise,
                'existingInspections' => $existingInspections
            ];
            
            $this->view('expertise/step5', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step4');
        }
    }
    
    /**
     * Guardar datos del paso 5 (Inspección Chasis)
     */
    public function saveStep5() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Obtener expertise_id
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                throw new Exception('No se encontró el peritaje en progreso');
            }
            
            // Obtener arrays de piezas y conceptos
            $piezas = isset($_POST['pieza_id']) ? $_POST['pieza_id'] : [];
            $conceptos = isset($_POST['concepto_id']) ? $_POST['concepto_id'] : [];
            
            // Validar que haya al menos una inspección
            if (empty($piezas) || count($piezas) === 0) {
                throw new Exception('Debe agregar al menos una inspección de pieza');
            }
            
            // Construir array de inspecciones
            $inspecciones = [];
            for ($i = 0; $i < count($piezas); $i++) {
                if (!empty($piezas[$i]) && !empty($conceptos[$i])) {
                    $inspecciones[] = [
                        'pieza_id' => $piezas[$i],
                        'concepto_id' => $conceptos[$i]
                    ];
                }
            }
            
            // Validar que haya al menos una inspección válida
            if (empty($inspecciones)) {
                throw new Exception('Debe completar al menos una inspección válida');
            }
            
            // Obtener observación general
            $observacionGeneral = $_POST['observaciones_chasis'] ?? null;
            
            // Actualizar inspecciones de chasis en BD
            $updated = $this->expertiseModel->updateInspections(
                $expertiseId, 
                'chasis', 
                $inspecciones,
                5,
                $observacionGeneral
            );
            
            if (!$updated) {
                throw new Exception('Error al guardar la inspección de chasis');
            }
            
            $_SESSION['success'] = 'Inspección de chasis guardada correctamente';
            
            // Redirigir al paso 6
            $this->redirect('expertise/step6');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step5');
        }
    }
    
    /**
     * Mostrar el paso 6: Inspección de Llantas
     */
    public function step6() {
        try {
            // Obtener expertise_id de la sesión o recuperar último borrador
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                // Intentar recuperar último borrador del usuario
                $lastDraft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
                
                if (!$lastDraft) {
                    $_SESSION['error'] = 'No se encontró un peritaje en progreso. Por favor inicie uno nuevo.';
                    $this->redirect('expertise/create');
                    return;
                }
                
                $expertiseId = $lastDraft['id'];
                $_SESSION['expertise_id'] = $expertiseId;
            }
            
            // Obtener datos del expertise desde BD
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                $_SESSION['error'] = 'No se encontró el peritaje';
                $this->redirect('expertise/create');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = 'No tiene permisos para editar este peritaje';
                $this->redirect('expertise');
            }
            
            $data = [
                'csrf_token' => $this->generateCsrfToken(),
                'expertise' => $expertise
            ];
            
            $this->view('expertise/step6', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step5');
        }
    }
    
    /**
     * Guardar datos del paso 6 (Inspección Llantas)
     */
    public function saveStep6() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Obtener expertise_id
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                throw new Exception('No se encontró un peritaje en progreso');
            }
            
            // Obtener el tipo de vehículo para validación
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            $vehicleType = $expertise['tipo_vehiculo_type'] ?? 'carro';
            
            // Obtener datos de llantas
            $llanta_anterior_izquierda = isset($_POST['llanta_anterior_izquierda']) ? intval($_POST['llanta_anterior_izquierda']) : null;
            $llanta_anterior_derecha = isset($_POST['llanta_anterior_derecha']) ? intval($_POST['llanta_anterior_derecha']) : null;
            $llanta_posterior_izquierda = isset($_POST['llanta_posterior_izquierda']) ? intval($_POST['llanta_posterior_izquierda']) : null;
            $llanta_posterior_derecha = isset($_POST['llanta_posterior_derecha']) ? intval($_POST['llanta_posterior_derecha']) : null;
            
            // Validar según el tipo de vehículo
            if ($vehicleType === 'moto') {
                // Para motos: solo validar delantera derecha y trasera derecha
                if ($llanta_anterior_derecha === null || $llanta_anterior_derecha < 0 || $llanta_anterior_derecha > 100) {
                    throw new Exception('El porcentaje de la llanta delantera debe estar entre 0 y 100');
                }
                if ($llanta_posterior_derecha === null || $llanta_posterior_derecha < 0 || $llanta_posterior_derecha > 100) {
                    throw new Exception('El porcentaje de la llanta trasera debe estar entre 0 y 100');
                }
                // Las llantas izquierdas se guardan como 0 para motos
                $llanta_anterior_izquierda = 0;
                $llanta_posterior_izquierda = 0;
            } else {
                // Para carros: validar las 4 llantas
                $porcentajes = [
                    'anterior_izquierda' => $llanta_anterior_izquierda,
                    'anterior_derecha' => $llanta_anterior_derecha,
                    'posterior_izquierda' => $llanta_posterior_izquierda,
                    'posterior_derecha' => $llanta_posterior_derecha
                ];
                
                foreach ($porcentajes as $nombre => $valor) {
                    if ($valor === null || $valor < 0 || $valor > 100) {
                        throw new Exception('El porcentaje de la llanta ' . str_replace('_', ' ', $nombre) . ' debe estar entre 0 y 100');
                    }
                }
            }
            
            // Preparar datos para actualizar
            $data = [
                'llanta_anterior_izquierda' => $llanta_anterior_izquierda,
                'llanta_anterior_derecha' => $llanta_anterior_derecha,
                'llanta_posterior_izquierda' => $llanta_posterior_izquierda,
                'llanta_posterior_derecha' => $llanta_posterior_derecha,
                'observaciones_llantas' => $_POST['observaciones_llantas'] ?? null
            ];
            
            // Actualizar en BD
            $updated = $this->expertiseModel->updateStep6($expertiseId, $data);
            
            if (!$updated) {
                throw new Exception('Error al guardar los datos de llantas');
            }
            
            $_SESSION['success'] = 'Inspección de llantas guardada correctamente';
            
            // Redirigir al paso 7
            $this->redirect('expertise/step7');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step6');
        }
    }
    
    /**
     * Mostrar el paso 7: Inspección de Amortiguadores
     */
    public function step7() {
        try {
            // Obtener expertise_id de la sesión o recuperar último borrador
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                // Intentar recuperar último borrador del usuario
                $lastDraft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
                
                if (!$lastDraft) {
                    $_SESSION['error'] = 'No se encontró un peritaje en progreso. Por favor inicie uno nuevo.';
                    $this->redirect('expertise/create');
                    return;
                }
                
                $expertiseId = $lastDraft['id'];
                $_SESSION['expertise_id'] = $expertiseId;
            }
            
            // Obtener datos del expertise desde BD
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                $_SESSION['error'] = 'No se encontró el peritaje';
                $this->redirect('expertise/create');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = 'No tiene permisos para editar este peritaje';
                $this->redirect('expertise');
            }
            
            $data = [
                'csrf_token' => $this->generateCsrfToken(),
                'expertise' => $expertise
            ];
            
            $this->view('expertise/step7', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step6');
        }
    }
    
    /**
     * Guardar datos del paso 7 (Inspección Amortiguadores)
     */
    public function saveStep7() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Obtener expertise_id
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                throw new Exception('No se encontró un peritaje en progreso');
            }
            
            // Obtener el tipo de vehículo para validación
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            $vehicleType = $expertise['tipo_vehiculo_type'] ?? 'carro';
            
            // Obtener datos de amortiguadores
            $amortiguador_anterior_izquierdo = isset($_POST['amortiguador_anterior_izquierdo']) ? intval($_POST['amortiguador_anterior_izquierdo']) : null;
            $amortiguador_anterior_derecho = isset($_POST['amortiguador_anterior_derecho']) ? intval($_POST['amortiguador_anterior_derecho']) : null;
            $amortiguador_posterior_izquierdo = isset($_POST['amortiguador_posterior_izquierdo']) ? intval($_POST['amortiguador_posterior_izquierdo']) : null;
            $amortiguador_posterior_derecho = isset($_POST['amortiguador_posterior_derecho']) ? intval($_POST['amortiguador_posterior_derecho']) : null;
            
            // Validar según el tipo de vehículo
            if ($vehicleType === 'moto') {
                // Para motos: solo validar delantero y trasero (derecho)
                if ($amortiguador_anterior_derecho === null || $amortiguador_anterior_derecho < 0 || $amortiguador_anterior_derecho > 100) {
                    throw new Exception('El porcentaje del amortiguador delantero debe estar entre 0 y 100');
                }
                if ($amortiguador_posterior_derecho === null || $amortiguador_posterior_derecho < 0 || $amortiguador_posterior_derecho > 100) {
                    throw new Exception('El porcentaje del amortiguador trasero debe estar entre 0 y 100');
                }
                // Los amortiguadores izquierdos se guardan como 0 para motos
                $amortiguador_anterior_izquierdo = 0;
                $amortiguador_posterior_izquierdo = 0;
            } else {
                // Para carros: validar los 4 amortiguadores
                $porcentajes = [
                    'anterior_izquierdo' => $amortiguador_anterior_izquierdo,
                    'anterior_derecho' => $amortiguador_anterior_derecho,
                    'posterior_izquierdo' => $amortiguador_posterior_izquierdo,
                    'posterior_derecho' => $amortiguador_posterior_derecho
                ];
                
                foreach ($porcentajes as $nombre => $valor) {
                    if ($valor === null || $valor < 0 || $valor > 100) {
                        throw new Exception('El porcentaje del amortiguador ' . str_replace('_', ' ', $nombre) . ' debe estar entre 0 y 100');
                    }
                }
            }
            
            // Preparar datos para actualizar
            $data = [
                'amortiguador_anterior_izquierdo' => $amortiguador_anterior_izquierdo,
                'amortiguador_anterior_derecho' => $amortiguador_anterior_derecho,
                'amortiguador_posterior_izquierdo' => $amortiguador_posterior_izquierdo,
                'amortiguador_posterior_derecho' => $amortiguador_posterior_derecho,
                'observaciones_amortiguadores' => $_POST['observaciones_amortiguadores'] ?? null
            ];
            
            // Actualizar en BD
            $updated = $this->expertiseModel->updateStep7($expertiseId, $data);
            
            if (!$updated) {
                throw new Exception('Error al guardar los datos de amortiguadores');
            }
            
            $_SESSION['success'] = 'Inspección de amortiguadores guardada correctamente';
            
            // Redirigir al paso 8
            $this->redirect('expertise/step8');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step7');
        }
    }
    
    /**
     * Mostrar el paso 8: Inspección de Batería
     */
    public function step8() {
        try {
            // Obtener expertise_id de la sesión o recuperar último borrador
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                // Intentar recuperar último borrador del usuario
                $lastDraft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
                
                if (!$lastDraft) {
                    $_SESSION['error'] = 'No se encontró un peritaje en progreso. Por favor inicie uno nuevo.';
                    $this->redirect('expertise/create');
                    return;
                }
                
                $expertiseId = $lastDraft['id'];
                $_SESSION['expertise_id'] = $expertiseId;
            }
            
            // Obtener datos del expertise desde BD
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                $_SESSION['error'] = 'No se encontró el peritaje';
                $this->redirect('expertise/create');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = 'No tiene permisos para editar este peritaje';
                $this->redirect('expertise');
            }
            
            $data = [
                'csrf_token' => $this->generateCsrfToken(),
                'expertise' => $expertise
            ];
            
            $this->view('expertise/step8', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step7');
        }
    }
    
    /**
     * Guardar datos del paso 8 (Inspección Batería)
     */
    public function saveStep8() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Obtener expertise_id
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                throw new Exception('No se encontró un peritaje en progreso');
            }
            
            // Obtener datos de batería
            $prueba_bateria = isset($_POST['prueba_bateria']) ? intval($_POST['prueba_bateria']) : null;
            $prueba_arranque = isset($_POST['prueba_arranque']) ? intval($_POST['prueba_arranque']) : null;
            $carga_bateria = isset($_POST['carga_bateria']) ? intval($_POST['carga_bateria']) : null;
            
            // Validar que todos los porcentajes estén en el rango 0-100
            $porcentajes = [
                'prueba_bateria' => $prueba_bateria,
                'prueba_arranque' => $prueba_arranque,
                'carga_bateria' => $carga_bateria
            ];
            
            foreach ($porcentajes as $nombre => $valor) {
                if ($valor === null || $valor < 0 || $valor > 100) {
                    throw new Exception('El porcentaje de ' . str_replace('_', ' ', $nombre) . ' debe estar entre 0 y 100');
                }
            }
            
            // Preparar datos para actualizar
            $data = [
                'prueba_bateria' => $prueba_bateria,
                'prueba_arranque' => $prueba_arranque,
                'carga_bateria' => $carga_bateria,
                'observaciones_bateria' => $_POST['observaciones_bateria'] ?? null
            ];
            
            // Actualizar en BD
            $updated = $this->expertiseModel->updateStep8($expertiseId, $data);
            
            if (!$updated) {
                throw new Exception('Error al guardar los datos de batería');
            }
            
            $_SESSION['success'] = 'Inspección de batería guardada correctamente';
            
            // Redirigir al paso 9
            $this->redirect('expertise/step9');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step8');
        }
    }
    
    /**
     * Mostrar el paso 9: Motor y Sistemas
     */
    public function step9() {
        try {
            // Obtener expertise_id de la sesión o recuperar último borrador
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                // Intentar recuperar último borrador del usuario
                $lastDraft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
                
                if (!$lastDraft) {
                    $_SESSION['error'] = 'No se encontró un peritaje en progreso. Por favor inicie uno nuevo.';
                    $this->redirect('expertise/create');
                    return;
                }
                
                $expertiseId = $lastDraft['id'];
                $_SESSION['expertise_id'] = $expertiseId;
            }
            
            // Obtener datos del expertise desde BD
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                $_SESSION['error'] = 'No se encontró el peritaje';
                $this->redirect('expertise/create');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = 'No tiene permisos para editar este peritaje';
                $this->redirect('expertise');
            }
            
            $data = [
                'csrf_token' => $this->generateCsrfToken(),
                'expertise' => $expertise
            ];
            
            $this->view('expertise/step9', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step8');
        }
    }
    
    /**
     * Guardar datos del paso 9 (Motor y Sistemas)
     */
    public function saveStep9() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Array de campos de motor y sistemas (31 sistemas en total)
            $campos_motor = [
                // Sección Motor (12 items)
                'estado_arranque', 'respuesta_arranque',
                'estado_radiador', 'respuesta_radiador',
                'estado_carter_motor', 'respuesta_carter_motor',
                'estado_carter_caja', 'respuesta_carter_caja',
                'estado_caja_velocidades', 'respuesta_caja_velocidades',
                'estado_soporte_caja', 'respuesta_soporte_caja',
                'estado_soporte_motor', 'respuesta_soporte_motor',
                'estado_mangueras_radiador', 'respuesta_mangueras_radiador',
                'estado_correas', 'respuesta_correas',
                'tension_correas', 'respuesta_tension_correas',
                'estado_filtro_aire', 'respuesta_filtro_aire',
                'estado_externo_bateria', 'respuesta_externo_bateria',
                // Sección Frenos y Suspensión (11 items)
                'estado_pastilla_freno', 'respuesta_pastilla_freno',
                'estado_discos_freno', 'respuesta_discos_freno',
                'estado_punta_eje', 'respuesta_punta_eje',
                'estado_axiales', 'respuesta_axiales',
                'estado_terminales', 'respuesta_terminales',
                'estado_rotulas', 'respuesta_rotulas',
                'estado_tijeras', 'respuesta_tijeras',
                'estado_caja_direccion', 'respuesta_caja_direccion',
                'estado_rodamientos', 'respuesta_rodamientos',
                'estado_cardan', 'respuesta_cardan',
                'estado_crucetas', 'respuesta_crucetas',
                // Sección Interior (8 items)
                'estado_calefaccion', 'respuesta_calefaccion',
                'estado_aire_acondicionado', 'respuesta_aire_acondicionado',
                'estado_cinturones', 'respuesta_cinturones',
                'estado_tapiceria_asientos', 'respuesta_tapiceria_asientos',
                'estado_tapiceria_techo', 'respuesta_tapiceria_techo',
                'estado_millaret', 'respuesta_millaret',
                'estado_alfombra', 'respuesta_alfombra',
                'estado_chapas', 'respuesta_chapas',
            ];
            
            // Construir array de datos
            $datos_motor = [];
            foreach ($campos_motor as $campo) {
                $datos_motor[$campo] = $_POST[$campo] ?? '';
            }
            
            // Agregar observaciones generales
            $datos_motor['observaciones_motor'] = $_POST['observaciones_motor'] ?? '';
            $datos_motor['observaciones_interior'] = $_POST['observaciones_interior'] ?? '';
            
            // Validar que todos los campos de estado tengan valor
            $campos_estado = array_filter($campos_motor, function($campo) {
                return strpos($campo, 'estado_') === 0 || strpos($campo, 'tension_') === 0;
            });
            
            foreach ($campos_estado as $campo) {
                if (empty($datos_motor[$campo])) {
                    throw new Exception('Debe completar el estado de todos los sistemas antes de continuar');
                }
            }
            
            // Obtener expertise_id
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                throw new Exception('No se encontró un peritaje en progreso');
            }
            
            // Preparar datos para actualizar (renombrar variable para claridad)
            $data = $datos_motor;
            
            // Actualizar en BD
            $updated = $this->expertiseModel->updateStep9($expertiseId, $data);
            
            if (!$updated) {
                throw new Exception('Error al guardar los datos de motor y sistemas');
            }
            
            $_SESSION['success'] = 'Inspección de motor y sistemas guardada correctamente';
            
            // Redirigir al paso 10
            $this->redirect('expertise/step10');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step9');
        }
    }
    
    /**
     * Mostrar formulario del Paso 10 - Fugas y Niveles
     */
    public function step10() {
        try {
            // Obtener expertise_id de la sesión o recuperar último borrador
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                // Intentar recuperar último borrador del usuario
                $lastDraft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
                
                if (!$lastDraft) {
                    $_SESSION['error'] = 'No se encontró un peritaje en progreso';
                    $this->redirect('expertise/create');
                }
                
                $expertiseId = $lastDraft['id'];
                $_SESSION['expertise_id'] = $expertiseId;
            }
            
            // Obtener datos del expertise desde BD
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                $_SESSION['error'] = 'No se encontró el peritaje';
                $this->redirect('expertise/create');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = 'No tiene permisos para editar este peritaje';
                $this->redirect('expertise');
            }
            
            $data = [
                'title' => 'Nuevo Peritaje Completo - Paso 10: Fugas y Niveles',
                'csrf_token' => $this->generateCSRFToken(),
                'expertise' => $expertise
            ];
            
            $this->view('expertise/step10', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step9');
        }
    }
    
    /**
     * Guardar datos del Paso 10 - Fugas y Niveles
     */
    public function saveStep10() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Obtener expertise_id
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                throw new Exception('No se encontró un peritaje en progreso');
            }
            
            // Array de campos de fugas y niveles (19 sistemas)
            $campos_fugas = [
                'respuesta_fuga_aceite_motor',
                'respuesta_fuga_aceite_caja_velocidades',
                'respuesta_fuga_aceite_caja_transmision',
                'respuesta_fuga_liquido_frenos',
                'respuesta_fuga_aceite_direccion_hidraulica',
                'respuesta_fuga_liquido_bomba_embrague',
                'respuesta_fuga_tanque_combustible',
                'respuesta_estado_tanque_silenciador',
                'respuesta_estado_tubo_exhosto',
                'respuesta_estado_tanque_catalizador_gases',
                'respuesta_estado_guardapolvo_caja_direccion',
                'respuesta_estado_tuberia_frenos',
                'respuesta_viscosidad_aceite_motor',
                'respuesta_nivel_refrigerante_motor',
                'respuesta_nivel_liquido_frenos',
                'respuesta_nivel_agua_limpiavidrios',
                'respuesta_nivel_aceite_direccion_hidraulica',
                'respuesta_nivel_liquido_embrague',
                'respuesta_nivel_aceite_motor',
            ];
            
            // Construir array de datos
            $data = [];
            foreach ($campos_fugas as $campo) {
                $data[$campo] = $_POST[$campo] ?? '';
            }
            
            // Agregar campos adicionales
            $data['prueba_ruta'] = $_POST['prueba_ruta'] ?? '';
            $data['observaciones_fugas'] = $_POST['observaciones_fugas'] ?? '';
            
            // Actualizar en BD
            $updated = $this->expertiseModel->updateStep10($expertiseId, $data);
            
            if (!$updated) {
                throw new Exception('Error al guardar los datos de fugas y niveles');
            }
            
            $_SESSION['success'] = 'Inspección de fugas y niveles guardada correctamente';
            
            // Redirigir al paso 11
            $this->redirect('expertise/step11');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step10');
        }
    }
    
    /**
     * Mostrar formulario del Paso 11 - Fijación Fotográfica
     */
    public function step11() {
        try {
            // Obtener expertise_id de la sesión o recuperar último borrador
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                // Intentar recuperar último borrador del usuario
                $lastDraft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
                
                if (!$lastDraft) {
                    $_SESSION['error'] = 'No se encontró un peritaje en progreso';
                    $this->redirect('expertise/create');
                }
                
                $expertiseId = $lastDraft['id'];
                $_SESSION['expertise_id'] = $expertiseId;
            }
            
            // Obtener datos del expertise desde BD
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                $_SESSION['error'] = 'No se encontró el peritaje';
                $this->redirect('expertise/create');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                $_SESSION['error'] = 'No tiene permisos para editar este peritaje';
                $this->redirect('expertise');
            }
            
            $data = [
                'title' => 'Nuevo Peritaje Completo - Paso 11: Fijación Fotográfica',
                'csrf_token' => $this->generateCSRFToken(),
                'expertise' => $expertise
            ];
            
            $this->view('expertise/step11', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step10');
        }
    }
    
    /**
     * Guardar datos del Paso 11 - Fijación Fotográfica
     */
    public function saveStep11() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Obtener expertise_id
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                throw new Exception('No se encontró un peritaje en progreso');
            }
            
            // Validar que se hayan subido archivos
            if (!isset($_FILES['fotos']) || empty($_FILES['fotos']['name'][0])) {
                throw new Exception('Debe agregar al menos una fotografía');
            }
            
            $fotos_guardadas = [];
            
            // Crear directorio para fotos en public/assets/expertises/
            $upload_dir = __DIR__ . '/../../public/assets/expertises/';
            
            // Normalizar ruta para Windows
            $upload_dir = str_replace('\\', '/', $upload_dir);
            
            // Si el directorio no existe, crearlo
            if (!file_exists($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    throw new Exception('No se pudo crear el directorio de fotografías: ' . $upload_dir);
                }
            }
            
            // Convertir a ruta absoluta
            $upload_dir = realpath($upload_dir);
            
            if (!$upload_dir) {
                throw new Exception('No se pudo obtener la ruta absoluta del directorio de fotografías');
            }
            
            // Asegurar que termine con /
            $upload_dir = rtrim(str_replace('\\', '/', $upload_dir), '/') . '/';
            
            // Verificar que el directorio sea escribible
            if (!is_writable($upload_dir)) {
                throw new Exception('El directorio de fotografías no tiene permisos de escritura: ' . $upload_dir);
            }
            
            // Procesar cada foto
            $total_fotos = count($_FILES['fotos']['name']);
            
            for ($i = 0; $i < $total_fotos; $i++) {
                // Validar que se haya subido correctamente
                if ($_FILES['fotos']['error'][$i] !== UPLOAD_ERR_OK) {
                    continue;
                }
                
                // Obtener información del archivo
                $file_name = $_FILES['fotos']['name'][$i];
                $file_tmp = $_FILES['fotos']['tmp_name'][$i];
                $file_size = $_FILES['fotos']['size'][$i];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                
                // Validar extensión
                $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($file_ext, $extensiones_permitidas)) {
                    throw new Exception("Formato de archivo no permitido: {$file_name}. Solo se permiten: " . implode(', ', $extensiones_permitidas));
                }
                
                // Validar tamaño (5MB max)
                $max_size = 5 * 1024 * 1024; // 5MB
                if ($file_size > $max_size) {
                    throw new Exception("El archivo {$file_name} excede el tamaño máximo de 5MB");
                }
                
                // Generar nombre único
                $nuevo_nombre = uniqid('expertise_', true) . '.' . $file_ext;
                $ruta_destino = $upload_dir . $nuevo_nombre;
                
                // Mover archivo
                if (move_uploaded_file($file_tmp, $ruta_destino)) {
                    $fotos_guardadas[] = [
                        'nombre_original' => $file_name,
                        'nombre_guardado' => $nuevo_nombre,
                        'ruta' => 'public/assets/expertises/' . $nuevo_nombre,
                        'size' => $file_size,
                        'extension' => $file_ext
                    ];
                } else {
                    throw new Exception("Error al mover el archivo: {$file_name} a {$ruta_destino}");
                }
            }
            
            // Validar que se haya guardado al menos una foto
            if (empty($fotos_guardadas)) {
                throw new Exception('No se pudo guardar ninguna fotografía. Verifique que seleccionó archivos válidos.');
            }
            
            // Preparar datos para actualizar (enviar array completo con metadatos)
            $data = [
                'fotos' => $fotos_guardadas
            ];
            
            // Actualizar en BD
            $updated = $this->expertiseModel->updateStep11($expertiseId, $data);
            
            if (!$updated) {
                throw new Exception('Error al guardar las fotografías en la base de datos. Verifique que el método updateStep11 existe en el modelo.');
            }
            
            $_SESSION['success'] = 'Fotografías guardadas correctamente (' . count($fotos_guardadas) . ' imágenes)';
            
            // Redirigir al paso 12 (resumen)
            $this->redirect('expertise/step12');
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step11');
        }
    }
    
    /**
     * Mostrar resumen final (Paso 12)
     */
    public function step12() {
        try {
            // Verificar si viene un ID por GET (desde el botón de "Ver Resumen y Continuar")
            $requestedId = isset($_GET['id']) ? intval($_GET['id']) : null;
            
            // Obtener expertise_id de la sesión o del parámetro GET
            $expertiseId = $requestedId ?? $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                // Intentar recuperar último borrador
                $lastDraft = $this->expertiseModel->getLastDraft($_SESSION['user_id']);
                
                if ($lastDraft && ($lastDraft['status'] === 'draft' || $lastDraft['status'] === 'in_progress')) {
                    $expertiseId = $lastDraft['id'];
                    $_SESSION['expertise_id'] = $expertiseId;
                } else {
                    throw new Exception('No se encontró un peritaje en progreso. Por favor, inicie un nuevo peritaje.');
                }
            }
            
            // Si viene un ID por GET, validar que el usuario sea el dueño y cargar en sesión
            if ($requestedId) {
                $expertise = $this->expertiseModel->getByIdComplete($requestedId);
                
                if (!$expertise) {
                    throw new Exception('No se pudo cargar el peritaje');
                }
                
                if ($expertise['user_id'] != $_SESSION['user_id']) {
                    throw new Exception('No tiene permisos para ver este peritaje');
                }
                
                // Cargar en sesión para poder continuar editando
                $_SESSION['expertise_id'] = $requestedId;
                $expertiseId = $requestedId;
            }
            
            // Obtener datos completos del expertise desde BD
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                throw new Exception('No se pudo cargar el peritaje');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                throw new Exception('No tiene permisos para ver este peritaje');
            }
            
            // Obtener fotos desde la BD
            $fotos = $this->expertiseModel->getPhotos($expertiseId);
            
            // Obtener inspecciones por sección
            $inspeccionesCarroceria = $this->expertiseModel->getInspectionsBySection($expertiseId, 'carroceria');
            $inspeccionesEstructura = $this->expertiseModel->getInspectionsBySection($expertiseId, 'estructura');
            $inspeccionesChasis = $this->expertiseModel->getInspectionsBySection($expertiseId, 'chasis');
            
            // Cargar todos los datos en sesión para que la vista los pueda leer
            // Paso 1: Información del Servicio
            $_SESSION['expertise_step1'] = [
                'service_date' => $expertise['service_date'],
                'service_number' => $expertise['service_number'],
                'service_for' => $expertise['service_for'],
                'agreement' => $expertise['agreement'],
                'client_name' => ($expertise['cliente_nombre'] ?? '') . ' ' . ($expertise['cliente_apellido'] ?? '')
            ];
            
            // Paso 2: Datos del Vehículo
            $_SESSION['expertise_step2'] = [
                'placa' => $expertise['placa'],
                'marca' => $expertise['marca'],
                'linea' => $expertise['linea'],
                'modelo' => $expertise['modelo'],
                'color' => $expertise['color'],
                'kilometraje' => $expertise['kilometraje'],
                'numero_motor' => $expertise['numero_motor'],
                'numero_chasis' => $expertise['numero_chasis'],
                'numero_serie' => $expertise['numero_serie'],
                'vin' => $expertise['vin']
            ];
            
            // Paso 3, 4, 5: Inspecciones (ya cargadas arriba)
            $_SESSION['expertise_step3'] = [
                'inspecciones' => $inspeccionesCarroceria
            ];
            
            $_SESSION['expertise_step4'] = [
                'inspecciones' => $inspeccionesEstructura
            ];
            
            $_SESSION['expertise_step5'] = [
                'inspecciones' => $inspeccionesChasis
            ];
            
            // Paso 6: Llantas
            $_SESSION['expertise_step6'] = [
                'llanta_anterior_izquierda' => $expertise['llanta_anterior_izquierda'],
                'llanta_anterior_derecha' => $expertise['llanta_anterior_derecha'],
                'llanta_posterior_izquierda' => $expertise['llanta_posterior_izquierda'],
                'llanta_posterior_derecha' => $expertise['llanta_posterior_derecha'],
                'observaciones_llantas' => $expertise['observaciones_llantas']
            ];
            
            // Paso 7: Amortiguadores
            $_SESSION['expertise_step7'] = [
                'amortiguador_anterior_izquierdo' => $expertise['amortiguador_anterior_izquierdo'],
                'amortiguador_anterior_derecho' => $expertise['amortiguador_anterior_derecho'],
                'amortiguador_posterior_izquierdo' => $expertise['amortiguador_posterior_izquierdo'],
                'amortiguador_posterior_derecho' => $expertise['amortiguador_posterior_derecho'],
                'observaciones_amortiguadores' => $expertise['observaciones_amortiguadores']
            ];
            
            // Paso 8: Batería
            $_SESSION['expertise_step8'] = [
                'prueba_bateria' => $expertise['prueba_bateria'],
                'prueba_arranque' => $expertise['prueba_arranque'],
                'carga_bateria' => $expertise['carga_bateria'],
                'observaciones_bateria' => $expertise['observaciones_bateria']
            ];
            
            // Paso 9: Motor y Sistemas (decodificar si es string JSON)
            if (!empty($expertise['motor_sistemas_data'])) {
                if (is_string($expertise['motor_sistemas_data'])) {
                    $_SESSION['expertise_step9'] = json_decode($expertise['motor_sistemas_data'], true) ?? [];
                } else {
                    $_SESSION['expertise_step9'] = $expertise['motor_sistemas_data'];
                }
            } else {
                $_SESSION['expertise_step9'] = [];
            }
            
            // Paso 10: Fugas y Niveles (decodificar si es string JSON)
            if (!empty($expertise['fugas_niveles_data'])) {
                if (is_string($expertise['fugas_niveles_data'])) {
                    $_SESSION['expertise_step10'] = json_decode($expertise['fugas_niveles_data'], true) ?? [];
                } else {
                    $_SESSION['expertise_step10'] = $expertise['fugas_niveles_data'];
                }
            } else {
                $_SESSION['expertise_step10'] = [];
            }
            
            // Paso 11: Fotos
            $_SESSION['expertise_step11'] = [
                'total_fotos' => count($fotos),
                'fotos' => $fotos
            ];
            
            $data = [
                'title' => 'Nuevo Peritaje Completo - Paso 12: Resumen Final',
                'csrf_token' => $this->generateCSRFToken(),
                'expertise' => $expertise,
                'fotos' => $fotos,
                'total_inspeccionesCarroceria' => count($inspeccionesCarroceria),
                'total_inspeccionesEstructura' => count($inspeccionesEstructura),
                'total_inspeccionesChasis' => count($inspeccionesChasis),
                'expertise_id' => $expertiseId,
                'view_mode' => false // Modo resumen para continuar editando
            ];
            
            $this->view('expertise/step12', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/step11');
        }
    }
    
    /**
     * Ver detalles de un peritaje (reutiliza vista del paso 12)
     */
    public function show($id) {
        try {
            // Validar ID
            if (empty($id) || !is_numeric($id)) {
                throw new Exception('ID de peritaje inválido');
            }
            
            // Obtener el peritaje con todas sus relaciones
            $expertise = $this->expertiseModel->getByIdWithRelations($id);
            
            if (!$expertise) {
                throw new Exception('Peritaje no encontrado');
            }
            
            // Cargar los datos en sesión temporalmente para que la vista del paso 12 los pueda leer
            // Guardamos las sesiones existentes para no perder el progreso del usuario
            $backup_sessions = [];
            for ($i = 1; $i <= 11; $i++) {
                if (isset($_SESSION['expertise_step' . $i])) {
                    $backup_sessions['expertise_step' . $i] = $_SESSION['expertise_step' . $i];
                }
            }
            
            // Marcar modo visualización
            $_SESSION['expertise_view_mode'] = true;
            $_SESSION['expertise_view_id'] = $id;
            
            // Paso 1: Información del Servicio y Cliente
            $_SESSION['expertise_step1'] = [
                'service_date' => $expertise['service_date'],
                'service_number' => $expertise['service_number'],
                'service_for' => $expertise['service_for'],
                'client_id' => $expertise['client_id'],
                'client_name' => $expertise['cliente_nombre'] . ' ' . $expertise['cliente_apellido']
            ];
            
            // Paso 2: Datos del Vehículo
            $_SESSION['expertise_step2'] = [
                'tipo_vehiculo' => $expertise['vehicle_type_id'],
                'tipo_vehiculo_nombre' => $expertise['tipo_vehiculo_nombre'],
                'placa' => $expertise['placa'],
                'marca' => $expertise['marca'],
                'linea' => $expertise['linea'],
                'modelo' => $expertise['modelo'],
                'color' => $expertise['color'],
                'kilometraje' => $expertise['kilometraje'] ?? '',
                'vin' => $expertise['vin'] ?? '',
                'numero_motor' => $expertise['numero_motor'] ?? '',
                'numero_chasis' => $expertise['numero_chasis'] ?? '',
                'tipo_combustible' => $expertise['tipo_combustible'] ?? '',
                'carroceria' => $expertise['carroceria'] ?? '',
                'clase' => $expertise['clase'] ?? '',
                'cilindraje' => $expertise['cilindraje'] ?? '',
                'capacidad_pasajeros' => $expertise['capacidad_pasajeros'] ?? ''
            ];
            
            // Pasos 3, 4, 5: Inspecciones (cargar desde BD)
            $inspeccionesCarroceria = $this->expertiseModel->getInspectionsBySection($id, 'carroceria');
            $inspeccionesEstructura = $this->expertiseModel->getInspectionsBySection($id, 'estructura');
            $inspeccionesChasis = $this->expertiseModel->getInspectionsBySection($id, 'chasis');
            
            $_SESSION['expertise_step3'] = [
                'inspecciones' => $inspeccionesCarroceria,
                'observaciones_carroceria' => $expertise['observaciones_carroceria'] ?? ''
            ];
            
            $_SESSION['expertise_step4'] = [
                'inspecciones' => $inspeccionesEstructura,
                'observaciones_estructura' => $expertise['observaciones_estructura'] ?? ''
            ];
            
            $_SESSION['expertise_step5'] = [
                'inspecciones' => $inspeccionesChasis,
                'observaciones_chasis' => $expertise['observaciones_chasis'] ?? ''
            ];
            
            // Paso 6: Llantas
            $_SESSION['expertise_step6'] = [
                'llanta_anterior_izquierda' => $expertise['llanta_anterior_izquierda'] ?? '',
                'llanta_anterior_derecha' => $expertise['llanta_anterior_derecha'] ?? '',
                'llanta_posterior_izquierda' => $expertise['llanta_posterior_izquierda'] ?? '',
                'llanta_posterior_derecha' => $expertise['llanta_posterior_derecha'] ?? '',
                'observaciones_llantas' => $expertise['observaciones_llantas'] ?? ''
            ];
            
            // Paso 7: Amortiguadores
            $_SESSION['expertise_step7'] = [
                'amortiguador_anterior_izquierdo' => $expertise['amortiguador_anterior_izquierdo'] ?? '',
                'amortiguador_anterior_derecho' => $expertise['amortiguador_anterior_derecho'] ?? '',
                'amortiguador_posterior_izquierdo' => $expertise['amortiguador_posterior_izquierdo'] ?? '',
                'amortiguador_posterior_derecho' => $expertise['amortiguador_posterior_derecho'] ?? '',
                'observaciones_amortiguadores' => $expertise['observaciones_amortiguadores'] ?? ''
            ];
            
            // Paso 8: Batería
            $_SESSION['expertise_step8'] = [
                'prueba_bateria' => $expertise['prueba_bateria'] ?? '',
                'prueba_arranque' => $expertise['prueba_arranque'] ?? '',
                'carga_bateria' => $expertise['carga_bateria'] ?? '',
                'observaciones_bateria' => $expertise['observaciones_bateria'] ?? ''
            ];
            
            // Paso 9: Motor y Sistemas (31 campos)
            $_SESSION['expertise_step9'] = [
                'estado_aceite_motor' => $expertise['estado_aceite_motor'] ?? '',
                'estado_refrigerante' => $expertise['estado_refrigerante'] ?? '',
                'estado_liquido_frenos' => $expertise['estado_liquido_frenos'] ?? '',
                'estado_liquido_direccion' => $expertise['estado_liquido_direccion'] ?? '',
                'estado_transmision' => $expertise['estado_transmision'] ?? '',
                'estado_clutch' => $expertise['estado_clutch'] ?? '',
                'estado_filtro_aire' => $expertise['estado_filtro_aire'] ?? '',
                'estado_filtro_combustible' => $expertise['estado_filtro_combustible'] ?? '',
                'estado_bujias' => $expertise['estado_bujias'] ?? '',
                'estado_cables' => $expertise['estado_cables'] ?? '',
                'estado_banda_accesorios' => $expertise['estado_banda_accesorios'] ?? '',
                'estado_banda_tiempo' => $expertise['estado_banda_tiempo'] ?? '',
                'estado_mangueras' => $expertise['estado_mangueras'] ?? '',
                'estado_luces_delanteras' => $expertise['estado_luces_delanteras'] ?? '',
                'estado_luces_traseras' => $expertise['estado_luces_traseras'] ?? '',
                'estado_sistema_escape' => $expertise['estado_sistema_escape'] ?? '' ?? '',
                'estado_embrague' => $expertise['estado_embrague'] ?? '',
                'estado_freno_mano' => $expertise['estado_freno_mano'] ?? '',
                'estado_pedal_freno' => $expertise['estado_pedal_freno'] ?? '',
                'estado_pedal_clutch' => $expertise['estado_pedal_clutch'] ?? '',
                'estado_limpiabrisas' => $expertise['estado_limpiabrisas'] ?? '',
                'estado_bocina' => $expertise['estado_bocina'] ?? '',
                'estado_espejos' => $expertise['estado_espejos'] ?? '',
                'estado_cinturones' => $expertise['estado_cinturones'] ?? '',
                'estado_airbags' => $expertise['estado_airbags'] ?? '',
                'estado_tapiceria' => $expertise['estado_tapiceria'] ?? '',
                'estado_panel' => $expertise['estado_panel'] ?? '',
                'estado_aire_acondicionado' => $expertise['estado_aire_acondicionado'] ?? '',
                'tension_correa_alternador' => $expertise['tension_correa_alternador'] ?? '',
                'tension_correa_direccion' => $expertise['tension_correa_direccion'] ?? '',
                'tension_correa_aire' => $expertise['tension_correa_aire'] ?? '',
                'observaciones_motor' => $expertise['observaciones_motor'] ?? '',
                'observaciones_interior' => $expertise['observaciones_interior'] ?? ''
            ];
            
            // Paso 10: Fugas y Niveles (19 campos)
            $_SESSION['expertise_step10'] = [
                'fuga_aceite_motor' => $expertise['fuga_aceite_motor'] ?? '',
                'fuga_refrigerante' => $expertise['fuga_refrigerante'] ?? '',
                'fuga_liquido_frenos' => $expertise['fuga_liquido_frenos'] ?? '',
                'fuga_liquido_direccion' => $expertise['fuga_liquido_direccion'] ?? '',
                'fuga_transmision' => $expertise['fuga_transmision'] ?? '',
                'fuga_combustible' => $expertise['fuga_combustible'] ?? '',
                'nivel_aceite_motor' => $expertise['nivel_aceite_motor'] ?? '',
                'nivel_refrigerante' => $expertise['nivel_refrigerante'] ?? '',
                'nivel_liquido_frenos' => $expertise['nivel_liquido_frenos'] ?? '',
                'nivel_liquido_direccion' => $expertise['nivel_liquido_direccion'] ?? '',
                'nivel_liquido_transmision' => $expertise['nivel_liquido_transmision'] ?? '',
                'nivel_liquido_limpiabrisas' => $expertise['nivel_liquido_limpiabrisas'] ?? '',
                'presion_neumatico_ai' => $expertise['presion_neumatico_ai'] ?? '',
                'presion_neumatico_ad' => $expertise['presion_neumatico_ad'] ?? '',
                'presion_neumatico_pi' => $expertise['presion_neumatico_pi'] ?? '',
                'presion_neumatico_pd' => $expertise['presion_neumatico_pd'] ?? '',
                'presion_neumatico_repuesto' => $expertise['presion_neumatico_repuesto'] ?? '',
                'estado_herramientas' => $expertise['estado_herramientas'] ?? '',
                'estado_gato' => $expertise['estado_gato'] ?? '',
                'prueba_ruta' => $expertise['prueba_ruta'] ?? '',
                'observaciones_fugas' => $expertise['observaciones_fugas'] ?? ''
            ];
            
            // Paso 11: Fotos (cargar desde BD)
            $fotos = $this->expertiseModel->getPhotos($id);
            
            $_SESSION['expertise_step11'] = [
                'total_fotos' => count($fotos),
                'fotos' => $fotos,
                'observaciones_fotograficas' => $expertise['observaciones_fotograficas'] ?? ''
            ];
            
            $data = [
                'title' => 'Detalles del Peritaje #' . $expertise['service_number'],
                'csrf_token' => $this->generateCSRFToken(),
                'expertise_id' => $id,
                'expertise' => $expertise,
                'view_mode' => true, // Flag para indicar que estamos en modo vista
                'backup_sessions' => $backup_sessions, // Para restaurar después
                'total_inspeccionesCarroceria' => count($inspeccionesCarroceria),
                'total_inspeccionesEstructura' => count($inspeccionesEstructura),
                'total_inspeccionesChasis' => count($inspeccionesChasis)
            ];
            
            $this->view('expertise/step12', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise');
        }
    }
    
    /**
     * Iniciar edición de un peritaje completado
     * @param int $id ID del expertise a editar
     * @param int $step Número de paso a editar (1-11)
     */
    public function edit($id, $step = 2) {
        try {
            // Validar parámetros
            if (empty($id) || !is_numeric($id)) {
                throw new Exception('ID de peritaje inválido');
            }
            
            if (empty($step) || !is_numeric($step) || $step < 1 || $step > 11) {
                throw new Exception('Paso inválido');
            }
            
            // Obtener el expertise
            $expertise = $this->expertiseModel->getByIdComplete($id);
            
            if (!$expertise) {
                throw new Exception('Peritaje no encontrado');
            }
            
            // Verificar que el usuario sea el dueño
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                throw new Exception('No tiene permisos para editar este peritaje');
            }
            
            // Cargar el expertise_id en la sesión para poder editarlo
            $_SESSION['expertise_id'] = $id;
            
            // Mensaje informativo
            $_SESSION['info'] = 'Editando peritaje ' . $expertise['codigo'] . '. Los cambios se guardarán automáticamente.';
            
            // Redirigir al paso correspondiente (las vistas ya cargan datos desde BD)
            $this->redirect('expertise/step' . $step);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise/show/' . $id);
        }
    }
    
    /**
     * Guardar peritaje completo en la base de datos (Finalizar)
     */
    public function saveFinal() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Obtener expertise_id de la sesión
            $expertiseId = $_SESSION['expertise_id'] ?? null;
            
            if (!$expertiseId) {
                throw new Exception('No se encontró un peritaje en progreso');
            }
            
            // Verificar que el expertise existe y el usuario es el dueño
            $expertise = $this->expertiseModel->getByIdComplete($expertiseId);
            
            if (!$expertise) {
                throw new Exception('El peritaje no existe');
            }
            
            if ($expertise['user_id'] != $_SESSION['user_id']) {
                throw new Exception('No tiene permisos para completar este peritaje');
            }
            
            // Validar que el peritaje tenga todos los datos necesarios
            if ($expertise['current_step'] < 11) {
                throw new Exception('Debe completar todos los pasos antes de finalizar el peritaje');
            }
            
            // Llamar al modelo para completar el peritaje (cambiar status a 'completed')
            $completed = $this->expertiseModel->completeExpertise($expertiseId);
            
            if (!$completed) {
                throw new Exception('Error al completar el peritaje');
            }
            
            // Limpiar la sesión del expertise_id
            unset($_SESSION['expertise_id']);
            
            // Limpiar cualquier dato residual de sesiones antiguas
            for ($i = 1; $i <= 11; $i++) {
                unset($_SESSION['expertise_step' . $i]);
            }
            
            // Mensaje de éxito
            $_SESSION['success'] = '¡Peritaje completado exitosamente! Código: ' . $expertise['codigo'];
            
            // Redirigir al índice de peritajes
            $this->redirect('expertise');
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al guardar el peritaje: ' . $e->getMessage();
            $this->redirect('expertise/step12');
        }
    }
    
    /**
     * Generar PDF de un peritaje
     * @param int $id ID del expertise
     */
    public function generatePDF($id) {
        try {
            // Obtener datos completos del peritaje
            $expertise = $this->expertiseModel->getByIdWithRelations($id);
            
            if (!$expertise) {
                throw new Exception('Peritaje no encontrado');
            }
            
            // Verificar que el usuario tenga acceso
            if ($expertise['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] != 'admin') {
                throw new Exception('No tienes permiso para ver este peritaje');
            }
            
            // Decodificar JSON de motor_sistemas_data
            $motorSistemasData = [];
            if (!empty($expertise['motor_sistemas_data'])) {
                $motorSistemasData = json_decode($expertise['motor_sistemas_data'], true) ?? [];
            }
            
            // Decodificar JSON de fugas_niveles_data
            $fugasNivelesData = [];
            if (!empty($expertise['fugas_niveles_data'])) {
                $fugasNivelesData = json_decode($expertise['fugas_niveles_data'], true) ?? [];
            }
            
            // Obtener fotos de la expertise
            $photos = $this->expertiseModel->getPhotos($id);
            
            // Preparar datos para la vista
            $data = [
                'BASE_URL' => APP_URL,
                'photos' => $photos,
                'peritaje' => array_merge([
                    'fecha' => $expertise['service_date'] ?? date('Y-m-d'),
                    'no_servicio' => $expertise['service_number'] ?? 'N/A',
                    'servicio_para' => $expertise['service_for'] ?? 'N/A',
                    'convenio' => $expertise['agreement'] ?? 'N/A',
                    'clase' => $expertise['clase_vehiculo'] ?? 'N/A',
                    'marca' => $expertise['marca'] ?? 'N/A',
                    'linea' => $expertise['linea'] ?? 'N/A',
                    'cilindraje' => $expertise['cilindrada'] ?? 'N/A',
                    'kilometraje' => $expertise['kilometraje'] ?? 'N/A',
                    'servicio' => $expertise['tipo_combustible'] ?? 'N/A',
                    'modelo' => $expertise['modelo'] ?? 'N/A',
                    'color' => $expertise['color'] ?? 'N/A',
                    'no_chasis' => $expertise['numero_chasis'] ?? 'N/A',
                    'no_motor' => $expertise['numero_motor'] ?? 'N/A',
                    'no_serie' => $expertise['numero_serie'] ?? 'N/A',
                    'tipo_carroceria' => $expertise['tipo_carroceria'] ?? 'N/A',
                    'organismo_transito' => $expertise['organismo_transito'] ?? 'N/A',
                    'codigo_fasecolda' => $expertise['codigo_fasecolda'] ?? 'N/A',
                    'valor_fasecolda' => $expertise['valor_fasecolda'] ?? 'N/A',
                    'valor_sugerido' => $expertise['valor_sugerido'] ?? 'N/A',
                    'valor_accesorios' => $expertise['valor_accesorios'] ?? 'N/A',
                    'placa' => $expertise['placa'] ?? 'N/A',
                    'nombre_apellidos' => trim(($expertise['cliente_nombre'] ?? '') . ' ' . ($expertise['cliente_apellido'] ?? '')),
                    'identificacion' => $expertise['cliente_identificacion'] ?? 'N/A',
                    'telefono' => $expertise['cliente_telefono'] ?? 'N/A',
                    'direccion' => $expertise['cliente_direccion'] ?? 'N/A',
                    'email' => $expertise['cliente_email'] ?? 'N/A',
                    'tipo_vehiculo' => $expertise['tipo_vehiculo_nombre'] ?? 'VEHÍCULO',
                    'type' => $expertise['tipo_vehiculo_type'] ?? '',
                    'tipo_vehiculo_type' => $expertise['tipo_vehiculo_type'] ?? 'carro',
                    'observaciones_inspeccion' => $expertise['observaciones_inspeccion'] ?? 'Sin observaciones',
                    'observaciones_estructura' => $expertise['observaciones_estructura'] ?? 'Sin observaciones',
                    'observaciones_llantas' => $expertise['observaciones_llantas'] ?? 'Sin observaciones',
                    'observaciones_amortiguadores' => $expertise['observaciones_amortiguadores'] ?? 'Sin observaciones',
                    'llanta_anterior_izquierda' => $expertise['llanta_anterior_izquierda'] ?? 0,
                    'llanta_anterior_derecha' => $expertise['llanta_anterior_derecha'] ?? 0,
                    'llanta_posterior_izquierda' => $expertise['llanta_posterior_izquierda'] ?? 0,
                    'llanta_posterior_derecha' => $expertise['llanta_posterior_derecha'] ?? 0,
                    'amortiguador_anterior_izquierdo' => $expertise['amortiguador_anterior_izquierdo'] ?? 0,
                    'amortiguador_anterior_derecho' => $expertise['amortiguador_anterior_derecho'] ?? 0,
                    'amortiguador_posterior_izquierdo' => $expertise['amortiguador_posterior_izquierdo'] ?? 0,
                    'amortiguador_posterior_derecho' => $expertise['amortiguador_posterior_derecho'] ?? 0,
                    'prueba_bateria' => $expertise['prueba_bateria'] ?? 0,
                    'prueba_arranque' => $expertise['prueba_arranque'] ?? 0,
                    'carga_bateria' => $expertise['carga_bateria'] ?? 0,
                    'observaciones_bateria' => $expertise['observaciones_bateria'] ?? 'Sin observaciones',
                    'prueba_escaner' => $expertise['prueba_escaner'] ?? 'Sin datos',
                ], $motorSistemasData, $fugasNivelesData), // Merge JSON data into peritaje array
                'carroceria' => $this->getInspectionDataWithImage($id, $expertise['tipo_vehiculo'], 'carroceria'),
                'estructura' => $this->getInspectionDataWithImage($id, $expertise['tipo_vehiculo'], 'estructura'),
                'chasis' => $this->getInspectionDataWithImage($id, $expertise['tipo_vehiculo'], 'chasis'),
                'campos_tren_motriz' => [
                    'estado_punta_eje' => 'Punta eje',
                    'estado_discos_freno' => 'Discos freno',
                    'estado_pastilla_freno' => 'Pastilla freno',
                    'estado_axiales' => 'Axiales',
                    'estado_terminales' => 'Terminales',
                    'estado_rotulas' => 'Rótulas',
                    'estado_chapas' => 'Chapas',
                    'estado_caja_direccion' => 'Caja dirección',
                    'estado_rodamientos' => 'Rodamientos',
                    'estado_cardan' => 'Cardán',
                    'estado_crucetas' => 'Crucetas',
                ],
                'campos_liquidos' => [
                    'viscosidad_aceite_motor' => 'Viscosidad aceite motor',
                    'nivel_refrigerante_motor' => 'Nivel refrigerante motor',
                    'nivel_liquido_frenos' => 'Nivel líquido de frenos',
                    'nivel_agua_limpiavidrios' => 'Nivel agua limpiavidrios',
                    'nivel_aceite_direccion_hidraulica' => 'Nivel aceite dirección hidráulica',
                    'nivel_liquido_embrague' => 'Nivel líquido embrague',
                    'nivel_aceite_motor' => 'Nivel aceite motor',
                ],
                'campos_motor' => [
                    'estado_arranque' => 'Arranque',
                    'estado_radiador' => 'Radiador',
                    'estado_carter_motor' => 'Carter motor',
                    'estado_carter_caja' => 'Carter caja',
                    'estado_caja_velocidades' => 'Caja de velocidades',
                    'estado_soporte_caja' => 'Soporte caja',
                    'estado_soporte_motor' => 'Estado soporte motor',
                    'estado_mangueras_radiador' => 'Estado mangueras radiador',
                    'estado_correas' => 'Estado correas',
                    'tension_correas' => 'Tensión correas',
                    'estado_filtro_aire' => 'Estado filtro de aire',
                    'estado_externo_bateria' => 'Estado externo baterías',
                ],
                'campos_interior' => [
                    'estado_calefaccion' => 'Calefacción',
                    'estado_aire_acondicionado' => 'Aire acondicionado',
                    'estado_cinturones' => 'Cinturones',
                    'estado_tapiceria_asientos' => 'Tapicería asientos',
                    'estado_tapiceria_techo' => 'Tapicería Techo',
                    'estado_millaret' => 'Millaret',
                    'estado_alfombra' => 'Alfombra',
                    'estado_chapas' => 'Chapas',
                ],
                'campos_fugas' => [
                    'fuga_aceite_motor' => 'Fuga aceite motor',
                    'fuga_aceite_caja_velocidades' => 'Fuga aceite caja de velocidades',
                    'fuga_aceite_caja_transmision' => 'Fuga aceite caja de transmisión',
                    'fuga_liquido_frenos' => 'Fuga líquido de frenos',
                    'fuga_aceite_direccion_hidraulica' => 'Fuga aceite dirección hidráulica',
                    'fuga_liquido_bomba_embrague' => 'Fuga líquido bomba embrague',
                    'fuga_tanque_combustible' => 'Fuga tanque de combustible',
                ],
                'campos_estado_componentes' => [
                    'estado_tanque_silenciador' => 'Estado tanque silenciador',
                    'estado_tubo_exhosto' => 'Estado tubo exhosto',
                    'estado_tanque_catalizador_gases' => 'Estado tanque catalizador de gases',
                    'estado_guardapolvo_caja_direccion' => 'Estado guardapolvo caja dirección',
                    'estado_tuberia_frenos' => 'Estado tubería frenos',
                ],
            ];
            
            // Cargar vista HTML (el usuario puede imprimir a PDF desde el navegador)
            $this->view('pdf_expertise', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al generar PDF: ' . $e->getMessage();
            $this->redirect('expertise');
        }
    }
    
    /**
     * Obtener datos de inspección con imagen de la sección
     * @param int $expertiseId ID del expertise
     * @param int $vehicleTypeId ID del tipo de vehículo
     * @param string $section Nombre de la sección (carroceria, estructura, chasis)
     * @return array Array con 'image' y 'pieces'
     */
    private function getInspectionDataWithImage($expertiseId, $vehicleTypeId, $section) {
        try {
            // Obtener la imagen de la sección del tipo de vehículo
            $conn = $this->db->getConnection();
            
            // Consulta para obtener la imagen de la sección
            $sqlImage = "SELECT image_path FROM vehicle_sections 
                        WHERE vehicle_type_id = ? AND section_name = ? 
                        LIMIT 1";
            $stmtImage = $conn->prepare($sqlImage);
            $stmtImage->execute([$vehicleTypeId, $section]);
            $sectionData = $stmtImage->fetch(PDO::FETCH_ASSOC);
            
            // Construir la ruta completa de la imagen
            // La imagen está en /public/assets/uploads/vehicle_sections/
            $imagePath = '';
            if (!empty($sectionData['image_path'])) {
                $imagePath = '/public/assets/uploads/vehicle_sections/' . $sectionData['image_path'];
            }
            
            // Obtener las piezas inspeccionadas con sus conceptos Y POSICIONES
            // NOTA: La tabla usa pieza_id y concepto_id (en español), no piece_id y concept_id
            // NOTA: La columna en inspection_concepts es 'name', no 'concept_name'
            $sqlPieces = "SELECT 
                            vp.piece_number,
                            vp.piece_name,
                            vp.position_x,
                            vp.position_y,
                            ic.name as concept_name
                        FROM expertise_inspections ei
                        INNER JOIN vehicle_pieces vp ON ei.pieza_id = vp.id
                        INNER JOIN inspection_concepts ic ON ei.concepto_id = ic.id
                        INNER JOIN vehicle_sections vs ON vp.section_id = vs.id
                        WHERE ei.expertise_id = ? 
                        AND ei.section = ?
                        ORDER BY vp.piece_number ASC";
            
            $stmtPieces = $conn->prepare($sqlPieces);
            $stmtPieces->execute([$expertiseId, $section]);
            $pieces = $stmtPieces->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'image' => $imagePath,
                'pieces' => $pieces
            ];
            
        } catch (Exception $e) {
            return [
                'image' => '',
                'pieces' => []
            ];
        }
    }
    
}
