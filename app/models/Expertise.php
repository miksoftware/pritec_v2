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
                    vt.description as tipo_vehiculo_descripcion,
                    vt.type as tipo_vehiculo_type
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
            $sql = "SELECT id, name, description, type 
                    FROM vehicle_types 
                    WHERE status = 'active' 
                    AND (name LIKE ? OR description LIKE ?)
                    ORDER BY type DESC, name ASC
                    LIMIT 20";
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute([$searchParam, $searchParam]);
        } else {
            $sql = "SELECT id, name, description, type 
                    FROM vehicle_types 
                    WHERE status = 'active'
                    ORDER BY type DESC, name ASC
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
    
    /**
     * Crear un borrador inicial de peritaje (Paso 1)
     * @param array $data Datos del paso 1
     * @return int ID del expertise creado
     */
    public function createDraft($data) {
        // Generar código único del peritaje
        $codigo = 'PRT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        
        $sql = "INSERT INTO expertises (
            codigo,
            client_id,
            user_id,
            service_date,
            service_number,
            service_for,
            agreement,
            placa,
            status,
            current_step,
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'draft', 1, NOW())";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([
            $codigo,
            $data['client_id'],
            $data['user_id'],
            $data['service_date'],
            $data['service_number'] ?? null,
            $data['service_for'] ?? null,
            $data['agreement'] ?? null,
            $data['placa'] ?? '' // Placa temporal
        ]);
        
        return $this->db->getConnection()->lastInsertId();
    }
    
    /**
     * Obtener el último borrador de un usuario
     * @param int $userId ID del usuario
     * @return array|false Datos del expertise o false si no existe
     */
    public function getLastDraft($userId) {
        $sql = "SELECT * FROM expertises 
                WHERE user_id = ? 
                AND status IN ('draft', 'in_progress')
                ORDER BY updated_at DESC 
                LIMIT 1";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$userId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtener un expertise completo por ID (con JSONs decodificados)
     * @param int $id ID del expertise
     * @return array|false Datos completos o false si no existe
     */
    public function getByIdComplete($id) {
        $expertise = $this->getByIdWithRelations($id);
        
        if (!$expertise) {
            return false;
        }
        
        // Decodificar JSONs
        if (!empty($expertise['motor_sistemas_data'])) {
            $expertise['motor_sistemas_data'] = json_decode($expertise['motor_sistemas_data'], true);
        }
        
        if (!empty($expertise['fugas_niveles_data'])) {
            $expertise['fugas_niveles_data'] = json_decode($expertise['fugas_niveles_data'], true);
        }
        
        return $expertise;
    }
    
    /**
     * Actualizar datos del Paso 2 (Datos del Vehículo)
     * @param int $id ID del expertise
     * @param array $data Datos del vehículo
     * @return bool True si se actualizó correctamente
     */
    public function updateStep2($id, $data) {
        $sql = "UPDATE expertises SET
            vehicle_type_id = ?,
            placa = ?,
            marca = ?,
            linea = ?,
            modelo = ?,
            color = ?,
            clase_vehiculo = ?,
            tipo_vehiculo = ?,
            tipo_carroceria = ?,
            tipo_combustible = ?,
            numero_motor = ?,
            numero_chasis = ?,
            numero_serie = ?,
            vin = ?,
            kilometraje = ?,
            cilindrada = ?,
            capacidad_carga = ?,
            numero_ejes = ?,
            numero_pasajeros = ?,
            fecha_matricula = ?,
            current_step = 2,
            status = 'in_progress',
            updated_at = NOW()
        WHERE id = ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['tipo_vehiculo'] ?? null,  // vehicle_type_id (ID numérico)
            $data['placa'],
            $data['marca'] ?? null,
            $data['linea'] ?? null,
            $data['modelo'] ?? null,
            $data['color'] ?? null,
            $data['clase_vehiculo'] ?? null,
            $data['tipo_vehiculo'] ?? null,  // tipo_vehiculo (mismo ID, para compatibilidad)
            $data['tipo_carroceria'] ?? null,
            $data['tipo_combustible'] ?? null,
            $data['numero_motor'] ?? null,
            $data['numero_chasis'] ?? null,
            $data['numero_serie'] ?? null,
            $data['vin'] ?? null,
            $data['kilometraje'] ?? null,
            $data['cilindrada'] ?? null,
            $data['capacidad_carga'] ?? null,
            $data['numero_ejes'] ?? null,
            $data['numero_pasajeros'] ?? null,
            $data['fecha_matricula'] ?? null,
            $id
        ]);
    }
    
    /**
     * Actualizar inspecciones de un peritaje (Pasos 3, 4, 5)
     * @param int $expertiseId ID del expertise
     * @param string $section Sección: 'carroceria', 'estructura', 'chasis'
     * @param array $inspecciones Array de inspecciones
     * @param int $currentStep Paso actual (3, 4 o 5)
     * @return bool True si se actualizó correctamente
     */
    public function updateInspections($expertiseId, $section, $inspecciones, $currentStep, $observacionGeneral = null) {
        $db = $this->db->getConnection();
        
        try {
            $db->beginTransaction();
            
            // 1. Eliminar inspecciones anteriores de esta sección
            $sqlDelete = "DELETE FROM expertise_inspections 
                         WHERE expertise_id = ? AND section = ?";
            $stmtDelete = $db->prepare($sqlDelete);
            $stmtDelete->execute([$expertiseId, $section]);
            
            // 2. Insertar nuevas inspecciones
            if (!empty($inspecciones)) {
                $sqlInsert = "INSERT INTO expertise_inspections 
                             (expertise_id, section, pieza_id, concepto_id, created_at) 
                             VALUES (?, ?, ?, ?, NOW())";
                $stmtInsert = $db->prepare($sqlInsert);
                
                foreach ($inspecciones as $insp) {
                    $stmtInsert->execute([
                        $expertiseId,
                        $section,
                        $insp['pieza_id'],
                        $insp['concepto_id']
                    ]);
                }
            }
            
            // 3. Actualizar observaciones generales y current_step
            $observacionColumn = 'observaciones_' . $section;
            $sqlUpdate = "UPDATE expertises SET 
                         current_step = ?,
                         {$observacionColumn} = ?,
                         status = 'in_progress',
                         updated_at = NOW()
                         WHERE id = ?";
            $stmtUpdate = $db->prepare($sqlUpdate);
            $stmtUpdate->execute([$currentStep, $observacionGeneral, $expertiseId]);
            
            $db->commit();
            return true;
            
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
    
    /**
     * Actualizar datos del Paso 6 (Llantas)
     * @param int $id ID del expertise
     * @param array $data Datos de llantas
     * @return bool True si se actualizó correctamente
     */
    public function updateStep6($id, $data) {
        $sql = "UPDATE expertises SET
            llanta_anterior_izquierda = ?,
            llanta_anterior_derecha = ?,
            llanta_posterior_izquierda = ?,
            llanta_posterior_derecha = ?,
            observaciones_llantas = ?,
            current_step = 6,
            status = 'in_progress',
            updated_at = NOW()
        WHERE id = ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['llanta_anterior_izquierda'] ?? 0,
            $data['llanta_anterior_derecha'] ?? 0,
            $data['llanta_posterior_izquierda'] ?? 0,
            $data['llanta_posterior_derecha'] ?? 0,
            $data['observaciones_llantas'] ?? null,
            $id
        ]);
    }
    
    /**
     * Actualizar datos del Paso 7 (Amortiguadores)
     * @param int $id ID del expertise
     * @param array $data Datos de amortiguadores
     * @return bool True si se actualizó correctamente
     */
    public function updateStep7($id, $data) {
        $sql = "UPDATE expertises SET
            amortiguador_anterior_izquierdo = ?,
            amortiguador_anterior_derecho = ?,
            amortiguador_posterior_izquierdo = ?,
            amortiguador_posterior_derecho = ?,
            observaciones_amortiguadores = ?,
            current_step = 7,
            status = 'in_progress',
            updated_at = NOW()
        WHERE id = ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['amortiguador_anterior_izquierdo'] ?? 0,
            $data['amortiguador_anterior_derecho'] ?? 0,
            $data['amortiguador_posterior_izquierdo'] ?? 0,
            $data['amortiguador_posterior_derecho'] ?? 0,
            $data['observaciones_amortiguadores'] ?? null,
            $id
        ]);
    }
    
    /**
     * Actualizar datos del Paso 8 (Batería)
     * @param int $id ID del expertise
     * @param array $data Datos de batería
     * @return bool True si se actualizó correctamente
     */
    public function updateStep8($id, $data) {
        $sql = "UPDATE expertises SET
            prueba_bateria = ?,
            prueba_arranque = ?,
            carga_bateria = ?,
            observaciones_bateria = ?,
            current_step = 8,
            status = 'in_progress',
            updated_at = NOW()
        WHERE id = ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $data['prueba_bateria'] ?? 0,
            $data['prueba_arranque'] ?? 0,
            $data['carga_bateria'] ?? 0,
            $data['observaciones_bateria'] ?? null,
            $id
        ]);
    }
    
    /**
     * Actualizar datos del Paso 9 (Motor y Sistemas)
     * @param int $id ID del expertise
     * @param array $data Datos de motor y sistemas
     * @return bool True si se actualizó correctamente
     */
    public function updateStep9($id, $data) {
        // Convertir a JSON
        $motorSistemasJson = json_encode($data);
        
        $sql = "UPDATE expertises SET
            motor_sistemas_data = ?,
            observaciones_motor = ?,
            observaciones_interior = ?,
            current_step = 9,
            status = 'in_progress',
            updated_at = NOW()
        WHERE id = ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $motorSistemasJson,
            $data['observaciones_motor'] ?? null,
            $data['observaciones_interior'] ?? null,
            $id
        ]);
    }
    
    /**
     * Actualizar datos del Paso 10 (Fugas y Niveles)
     * @param int $id ID del expertise
     * @param array $data Datos de fugas y niveles
     * @return bool True si se actualizó correctamente
     */
    public function updateStep10($id, $data) {
        // Convertir a JSON
        $fugasNivelesJson = json_encode($data);
        
        $sql = "UPDATE expertises SET
            fugas_niveles_data = ?,
            prueba_ruta = ?,
            observaciones_fugas = ?,
            current_step = 10,
            status = 'in_progress',
            updated_at = NOW()
        WHERE id = ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            $fugasNivelesJson,
            $data['prueba_ruta'] ?? null,
            $data['observaciones_fugas'] ?? null,
            $id
        ]);
    }
    
    /**
     * Actualizar datos del paso 11 (Fijación Fotográfica)
     * @param int $id ID del expertise
     * @param array $data Array con información completa de las fotos
     * @return bool True si se actualizó correctamente
     */
    public function updateStep11($id, $data) {
        try {
            $db = $this->db->getConnection();
            
            // Iniciar transacción
            $db->beginTransaction();
            
            // 1. Eliminar fotos anteriores si existen
            $sqlDelete = "DELETE FROM expertise_photos WHERE expertise_id = ?";
            $stmtDelete = $db->prepare($sqlDelete);
            $stmtDelete->execute([$id]);
            
            // 2. Insertar nuevas fotos
            $fotos = $data['fotos'] ?? [];
            $totalFotos = count($fotos);
            
            if ($totalFotos > 0) {
                $sqlInsert = "INSERT INTO expertise_photos 
                    (expertise_id, nombre_original, nombre_guardado, ruta, extension, size, orden) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmtInsert = $db->prepare($sqlInsert);
                
                foreach ($fotos as $index => $foto) {
                    $orden = $index + 1;
                    $stmtInsert->execute([
                        $id,
                        $foto['nombre_original'],
                        $foto['nombre_guardado'],
                        $foto['ruta'],
                        $foto['extension'],
                        $foto['size'],
                        $orden
                    ]);
                }
            }
            
            // 3. Actualizar expertise con el total de fotos y estado
            $sqlUpdate = "UPDATE expertises SET
                total_fotos = ?,
                current_step = 11,
                status = 'in_progress',
                updated_at = NOW()
            WHERE id = ?";
            
            $stmtUpdate = $db->prepare($sqlUpdate);
            $stmtUpdate->execute([$totalFotos, $id]);
            
            // Confirmar transacción
            $db->commit();
            
            return true;
            
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log("Error en updateStep11: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtener inspecciones por sección
     * @param int $expertiseId ID del expertise
     * @param string $section Nombre de la sección (carroceria, vidrios, estructura)
     * @return array Array de inspecciones
     */
    public function getInspectionsBySection($expertiseId, $section) {
        $sql = "SELECT * FROM expertise_inspections 
                WHERE expertise_id = ? AND section = ?
                ORDER BY id ASC";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$expertiseId, $section]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Completar peritaje (Paso 12)
     * @param int $id ID del expertise
     * @return bool True si se completó correctamente
     */
    public function completeExpertise($id) {
        $sql = "UPDATE expertises SET
            status = 'completed',
            current_step = 12,
            updated_at = NOW()
        WHERE id = ?";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Obtener peritajes en progreso del usuario actual
     * @param int $userId ID del usuario
     * @return array Lista de peritajes en progreso
     */
    public function getInProgressByUser($userId) {
        $sql = "SELECT 
                    e.*,
                    c.first_name as cliente_nombre,
                    c.last_name as cliente_apellido,
                    c.phone as cliente_telefono,
                    vt.name as tipo_vehiculo_nombre,
                    (SELECT COUNT(*) FROM expertise_inspections WHERE expertise_id = e.id) as total_inspecciones,
                    (SELECT COUNT(*) FROM expertise_photos WHERE expertise_id = e.id) as total_fotos
                FROM expertises e
                LEFT JOIN clients c ON e.client_id = c.id
                LEFT JOIN vehicle_types vt ON e.tipo_vehiculo = vt.id
                WHERE e.user_id = ? 
                AND e.status IN ('draft', 'in_progress')
                ORDER BY e.updated_at DESC";
        
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

