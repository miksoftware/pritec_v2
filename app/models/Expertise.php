<?php
/**
 * Modelo Expertise
 * Maneja las operaciones CRUD para la tabla de peritajes completos
 */

require_once APP_PATH . '/core/Model.php';

class Expertise extends Model {
    protected $table = 'expertises';
    protected $fillable = [
        'client_id',
        'service_date',
        'service_number',
        'service_for',
        'agreement',
        'tipo_vehiculo',
        'placa',
        'marca',
        'linea',
        'modelo',
        'color',
        'kilometraje',
        'clase',
        'vin',
        'motor',
        'chasis',
        'cilindros',
        'capacidad_carga',
        'pasajeros',
        'ejes',
        'numero_serie',
        'llanta_anterior_izquierda',
        'llanta_anterior_derecha',
        'llanta_posterior_izquierda',
        'llanta_posterior_derecha',
        'amortiguador_anterior_izquierdo',
        'amortiguador_anterior_derecho',
        'amortiguador_posterior_izquierdo',
        'amortiguador_posterior_derecho',
        'prueba_bateria',
        'prueba_arranque',
        'carga_bateria',
        'estado_motor',
        'estado_transmision',
        'estado_direccion',
        'estado_suspension',
        'estado_frenos',
        'estado_escape',
        'estado_radiador',
        'estado_ventilador',
        'estado_alternador',
        'estado_motor_arranque',
        'estado_bomba_agua',
        'estado_bomba_direccion',
        'estado_compresor_ac',
        'estado_multiple_admision',
        'estado_multiple_escape',
        'estado_bujias',
        'estado_cables_bujias',
        'estado_bobinas',
        'estado_sensores',
        'estado_inyectores',
        'estado_bomba_combustible',
        'estado_filtro_aire',
        'estado_filtro_combustible',
        'estado_correa_accesorios',
        'estado_correa_tiempo',
        'tension_correa_accesorios',
        'tension_correa_tiempo',
        'estado_bomba_freno',
        'estado_bomba_clutch',
        'estado_cilindro_clutch',
        'estado_tapiceria',
        'observaciones_motor',
        'observaciones_interior',
        'fuga_motor',
        'fuga_transmision',
        'fuga_diferencial',
        'fuga_caja_direccion',
        'fuga_bomba_direccion',
        'fuga_cremallera',
        'fuga_amortiguadores',
        'fuga_radiador',
        'fuga_bomba_agua',
        'fuga_mangueras',
        'fuga_tanque_combustible',
        'fuga_lineas_combustible',
        'fuga_sistema_frenos',
        'fuga_bomba_freno',
        'fuga_cilindros_freno',
        'nivel_aceite_motor',
        'nivel_liquido_frenos',
        'nivel_refrigerante',
        'nivel_aceite_direccion',
        'prueba_ruta',
        'observaciones_fugas',
        'status'
    ];
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Obtener todos los peritajes con información relacionada
     */
    public function getAllWithRelations($page = 1, $limit = 10, $search = '', $month = '') {
        $offset = ($page - 1) * $limit;
        
        $sql = "SELECT 
                    e.*,
                    c.first_name as cliente_nombre,
                    c.last_name as cliente_apellido,
                    c.phone as cliente_telefono,
                    c.email as cliente_email,
                    vt.name as tipo_vehiculo_nombre,
                    (SELECT COUNT(*) FROM expertise_inspections WHERE expertise_id = e.id) as total_inspecciones,
                    (SELECT COUNT(*) FROM expertise_photos WHERE expertise_id = e.id) as total_fotos
                FROM expertises e
                LEFT JOIN clients c ON e.client_id = c.id
                LEFT JOIN vehicle_types vt ON e.tipo_vehiculo = vt.id
                WHERE e.status = 'completed'";
        
        $params = [];
        
        // Filtro de búsqueda
        if (!empty($search)) {
            $sql .= " AND (
                e.service_number LIKE ? OR
                e.placa LIKE ? OR
                e.marca LIKE ? OR
                c.first_name LIKE ? OR
                c.last_name LIKE ?
            )";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        // Filtro de mes
        if (!empty($month)) {
            $sql .= " AND DATE_FORMAT(e.service_date, '%Y-%m') = ?";
            $params[] = $month;
        }
        
        $sql .= " ORDER BY e.created_at DESC";
        
        // Si se solicita paginación
        if ($limit > 0) {
            $sql .= " LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;
        }
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Contar total de peritajes con filtros
     */
    public function count($search = '', $month = '') {
        $sql = "SELECT COUNT(*) as total 
                FROM expertises e
                LEFT JOIN clients c ON e.client_id = c.id
                WHERE e.status = 'completed'";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (
                e.service_number LIKE ? OR
                e.placa LIKE ? OR
                e.marca LIKE ? OR
                c.first_name LIKE ? OR
                c.last_name LIKE ?
            )";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($month)) {
            $sql .= " AND DATE_FORMAT(e.service_date, '%Y-%m') = ?";
            $params[] = $month;
        }
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    /**
     * Obtener un peritaje por ID con información relacionada
     */
    public function getByIdWithRelations($id) {
        $sql = "SELECT 
                    e.*,
                    c.first_name as cliente_nombre,
                    c.last_name as cliente_apellido,
                    c.phone as cliente_telefono,
                    c.email as cliente_email,
                    c.identification as cliente_identificacion,
                    c.address as cliente_direccion,
                    vt.name as tipo_vehiculo_nombre,
                    vt.description as tipo_vehiculo_descripcion
                FROM expertises e
                LEFT JOIN clients c ON e.client_id = c.id
                LEFT JOIN vehicle_types vt ON e.tipo_vehiculo = vt.id
                WHERE e.id = ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Crear un nuevo peritaje completo con transacción
     */
    public function createWithTransaction($expertiseData, $inspectionsData = [], $photosData = []) {
        $db = $this->db->getConnection();
        
        try {
            $db->beginTransaction();
            
            // 1. Insertar expertise
            $expertiseId = $this->create($expertiseData);
            
            if (!$expertiseId) {
                throw new Exception('Error al crear el peritaje');
            }
            
            // 2. Insertar inspecciones si existen
            if (!empty($inspectionsData)) {
                $this->insertInspections($expertiseId, $inspectionsData);
            }
            
            // 3. Insertar fotos si existen
            if (!empty($photosData)) {
                $this->insertPhotos($expertiseId, $photosData);
            }
            
            $db->commit();
            
            return $expertiseId;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw new Exception('Error en la transacción: ' . $e->getMessage());
        }
    }
    
    /**
     * Insertar inspecciones para un peritaje
     */
    private function insertInspections($expertiseId, $inspections) {
        $sql = "INSERT INTO expertise_inspections 
                (expertise_id, pieza_id, concepto_id, created_at) 
                VALUES (?, ?, ?, NOW())";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        
        foreach ($inspections as $inspection) {
            $stmt->execute([
                $expertiseId,
                $inspection['pieza_id'],
                $inspection['concepto_id']
            ]);
        }
        
        return true;
    }
    
    /**
     * Insertar fotos para un peritaje
     */
    private function insertPhotos($expertiseId, $photos) {
        $sql = "INSERT INTO expertise_photos 
                (expertise_id, file_path, file_name, description, created_at) 
                VALUES (?, ?, ?, ?, NOW())";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        
        foreach ($photos as $photo) {
            $stmt->execute([
                $expertiseId,
                $photo['file_path'],
                $photo['file_name'],
                $photo['description'] ?? null
            ]);
        }
        
        return true;
    }
    
    /**
     * Obtener inspecciones de un peritaje
     */
    public function getInspections($expertiseId) {
        $sql = "SELECT 
                    ei.*,
                    vp.piece_name,
                    vp.piece_number,
                    ic.name as concepto_nombre
                FROM expertise_inspections ei
                LEFT JOIN vehicle_pieces vp ON ei.pieza_id = vp.id
                LEFT JOIN inspection_concepts ic ON ei.concepto_id = ic.id
                WHERE ei.expertise_id = ?
                ORDER BY ei.created_at";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$expertiseId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener fotos de un peritaje
     */
    public function getPhotos($expertiseId) {
        $sql = "SELECT * FROM expertise_photos 
                WHERE expertise_id = ? 
                ORDER BY created_at";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$expertiseId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Eliminar un peritaje con sus relaciones (transacción)
     */
    public function deleteWithRelations($id) {
        $db = $this->db->getConnection();
        
        try {
            $db->beginTransaction();
            
            // 1. Obtener fotos para eliminar archivos físicos
            $photos = $this->getPhotos($id);
            
            // 2. Eliminar inspecciones
            $sql = "DELETE FROM expertise_inspections WHERE expertise_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            
            // 3. Eliminar fotos de la base de datos
            $sql = "DELETE FROM expertise_photos WHERE expertise_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            
            // 4. Eliminar peritaje
            $sql = "DELETE FROM expertises WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            
            $db->commit();
            
            // 5. Eliminar archivos físicos de fotos
            foreach ($photos as $photo) {
                $filePath = PUBLIC_PATH . $photo['file_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            return true;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw new Exception('Error al eliminar el peritaje: ' . $e->getMessage());
        }
    }
    
    /**
     * Obtener estadísticas de peritajes
     */
    public function getStats() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    COUNT(CASE WHEN MONTH(service_date) = MONTH(CURDATE()) 
                        AND YEAR(service_date) = YEAR(CURDATE()) THEN 1 END) as this_month,
                    (SELECT COUNT(*) FROM expertise_inspections) as total_inspections,
                    (SELECT COUNT(*) FROM expertise_photos) as total_photos
                FROM expertises 
                WHERE status = 'completed'";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Buscar clientes (para el formulario)
     */
    public function searchClients($search) {
        if (strlen($search) < 3) {
            return [];
        }
        
        $searchParam = "%{$search}%";
        
        $sql = "SELECT id, first_name, last_name, identification, phone, email, address 
                FROM clients 
                WHERE status = 'active' 
                AND (
                    first_name LIKE ? OR 
                    last_name LIKE ? OR 
                    identification LIKE ? OR 
                    phone LIKE ? OR 
                    email LIKE ?
                )
                ORDER BY first_name, last_name
                LIMIT 20";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$searchParam, $searchParam, $searchParam, $searchParam, $searchParam]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Buscar tipos de vehículos
     */
    public function searchVehicleTypes($search = '') {
        if (strlen($search) > 0) {
            $searchParam = "%{$search}%";
            $sql = "SELECT id, name, description 
                    FROM vehicle_types 
                    WHERE status = 'active' 
                    AND (name LIKE ? OR description LIKE ?)
                    ORDER BY name
                    LIMIT 20";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$searchParam, $searchParam]);
        } else {
            $sql = "SELECT id, name, description 
                    FROM vehicle_types 
                    WHERE status = 'active'
                    ORDER BY name
                    LIMIT 20";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute();
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener piezas por tipo de vehículo y sección
     */
    public function getPiecesByVehicleTypeAndSection($vehicleTypeId, $section) {
        // 1. Obtener el section_id
        $sql = "SELECT id FROM vehicle_sections 
                WHERE vehicle_type_id = ? AND section_name = ? ";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$vehicleTypeId, $section]);
        $sectionData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$sectionData) {
            return [];
        }
        
        // 2. Obtener piezas de esa sección
        $sql = "SELECT id, piece_number, piece_name 
                FROM vehicle_pieces 
                WHERE section_id = ?
                ORDER BY piece_number";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$sectionData['id']]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener conceptos de inspección por categoría
     */
    public function getInspectionConceptsByCategory($category) {
        $sql = "SELECT id, name, display_order 
                FROM inspection_concepts 
                WHERE (category = ? OR category = 'all') 
                AND status = 'active'
                ORDER BY display_order, name";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$category]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Verificar si un número de servicio ya existe
     */
    public function serviceNumberExists($serviceNumber, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM expertises WHERE service_number = ?";
        $params = [$serviceNumber];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
