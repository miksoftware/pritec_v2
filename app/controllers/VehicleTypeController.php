<?php

require_once APP_PATH . '/models/VehicleType.php';
require_once APP_PATH . '/models/VehicleSection.php';
require_once APP_PATH . '/models/VehiclePiece.php';

class VehicleTypeController extends Controller
{
    private $vehicleTypeModel;
    private $vehicleSectionModel;
    private $vehiclePieceModel;
    
    public function __construct()
    {
        // Verificar que el usuario esté logueado
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header('Location: ' . APP_URL . 'login');
            exit();
        }
        
        parent::__construct();
        $this->vehicleTypeModel = new VehicleType();
        $this->vehicleSectionModel = new VehicleSection();
        $this->vehiclePieceModel = new VehiclePiece();
    }
    
    /**
     * Listar todos los tipos de vehículos
     */
    public function index()
    {
        try {
            $vehicleTypes = $this->vehicleTypeModel->getAllVehicleTypes();
            
            $data = [
                'title' => 'Tipos de Vehículos - Pritec v2.0',
                'vehicleTypes' => $vehicleTypes,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('vehicle_types/index', $data);
        } catch (Exception $e) {
            error_log("Error en VehicleTypeController::index: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar los tipos de vehículos';
            header('Location: ' . APP_URL . 'dashboard');
            exit();
        }
    }
    
    /**
     * Mostrar formulario para crear tipo de vehículo
     */
    public function create()
    {
        $data = [
            'title' => 'Crear Tipo de Vehículo - Pritec v2.0',
            'csrf_token' => $this->generateCSRFToken()
        ];
        
        $this->view('vehicle_types/create', $data);
    }
    
    /**
     * Guardar nuevo tipo de vehículo
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
            $required = ['type', 'name'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            // Validar tipo de vehículo
            if (!in_array($_POST['type'], ['carro', 'moto'])) {
                throw new Exception('Tipo de vehículo no válido');
            }
            
            // Verificar que el nombre no exista
            if ($this->vehicleTypeModel->nameExists($_POST['name'])) {
                throw new Exception('Ya existe un tipo de vehículo con este nombre');
            }
            
            // Crear tipo de vehículo
            $vehicleData = [
                'type' => $_POST['type'],
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'] ?? ''),
                'status' => $_POST['status'] ?? 'active'
            ];
            
            $vehicleTypeId = $this->vehicleTypeModel->create($vehicleData);
            
            if ($vehicleTypeId) {
                // Crear secciones automáticamente según el tipo
                $this->createDefaultSections($vehicleTypeId, $_POST['type']);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Tipo de vehículo creado exitosamente',
                    'redirect' => 'vehicle-types/' . $vehicleTypeId . '/sections'
                ]);
            } else {
                throw new Exception('Error al crear el tipo de vehículo');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Crear secciones por defecto según el tipo de vehículo
     */
    private function createDefaultSections($vehicleTypeId, $type)
    {
        $sections = [];
        
        if ($type === 'carro') {
            $sections = ['carroceria', 'estructura', 'chasis'];
        } else { // moto
            $sections = ['estructura', 'chasis'];
        }
        
        foreach ($sections as $sectionName) {
            $this->vehicleSectionModel->create([
                'vehicle_type_id' => $vehicleTypeId,
                'section_name' => $sectionName
            ]);
        }
    }
    
    /**
     * Formatear nombres de secciones para mostrar
     */
    private function formatSectionName($sectionName)
    {
        $names = [
            'carroceria' => 'Carrocería',
            'estructura' => 'Estructura',
            'chasis' => 'Chasis'
        ];
        
        return $names[$sectionName] ?? ucfirst($sectionName);
    }
    
    /**
     * Procesar secciones agregando nombres formateados
     */
    private function processSections($sections)
    {
        foreach ($sections as &$section) {
            $section['name'] = $this->formatSectionName($section['section_name']);
        }
        return $sections;
    }
    
    /**
     * Mostrar secciones de un tipo de vehículo
     */
    public function sections($id)
    {
        try {
            $vehicleType = $this->vehicleTypeModel->findById($id);
            
            if (!$vehicleType) {
                $_SESSION['error'] = 'Tipo de vehículo no encontrado';
                header('Location: ' . APP_URL . 'vehicle-types');
                exit();
            }
            
            $sections = $this->vehicleTypeModel->getSections($id);
            $sections = $this->processSections($sections);
            
            $data = [
                'title' => 'Configurar Secciones - ' . $vehicleType['name'],
                'vehicleType' => $vehicleType,
                'sections' => $sections,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('vehicle_types/sections', $data);
        } catch (Exception $e) {
            error_log("Error en VehicleTypeController::sections: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar las secciones';
            header('Location: ' . APP_URL . 'vehicle-types');
            exit();
        }
    }
    
    /**
     * Subir imagen para una sección
     */
    public function uploadSectionImage()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token de seguridad inválido');
            }
            
            if (!isset($_POST['section_id']) || !is_numeric($_POST['section_id'])) {
                throw new Exception('ID de sección no válido');
            }
            
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('No se pudo subir la imagen');
            }
            
            $sectionId = $_POST['section_id'];
            $section = $this->vehicleSectionModel->findById($sectionId);
            
            if (!$section) {
                throw new Exception('Sección no encontrada');
            }
            
            // Validar tipo de archivo
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            $fileType = $_FILES['image']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception('Solo se permiten imágenes JPG, JPEG y PNG');
            }
            
            // Crear directorio si no existe
            $uploadDir = 'public/assets/uploads/vehicle_sections/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generar nombre único para el archivo
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = 'section_' . $sectionId . '_' . time() . '.' . $extension;
            $filePath = $uploadDir . $fileName;
            
            // Redimensionar imagen a 400x400
            if ($this->resizeImage($_FILES['image']['tmp_name'], $filePath, 400, 400)) {
                // Actualizar la base de datos
                if ($this->vehicleSectionModel->updateImage($sectionId, $fileName)) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Imagen subida exitosamente',
                        'image_url' => ASSETS_URL . 'uploads/vehicle_sections/' . $fileName
                    ]);
                } else {
                    throw new Exception('Error al guardar la referencia de la imagen');
                }
            } else {
                throw new Exception('Error al procesar la imagen');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Redimensionar imagen
     */
    private function resizeImage($source, $destination, $width, $height)
    {
        try {
            $imageInfo = getimagesize($source);
            $mime = $imageInfo['mime'];
            
            switch ($mime) {
                case 'image/jpeg':
                    $sourceImage = imagecreatefromjpeg($source);
                    break;
                case 'image/png':
                    $sourceImage = imagecreatefrompng($source);
                    break;
                default:
                    return false;
            }
            
            $destImage = imagecreatetruecolor($width, $height);
            
            // Mantener transparencia para PNG
            if ($mime === 'image/png') {
                imagealphablending($destImage, false);
                imagesavealpha($destImage, true);
                $transparent = imagecolorallocatealpha($destImage, 255, 255, 255, 127);
                imagefilledrectangle($destImage, 0, 0, $width, $height, $transparent);
            }
            
            imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $width, $height, imagesx($sourceImage), imagesy($sourceImage));
            
            $result = false;
            if ($mime === 'image/jpeg') {
                $result = imagejpeg($destImage, $destination, 90);
            } elseif ($mime === 'image/png') {
                $result = imagepng($destImage, $destination);
            }
            
            imagedestroy($sourceImage);
            imagedestroy($destImage);
            
            return $result;
            
        } catch (Exception $e) {
            error_log("Error resizing image: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mostrar formulario de edición de tipo de vehículo
     */
    public function edit($id)
    {
        try {
            $vehicleType = $this->vehicleTypeModel->findById($id);
            
            if (!$vehicleType) {
                $_SESSION['error'] = 'Tipo de vehículo no encontrado';
                header('Location: ' . APP_URL . 'vehicle-types');
                exit();
            }
            
            $sections = $this->vehicleTypeModel->getSections($id);
            $sections = $this->processSections($sections);
            
            $data = [
                'title' => 'Editar Tipo de Vehículo - ' . $vehicleType['name'],
                'vehicleType' => $vehicleType,
                'sections' => $sections,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('vehicle_types/edit', $data);
        } catch (Exception $e) {
            error_log("Error en VehicleTypeController::edit: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar el tipo de vehículo';
            header('Location: ' . APP_URL . 'vehicle-types');
            exit();
        }
    }
    
    /**
     * Actualizar tipo de vehículo
     */
    public function update()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token de seguridad inválido');
            }
            
            if (empty($_POST['id']) || !is_numeric($_POST['id'])) {
                throw new Exception('ID de tipo de vehículo no válido');
            }
            
            $id = $_POST['id'];
            $vehicleType = $this->vehicleTypeModel->findById($id);
            
            if (!$vehicleType) {
                throw new Exception('Tipo de vehículo no encontrado');
            }
            
            // Validar datos requeridos
            $required = ['type', 'name'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            // Validar tipo de vehículo
            if (!in_array($_POST['type'], ['carro', 'moto'])) {
                throw new Exception('Tipo de vehículo no válido');
            }
            
            // Verificar que el nombre no exista (excepto el actual)
            if ($this->vehicleTypeModel->nameExists($_POST['name'], $id)) {
                throw new Exception('Ya existe un tipo de vehículo con este nombre');
            }
            
            // Actualizar datos
            $updateData = [
                'type' => $_POST['type'],
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'] ?? ''),
                'status' => $_POST['status'] ?? 'active'
            ];
            
            if ($this->vehicleTypeModel->update($id, $updateData)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Tipo de vehículo actualizado exitosamente'
                ]);
            } else {
                throw new Exception('Error al actualizar el tipo de vehículo');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Eliminar tipo de vehículo
     */
    public function delete($id = null)
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            // Obtener datos del JSON body
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Verificar token CSRF
            if (!isset($input['csrf_token']) || 
                !isset($_SESSION[CSRF_TOKEN_NAME]) || 
                !hash_equals($_SESSION[CSRF_TOKEN_NAME], $input['csrf_token'])) {
                throw new Exception('Token de seguridad inválido');
            }
            
            // El ID viene desde la URL
            if (empty($id) || !is_numeric($id)) {
                throw new Exception('ID de tipo de vehículo no válido');
            }
            
            $vehicleType = $this->vehicleTypeModel->findById($id);
            
            if (!$vehicleType) {
                throw new Exception('Tipo de vehículo no encontrado');
            }
            
            if ($this->vehicleTypeModel->delete($id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Tipo de vehículo eliminado exitosamente'
                ]);
            } else {
                throw new Exception('Error al eliminar el tipo de vehículo');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Cambiar estado de tipo de vehículo
     */
    public function toggleStatus($id = null)
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            // Obtener datos del JSON body
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Verificar token CSRF
            if (!isset($input['csrf_token']) || 
                !isset($_SESSION[CSRF_TOKEN_NAME]) || 
                !hash_equals($_SESSION[CSRF_TOKEN_NAME], $input['csrf_token'])) {
                throw new Exception('Token de seguridad inválido');
            }
            
            // El ID viene desde la URL
            if (empty($id) || !is_numeric($id)) {
                throw new Exception('ID de tipo de vehículo no válido');
            }
            
            $vehicleType = $this->vehicleTypeModel->findById($id);
            
            if (!$vehicleType) {
                throw new Exception('Tipo de vehículo no encontrado');
            }
            
            $newStatus = $vehicleType['status'] === 'active' ? 'inactive' : 'active';
            
            if ($this->vehicleTypeModel->updateStatus($id, $newStatus)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Estado actualizado exitosamente',
                    'new_status' => $newStatus
                ]);
            } else {
                throw new Exception('Error al cambiar el estado');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Crear secciones automáticamente
     */
    public function createSections()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token de seguridad inválido');
            }
            
            if (empty($_POST['vehicle_type_id']) || !is_numeric($_POST['vehicle_type_id'])) {
                throw new Exception('ID de tipo de vehículo no válido');
            }
            
            $vehicleTypeId = $_POST['vehicle_type_id'];
            $vehicleType = $this->vehicleTypeModel->findById($vehicleTypeId);
            
            if (!$vehicleType) {
                throw new Exception('Tipo de vehículo no encontrado');
            }
            
            // Verificar si ya tiene secciones
            $existingSections = $this->vehicleTypeModel->getSections($vehicleTypeId);
            if (count($existingSections) > 0) {
                throw new Exception('Este tipo de vehículo ya tiene secciones configuradas');
            }
            
            // Crear secciones según el tipo
            $this->createDefaultSections($vehicleTypeId, $vehicleType['type']);
            
            echo json_encode([
                'success' => true,
                'message' => 'Secciones creadas exitosamente'
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Mostrar vista de gestión de piezas para una sección
     */
    public function pieces($sectionId)
    {
        try {
            $section = $this->vehicleSectionModel->findById($sectionId);
            
            if (!$section) {
                $_SESSION['error'] = 'Sección no encontrada';
                header('Location: ' . APP_URL . 'vehicle-types');
                exit();
            }
            
            $vehicleType = $this->vehicleTypeModel->findById($section['vehicle_type_id']);
            $allSections = $this->vehicleTypeModel->getSections($section['vehicle_type_id']);
            $allSections = $this->processSections($allSections);
            $pieces = $this->vehiclePieceModel->getBySectionId($sectionId);
            
            // Formatear el nombre de la sección actual
            $section['name'] = $this->formatSectionName($section['section_name']);
            
            // Encontrar la siguiente sección
            $nextSection = null;
            $currentIndex = array_search($sectionId, array_column($allSections, 'id'));
            if ($currentIndex !== false && isset($allSections[$currentIndex + 1])) {
                $nextSection = $allSections[$currentIndex + 1];
            }
            
            $data = [
                'title' => 'Definir Piezas - ' . $section['name'],
                'vehicleType' => $vehicleType,
                'section' => $section,
                'allSections' => $allSections,
                'pieces' => $pieces,
                'nextSection' => $nextSection,
                'csrf_token' => $this->generateCSRFToken()
            ];
            
            $this->view('vehicle_types/pieces', $data);
            
        } catch (Exception $e) {
            error_log("Error en VehicleTypeController::pieces: " . $e->getMessage());
            $_SESSION['error'] = 'Error al cargar la gestión de piezas';
            header('Location: ' . APP_URL . 'vehicle-types');
            exit();
        }
    }
    
    /**
     * Agregar nueva pieza
     */
    public function addPiece()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token de seguridad inválido');
            }
            
            $required = ['section_id', 'piece_number', 'position_x', 'position_y'];
            foreach ($required as $field) {
                if (!isset($_POST[$field]) || $_POST[$field] === '') {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            $sectionId = $_POST['section_id'];
            $pieceNumber = $_POST['piece_number'];
            $positionX = $_POST['position_x'];
            $positionY = $_POST['position_y'];
            $pieceName = $_POST['piece_name'] ?? '';
            
            // Validar que la sección existe
            $section = $this->vehicleSectionModel->findById($sectionId);
            if (!$section) {
                throw new Exception('Sección no encontrada');
            }
            
            // Validar que el número de pieza no exista en esta sección
            if ($this->vehiclePieceModel->pieceNumberExists($sectionId, $pieceNumber)) {
                throw new Exception('Ya existe una pieza con este número en esta sección');
            }
            
            $pieceData = [
                'section_id' => $sectionId,
                'piece_number' => $pieceNumber,
                'piece_name' => $pieceName,
                'position_x' => $positionX,
                'position_y' => $positionY
            ];
            
            $pieceId = $this->vehiclePieceModel->create($pieceData);
            
            if ($pieceId) {
                $piece = $this->vehiclePieceModel->findById($pieceId);
                echo json_encode([
                    'success' => true,
                    'message' => 'Pieza agregada exitosamente',
                    'piece' => $piece
                ]);
            } else {
                throw new Exception('Error al agregar la pieza');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Actualizar pieza
     */
    public function updatePiece()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token de seguridad inválido');
            }
            
            if (empty($_POST['piece_id']) || !is_numeric($_POST['piece_id'])) {
                throw new Exception('ID de pieza no válido');
            }
            
            $pieceId = $_POST['piece_id'];
            $piece = $this->vehiclePieceModel->findById($pieceId);
            
            if (!$piece) {
                throw new Exception('Pieza no encontrada');
            }
            
            $required = ['piece_number', 'position_x', 'position_y'];
            foreach ($required as $field) {
                if (!isset($_POST[$field]) || $_POST[$field] === '') {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            $pieceNumber = $_POST['piece_number'];
            $pieceName = $_POST['piece_name'] ?? '';
            $positionX = $_POST['position_x'];
            $positionY = $_POST['position_y'];
            
            // Validar que el número de pieza no exista (excepto la actual)
            if ($this->vehiclePieceModel->pieceNumberExists($piece['section_id'], $pieceNumber, $pieceId)) {
                throw new Exception('Ya existe una pieza con este número en esta sección');
            }
            
            $updateData = [
                'piece_number' => $pieceNumber,
                'piece_name' => $pieceName,
                'position_x' => $positionX,
                'position_y' => $positionY
            ];
            
            if ($this->vehiclePieceModel->update($pieceId, $updateData)) {
                $updatedPiece = $this->vehiclePieceModel->findById($pieceId);
                echo json_encode([
                    'success' => true,
                    'message' => 'Pieza actualizada exitosamente',
                    'piece' => $updatedPiece
                ]);
            } else {
                throw new Exception('Error al actualizar la pieza');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Actualizar solo la posición de una pieza
     */
    public function updatePiecePosition()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token de seguridad inválido');
            }
            
            $required = ['piece_id', 'position_x', 'position_y'];
            foreach ($required as $field) {
                if (!isset($_POST[$field]) || $_POST[$field] === '') {
                    throw new Exception("El campo $field es requerido");
                }
            }
            
            $pieceId = $_POST['piece_id'];
            $positionX = $_POST['position_x'];
            $positionY = $_POST['position_y'];
            
            $piece = $this->vehiclePieceModel->findById($pieceId);
            if (!$piece) {
                throw new Exception('Pieza no encontrada');
            }
            
            if ($this->vehiclePieceModel->updatePosition($pieceId, $positionX, $positionY)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Posición actualizada exitosamente'
                ]);
            } else {
                throw new Exception('Error al actualizar la posición');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Eliminar pieza
     */
    public function deletePiece()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token de seguridad inválido');
            }
            
            if (empty($_POST['piece_id']) || !is_numeric($_POST['piece_id'])) {
                throw new Exception('ID de pieza no válido');
            }
            
            $pieceId = $_POST['piece_id'];
            $piece = $this->vehiclePieceModel->findById($pieceId);
            
            if (!$piece) {
                throw new Exception('Pieza no encontrada');
            }
            
            if ($this->vehiclePieceModel->delete($pieceId)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Pieza eliminada exitosamente'
                ]);
            } else {
                throw new Exception('Error al eliminar la pieza');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Eliminar todas las piezas de una sección
     */
    public function clearPieces()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Método no permitido');
            }
            
            if (!$this->verifyCSRFToken()) {
                throw new Exception('Token de seguridad inválido');
            }
            
            if (empty($_POST['section_id']) || !is_numeric($_POST['section_id'])) {
                throw new Exception('ID de sección no válido');
            }
            
            $sectionId = $_POST['section_id'];
            $section = $this->vehicleSectionModel->findById($sectionId);
            
            if (!$section) {
                throw new Exception('Sección no encontrada');
            }
            
            if ($this->vehiclePieceModel->deleteBySection($sectionId)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Todas las piezas han sido eliminadas'
                ]);
            } else {
                throw new Exception('Error al eliminar las piezas');
            }
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Obtener información de una sección
     */
    public function section($sectionId)
    {
        header('Content-Type: application/json');
        
        try {
            $section = $this->vehicleSectionModel->findById($sectionId);
            
            if (!$section) {
                throw new Exception('Sección no encontrada');
            }
            
            $piecesCount = $this->vehiclePieceModel->countBySection($sectionId);
            
            $sectionData = [
                'id' => $section['id'],
                'name' => $this->formatSectionName($section['section_name']),
                'image_url' => $section['image_path'] ? ASSETS_URL . 'uploads/vehicle_sections/' . $section['image_path'] : null,
                'pieces_count' => $piecesCount
            ];
            
            echo json_encode([
                'success' => true,
                'section' => $sectionData
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
