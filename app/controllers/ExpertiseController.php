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
            
            // Obtener peritajes con paginación
            $expertises = $this->expertiseModel->getAllWithRelations($page, $limit, $search, $month);
            
            // Obtener total para la paginación
            $totalRecords = $this->expertiseModel->count($search, $month);
            $totalPages = ceil($totalRecords / $limit);
            
            // Generar HTML de paginación (siempre se mostrará la info del total)
            $pagination = renderPagination($page, $totalPages, $totalRecords, '', $limit);
            
            $data = [
                'title' => 'Peritajes Completos',
                'expertises' => $expertises,
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
            $data = [
                'tipo_vehiculo' => $_POST['tipo_vehiculo'],
                'placa' => $_POST['placa'],
                'marca' => $_POST['marca'] ?? null,
                'linea' => $_POST['linea'] ?? null,
                'modelo' => $_POST['modelo'] ?? null,
                'color' => $_POST['color'] ?? null,
                'clase_vehiculo' => $_POST['clase'] ?? null,
                'tipo_vehiculo_text' => $_POST['tipo_vehiculo_text'] ?? null,
                'tipo_carroceria' => $_POST['tipo_carroceria'] ?? null,
                'tipo_combustible' => $_POST['tipo_combustible'] ?? null,
                'numero_motor' => $_POST['no_motor'] ?? null,
                'numero_chasis' => $_POST['no_chasis'] ?? null,
                'numero_serie' => $_POST['no_serie'] ?? null,
                'vin' => $_POST['vin'] ?? null,
                'kilometraje' => $_POST['kilometraje'] ?? null,
                'cilindrada' => $_POST['cilindraje'] ?? null,
                'capacidad_carga' => $_POST['capacidad_carga'] ?? null,
                'numero_ejes' => $_POST['numero_ejes'] ?? null,
                'numero_pasajeros' => $_POST['numero_pasajeros'] ?? null,
                'fecha_matricula' => $_POST['fecha_matricula'] ?? null
            ];
            
            // Actualizar en BD
            $updated = $this->expertiseModel->updateStep2($expertiseId, $data);
            
            if (!$updated) {
                throw new Exception('Error al actualizar los datos del vehículo');
            }
            
            $_SESSION['success'] = 'Datos del vehículo guardados correctamente';
            
            // Redirigir al paso 3 (Inspección Carrocería)
            $this->redirect('expertise/step3');
            
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
            
            // Redirigir al paso 4
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
            
            // Obtener datos de llantas
            $llanta_anterior_izquierda = isset($_POST['llanta_anterior_izquierda']) ? intval($_POST['llanta_anterior_izquierda']) : null;
            $llanta_anterior_derecha = isset($_POST['llanta_anterior_derecha']) ? intval($_POST['llanta_anterior_derecha']) : null;
            $llanta_posterior_izquierda = isset($_POST['llanta_posterior_izquierda']) ? intval($_POST['llanta_posterior_izquierda']) : null;
            $llanta_posterior_derecha = isset($_POST['llanta_posterior_derecha']) ? intval($_POST['llanta_posterior_derecha']) : null;
            
            // Validar que todos los porcentajes estén en el rango 0-100
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
        // Verificar que existan los pasos anteriores
        if (!isset($_SESSION['expertise_step1']) || 
            !isset($_SESSION['expertise_step2']) || 
            !isset($_SESSION['expertise_step3']) ||
            !isset($_SESSION['expertise_step4']) ||
            !isset($_SESSION['expertise_step5']) ||
            !isset($_SESSION['expertise_step6'])) {
            $_SESSION['error'] = 'Debe completar los pasos anteriores primero';
            $this->redirect('expertise/create');
            return;
        }
        
        // Generar token CSRF
        $csrf_token = $this->generateCsrfToken();
        
        // Cargar la vista del paso 7
        $this->view('expertise/step7', [
            'csrf_token' => $csrf_token
        ]);
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
            
            // Verificar que existan datos de los pasos anteriores
            if (!isset($_SESSION['expertise_step1']) || 
                !isset($_SESSION['expertise_step2']) || 
                !isset($_SESSION['expertise_step3']) ||
                !isset($_SESSION['expertise_step4']) ||
                !isset($_SESSION['expertise_step5']) ||
                !isset($_SESSION['expertise_step6'])) {
                throw new Exception('Debe completar los pasos anteriores primero');
            }
            
            // Obtener datos de amortiguadores
            $amortiguador_anterior_izquierdo = isset($_POST['amortiguador_anterior_izquierdo']) ? intval($_POST['amortiguador_anterior_izquierdo']) : null;
            $amortiguador_anterior_derecho = isset($_POST['amortiguador_anterior_derecho']) ? intval($_POST['amortiguador_anterior_derecho']) : null;
            $amortiguador_posterior_izquierdo = isset($_POST['amortiguador_posterior_izquierdo']) ? intval($_POST['amortiguador_posterior_izquierdo']) : null;
            $amortiguador_posterior_derecho = isset($_POST['amortiguador_posterior_derecho']) ? intval($_POST['amortiguador_posterior_derecho']) : null;
            
            // Validar que todos los porcentajes estén en el rango 0-100
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
            
            // Guardar en sesión los datos del paso 7
            $_SESSION['expertise_step7'] = [
                'amortiguador_anterior_izquierdo' => $amortiguador_anterior_izquierdo,
                'amortiguador_anterior_derecho' => $amortiguador_anterior_derecho,
                'amortiguador_posterior_izquierdo' => $amortiguador_posterior_izquierdo,
                'amortiguador_posterior_derecho' => $amortiguador_posterior_derecho,
                'observaciones_amortiguadores' => $_POST['observaciones_amortiguadores'] ?? ''
            ];
            
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
        // Verificar que existan los pasos anteriores
        if (!isset($_SESSION['expertise_step1']) || 
            !isset($_SESSION['expertise_step2']) || 
            !isset($_SESSION['expertise_step3']) ||
            !isset($_SESSION['expertise_step4']) ||
            !isset($_SESSION['expertise_step5']) ||
            !isset($_SESSION['expertise_step6']) ||
            !isset($_SESSION['expertise_step7'])) {
            $_SESSION['error'] = 'Debe completar los pasos anteriores primero';
            $this->redirect('expertise/create');
            return;
        }
        
        // Generar token CSRF
        $csrf_token = $this->generateCsrfToken();
        
        // Cargar la vista del paso 8
        $this->view('expertise/step8', [
            'csrf_token' => $csrf_token
        ]);
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
            
            // Verificar que existan datos de los pasos anteriores
            if (!isset($_SESSION['expertise_step1']) || 
                !isset($_SESSION['expertise_step2']) || 
                !isset($_SESSION['expertise_step3']) ||
                !isset($_SESSION['expertise_step4']) ||
                !isset($_SESSION['expertise_step5']) ||
                !isset($_SESSION['expertise_step6']) ||
                !isset($_SESSION['expertise_step7'])) {
                throw new Exception('Debe completar los pasos anteriores primero');
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
            
            // Guardar en sesión los datos del paso 8
            $_SESSION['expertise_step8'] = [
                'prueba_bateria' => $prueba_bateria,
                'prueba_arranque' => $prueba_arranque,
                'carga_bateria' => $carga_bateria,
                'observaciones_bateria' => $_POST['observaciones_bateria'] ?? ''
            ];
            
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
        // Verificar que existan los pasos anteriores
        if (!isset($_SESSION['expertise_step1']) || 
            !isset($_SESSION['expertise_step2']) || 
            !isset($_SESSION['expertise_step3']) ||
            !isset($_SESSION['expertise_step4']) ||
            !isset($_SESSION['expertise_step5']) ||
            !isset($_SESSION['expertise_step6']) ||
            !isset($_SESSION['expertise_step7']) ||
            !isset($_SESSION['expertise_step8'])) {
            $_SESSION['error'] = 'Debe completar los pasos anteriores primero';
            $this->redirect('expertise/create');
            return;
        }
        
        // Generar token CSRF
        $csrf_token = $this->generateCsrfToken();
        
        // Cargar la vista del paso 9
        $this->view('expertise/step9', [
            'csrf_token' => $csrf_token
        ]);
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
            
            // Verificar que existan datos de los pasos anteriores
            if (!isset($_SESSION['expertise_step1']) || 
                !isset($_SESSION['expertise_step2']) || 
                !isset($_SESSION['expertise_step3']) ||
                !isset($_SESSION['expertise_step4']) ||
                !isset($_SESSION['expertise_step5']) ||
                !isset($_SESSION['expertise_step6']) ||
                !isset($_SESSION['expertise_step7']) ||
                !isset($_SESSION['expertise_step8'])) {
                throw new Exception('Debe completar los pasos anteriores primero');
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
            
            // Guardar en sesión los datos del paso 9
            $_SESSION['expertise_step9'] = $datos_motor;
            
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
            // Verificar que existan datos de los pasos anteriores
            if (!isset($_SESSION['expertise_step1']) || 
                !isset($_SESSION['expertise_step2']) || 
                !isset($_SESSION['expertise_step3']) ||
                !isset($_SESSION['expertise_step4']) ||
                !isset($_SESSION['expertise_step5']) ||
                !isset($_SESSION['expertise_step6']) ||
                !isset($_SESSION['expertise_step7']) ||
                !isset($_SESSION['expertise_step8']) ||
                !isset($_SESSION['expertise_step9'])) {
                throw new Exception('Debe completar los pasos anteriores primero');
            }
            
            $data = [
                'title' => 'Nuevo Peritaje Completo - Paso 10: Fugas y Niveles',
                'csrf_token' => $this->generateCSRFToken()
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
            
            // Verificar que existan datos de los pasos anteriores
            if (!isset($_SESSION['expertise_step1']) || 
                !isset($_SESSION['expertise_step2']) || 
                !isset($_SESSION['expertise_step3']) ||
                !isset($_SESSION['expertise_step4']) ||
                !isset($_SESSION['expertise_step5']) ||
                !isset($_SESSION['expertise_step6']) ||
                !isset($_SESSION['expertise_step7']) ||
                !isset($_SESSION['expertise_step8']) ||
                !isset($_SESSION['expertise_step9'])) {
                throw new Exception('Debe completar los pasos anteriores primero');
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
            $datos_fugas = [];
            foreach ($campos_fugas as $campo) {
                $datos_fugas[$campo] = $_POST[$campo] ?? '';
            }
            
            // Agregar campos adicionales
            $datos_fugas['prueba_ruta'] = $_POST['prueba_ruta'] ?? '';
            $datos_fugas['observaciones_fugas'] = $_POST['observaciones_fugas'] ?? '';
            
            // Guardar en sesión los datos del paso 10
            $_SESSION['expertise_step10'] = $datos_fugas;
            
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
            // Verificar que existan datos de los pasos anteriores
            if (!isset($_SESSION['expertise_step1']) || 
                !isset($_SESSION['expertise_step2']) || 
                !isset($_SESSION['expertise_step3']) ||
                !isset($_SESSION['expertise_step4']) ||
                !isset($_SESSION['expertise_step5']) ||
                !isset($_SESSION['expertise_step6']) ||
                !isset($_SESSION['expertise_step7']) ||
                !isset($_SESSION['expertise_step8']) ||
                !isset($_SESSION['expertise_step9']) ||
                !isset($_SESSION['expertise_step10'])) {
                throw new Exception('Debe completar los pasos anteriores primero');
            }
            
            $data = [
                'title' => 'Nuevo Peritaje Completo - Paso 11: Fijación Fotográfica',
                'csrf_token' => $this->generateCSRFToken()
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
            
            // Verificar que existan datos de los pasos anteriores
            if (!isset($_SESSION['expertise_step1']) || 
                !isset($_SESSION['expertise_step2']) || 
                !isset($_SESSION['expertise_step3']) ||
                !isset($_SESSION['expertise_step4']) ||
                !isset($_SESSION['expertise_step5']) ||
                !isset($_SESSION['expertise_step6']) ||
                !isset($_SESSION['expertise_step7']) ||
                !isset($_SESSION['expertise_step8']) ||
                !isset($_SESSION['expertise_step9']) ||
                !isset($_SESSION['expertise_step10'])) {
                throw new Exception('Debe completar los pasos anteriores primero');
            }
            
            // Validar que se hayan subido archivos
            if (!isset($_FILES['fotos']) || empty($_FILES['fotos']['name'][0])) {
                throw new Exception('Debe agregar al menos una fotografía');
            }
            
            $fotos_guardadas = [];
            
            // Crear directorio para fotos si no existe
            $upload_dir = PUBLIC_PATH . '/uploads/expertise/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
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
                    throw new Exception("Formato de archivo no permitido: {$file_name}");
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
                        'ruta' => 'uploads/expertise/' . $nuevo_nombre,
                        'size' => $file_size,
                        'extension' => $file_ext
                    ];
                } else {
                    throw new Exception("Error al guardar el archivo: {$file_name}");
                }
            }
            
            // Validar que se haya guardado al menos una foto
            if (empty($fotos_guardadas)) {
                throw new Exception('No se pudo guardar ninguna fotografía. Intente nuevamente.');
            }
            
            // Guardar en sesión
            $datos_fotograficos = [
                'fotos' => $fotos_guardadas,
                'total_fotos' => count($fotos_guardadas),
                'fecha_subida' => date('Y-m-d H:i:s')
            ];
            
            $_SESSION['expertise_step11'] = $datos_fotograficos;
            
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
            // Verificar que existan datos de todos los pasos
            if (!isset($_SESSION['expertise_step1']) || 
                !isset($_SESSION['expertise_step2']) || 
                !isset($_SESSION['expertise_step3']) ||
                !isset($_SESSION['expertise_step4']) ||
                !isset($_SESSION['expertise_step5']) ||
                !isset($_SESSION['expertise_step6']) ||
                !isset($_SESSION['expertise_step7']) ||
                !isset($_SESSION['expertise_step8']) ||
                !isset($_SESSION['expertise_step9']) ||
                !isset($_SESSION['expertise_step10']) ||
                !isset($_SESSION['expertise_step11'])) {
                throw new Exception('Debe completar todos los pasos antes de ver el resumen');
            }
            
            $data = [
                'title' => 'Nuevo Peritaje Completo - Paso 12: Resumen Final',
                'csrf_token' => $this->generateCSRFToken()
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
            
            // Pasos 3, 4, 5: Inspecciones (cargar datos básicos)
            // Las inspecciones se cargarán directamente desde el modelo en la vista
            $_SESSION['expertise_step3'] = [
                'inspecciones' => [],
                'observaciones_carroceria' => $expertise['observaciones_carroceria'] ?? ''
            ];
            
            $_SESSION['expertise_step4'] = [
                'inspecciones' => [],
                'observaciones_estructura' => $expertise['observaciones_estructura'] ?? ''
            ];
            
            $_SESSION['expertise_step5'] = [
                'inspecciones' => [],
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
            
            // Paso 11: Fotos
            $_SESSION['expertise_step11'] = [
                'fotos' => [],
                'observaciones_fotograficas' => $expertise['observaciones_fotograficas'] ?? ''
            ];
            
            $data = [
                'title' => 'Detalles del Peritaje #' . $expertise['service_number'],
                'csrf_token' => $this->generateCSRFToken(),
                'expertise_id' => $id,
                'view_mode' => true, // Flag para indicar que estamos en modo vista
                'backup_sessions' => $backup_sessions // Para restaurar después
            ];
            
            $this->view('expertise/step12', $data);
            
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('expertise');
        }
    }
    
    /**
     * Guardar peritaje completo en la base de datos
     */
    public function saveFinal() {
        try {
            // Verificar CSRF token
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token CSRF inválido');
            }
            
            // Verificar que existan datos de todos los pasos
            for ($i = 1; $i <= 11; $i++) {
                if (!isset($_SESSION['expertise_step' . $i])) {
                    throw new Exception('Debe completar todos los pasos antes de guardar');
                }
            }
            
            // Obtener datos de los pasos
            $step1 = $_SESSION['expertise_step1'];
            $step2 = $_SESSION['expertise_step2'];
            $step3 = $_SESSION['expertise_step3'];
            $step4 = $_SESSION['expertise_step4'];
            $step5 = $_SESSION['expertise_step5'];
            $step6 = $_SESSION['expertise_step6'];
            $step7 = $_SESSION['expertise_step7'];
            $step8 = $_SESSION['expertise_step8'];
            $step9 = $_SESSION['expertise_step9'];
            $step10 = $_SESSION['expertise_step10'];
            $step11 = $_SESSION['expertise_step11'];
            
            // Obtener conexión a la base de datos
            $database = new Database();
            $db = $database->getConnection();
            
            // Iniciar transacción
            $db->beginTransaction();
            
            try {
                // Generar código único del peritaje
                $codigo = 'PRT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
                
                // Preparar datos de motor y sistemas como JSON
                $motor_sistemas_json = json_encode($step9);
                
                // Preparar datos de fugas y niveles como JSON
                $fugas_niveles_json = json_encode($step10);
                
                // 1. Insertar en tabla principal expertises
                $sql = "INSERT INTO expertises (
                    codigo, client_id, user_id, vehicle_type_id,
                    service_date, service_number, service_for, agreement,
                    placa, marca, linea, modelo, color, clase_vehiculo, tipo_vehiculo,
                    tipo_carroceria, tipo_combustible, numero_motor, numero_chasis,
                    numero_serie, vin, kilometraje, cilindrada, capacidad_carga,
                    numero_ejes, numero_pasajeros, fecha_matricula,
                    llanta_anterior_izquierda, llanta_anterior_derecha,
                    llanta_posterior_izquierda, llanta_posterior_derecha,
                    observaciones_llantas,
                    amortiguador_anterior_izquierdo, amortiguador_anterior_derecho,
                    amortiguador_posterior_izquierdo, amortiguador_posterior_derecho,
                    observaciones_amortiguadores,
                    prueba_bateria, prueba_arranque, carga_bateria, observaciones_bateria,
                    motor_sistemas_data, observaciones_motor, observaciones_interior,
                    fugas_niveles_data, prueba_ruta, observaciones_fugas,
                    total_fotos, status, created_at
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, NOW()
                )";
                
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    $codigo,
                    $step1['client_id'],
                    $_SESSION['user_id'] ?? 1,
                    $step2['tipo_vehiculo'] ?? null,
                    $step1['service_date'],
                    $step1['service_number'] ?? null,
                    $step1['service_for'] ?? null,
                    $step1['agreement'] ?? null,
                    $step2['placa'],
                    $step2['marca'] ?? null,
                    $step2['linea'] ?? null,
                    $step2['modelo'] ?? null,
                    $step2['color'] ?? null,
                    $step2['clase_vehiculo'] ?? null,
                    $step2['tipo_vehiculo_text'] ?? null,
                    $step2['tipo_carroceria'] ?? null,
                    $step2['tipo_combustible'] ?? null,
                    $step2['numero_motor'] ?? null,
                    $step2['numero_chasis'] ?? null,
                    $step2['numero_serie'] ?? null,
                    $step2['vin'] ?? null,
                    $step2['kilometraje'] ?? null,
                    $step2['cilindrada'] ?? null,
                    $step2['capacidad_carga'] ?? null,
                    $step2['numero_ejes'] ?? null,
                    $step2['numero_pasajeros'] ?? null,
                    $step2['fecha_matricula'] ?? null,
                    $step6['llanta_anterior_izquierda'] ?? 0,
                    $step6['llanta_anterior_derecha'] ?? 0,
                    $step6['llanta_posterior_izquierda'] ?? 0,
                    $step6['llanta_posterior_derecha'] ?? 0,
                    $step6['observaciones_llantas'] ?? null,
                    $step7['amortiguador_anterior_izquierdo'] ?? 0,
                    $step7['amortiguador_anterior_derecho'] ?? 0,
                    $step7['amortiguador_posterior_izquierdo'] ?? 0,
                    $step7['amortiguador_posterior_derecho'] ?? 0,
                    $step7['observaciones_amortiguadores'] ?? null,
                    $step8['prueba_bateria'] ?? 0,
                    $step8['prueba_arranque'] ?? 0,
                    $step8['carga_bateria'] ?? 0,
                    $step8['observaciones_bateria'] ?? null,
                    $motor_sistemas_json,
                    $step9['observaciones_motor'] ?? null,
                    $step9['observaciones_interior'] ?? null,
                    $fugas_niveles_json,
                    $step10['prueba_ruta'] ?? null,
                    $step10['observaciones_fugas'] ?? null,
                    $step11['total_fotos'] ?? 0,
                    'completed'
                ]);
                
                $expertise_id = $db->lastInsertId();
                
                // 2. Insertar inspecciones (Pasos 3, 4, 5)
                $sql_inspection = "INSERT INTO expertise_inspections 
                    (expertise_id, section, pieza_id, concepto_id, observacion) 
                    VALUES (?, ?, ?, ?, ?)";
                $stmt_inspection = $db->prepare($sql_inspection);
                
                // Carrocería
                if (!empty($step3['inspecciones'])) {
                    foreach ($step3['inspecciones'] as $insp) {
                        $stmt_inspection->execute([
                            $expertise_id,
                            'carroceria',
                            $insp['pieza_id'],
                            $insp['concepto_id'],
                            $insp['observacion'] ?? null
                        ]);
                    }
                }
                
                // Estructura
                if (!empty($step4['inspecciones'])) {
                    foreach ($step4['inspecciones'] as $insp) {
                        $stmt_inspection->execute([
                            $expertise_id,
                            'estructura',
                            $insp['pieza_id'],
                            $insp['concepto_id'],
                            $insp['observacion'] ?? null
                        ]);
                    }
                }
                
                // Chasis
                if (!empty($step5['inspecciones'])) {
                    foreach ($step5['inspecciones'] as $insp) {
                        $stmt_inspection->execute([
                            $expertise_id,
                            'chasis',
                            $insp['pieza_id'],
                            $insp['concepto_id'],
                            $insp['observacion'] ?? null
                        ]);
                    }
                }
                
                // 3. Insertar fotografías
                if (!empty($step11['fotos'])) {
                    $sql_photo = "INSERT INTO expertise_photos 
                        (expertise_id, nombre_original, nombre_guardado, ruta, extension, size, orden) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt_photo = $db->prepare($sql_photo);
                    
                    $orden = 1;
                    foreach ($step11['fotos'] as $foto) {
                        $stmt_photo->execute([
                            $expertise_id,
                            $foto['nombre_original'],
                            $foto['nombre_guardado'],
                            $foto['ruta'],
                            $foto['extension'],
                            $foto['size'],
                            $orden++
                        ]);
                    }
                }
                
                // Commit de la transacción
                $db->commit();
                
                // Limpiar datos de los pasos (ya no son necesarios)
                for ($i = 1; $i <= 11; $i++) {
                    unset($_SESSION['expertise_step' . $i]);
                }
                
                $_SESSION['success'] = '¡Peritaje guardado exitosamente! Código: ' . $codigo;
                $this->redirect('expertise');
                
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al guardar el peritaje: ' . $e->getMessage();
            $this->redirect('expertise/step12');
        }
    }
    
}
