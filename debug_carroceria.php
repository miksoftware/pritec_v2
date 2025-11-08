<?php
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

$db = new Database();
$conn = $db->getConnection();

echo "<h2>Debug CARROCERÍA - Expertise ID: 1</h2>";

// 1. Verificar el tipo de vehículo del expertise
echo "<h3>1. Datos del Expertise:</h3>";
$sql = "SELECT id, tipo_vehiculo, placa FROM expertises WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$expertise = $stmt->fetch(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($expertise);
echo "</pre>";

if (!$expertise) {
    die("No existe el expertise con ID 1");
}

$vehicleTypeId = $expertise['tipo_vehiculo'];
echo "<p><strong>Vehicle Type ID: $vehicleTypeId</strong></p>";

// 2. Verificar que existe la sección 'carroceria' para ese tipo de vehículo
echo "<h3>2. Sección CARROCERÍA en vehicle_sections:</h3>";
$sql = "SELECT * FROM vehicle_sections WHERE vehicle_type_id = ? AND section_name = 'carroceria'";
$stmt = $conn->prepare($sql);
$stmt->execute([$vehicleTypeId]);
$section = $stmt->fetch(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($section);
echo "</pre>";

if (!$section) {
    echo "<p style='color: red;'><strong>⚠️ NO EXISTE la sección 'carroceria' para el vehicle_type_id = $vehicleTypeId</strong></p>";
    
    // Mostrar qué secciones SÍ existen
    echo "<h4>Secciones disponibles para vehicle_type_id = $vehicleTypeId:</h4>";
    $sql = "SELECT id, section_name, image_path FROM vehicle_sections WHERE vehicle_type_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$vehicleTypeId]);
    $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($sections);
    echo "</pre>";
} else {
    $sectionId = $section['id'];
    
    // 3. Ver las piezas de esa sección
    echo "<h3>3. Piezas de CARROCERÍA (vehicle_pieces):</h3>";
    $sql = "SELECT id, piece_number, piece_name FROM vehicle_pieces WHERE section_id = ? ORDER BY piece_number";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$sectionId]);
    $pieces = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($pieces);
    echo "</pre>";
    
    // 4. Ver las inspecciones registradas en expertise_inspections
    echo "<h3>4. Inspecciones registradas para Expertise ID 1:</h3>";
    $sql = "SELECT 
                ei.id,
                ei.expertise_id,
                ei.pieza_id,
                ei.concepto_id,
                vp.piece_name,
                vs.section_name
            FROM expertise_inspections ei
            INNER JOIN vehicle_pieces vp ON ei.pieza_id = vp.id
            INNER JOIN vehicle_sections vs ON vp.section_id = vs.id
            WHERE ei.expertise_id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $inspections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($inspections);
    echo "</pre>";
    
    if (empty($inspections)) {
        echo "<p style='color: orange;'><strong>⚠️ NO HAY INSPECCIONES registradas para el expertise_id = 1</strong></p>";
    }
    
    // 5. Ejecutar la query CORREGIDA del método getInspectionDataWithImage
    echo "<h3>5. Query CORREGIDA del método getInspectionDataWithImage:</h3>";
    $sqlPieces = "SELECT 
                    vp.piece_number,
                    vp.piece_name,
                    ic.name
                FROM expertise_inspections ei
                INNER JOIN vehicle_pieces vp ON ei.pieza_id = vp.id
                INNER JOIN inspection_concepts ic ON ei.concepto_id = ic.id
                INNER JOIN vehicle_sections vs ON vp.section_id = vs.id
                WHERE ei.expertise_id = ? 
                AND ei.section = ?
                ORDER BY vp.piece_number ASC";
    
    $stmt = $conn->prepare($sqlPieces);
    $stmt->execute([1, 'carroceria']);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p><strong>Parámetros: expertise_id = 1, section = 'carroceria'</strong></p>";
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    
    if (empty($result)) {
        echo "<p style='color: red;'><strong>⚠️ La query NO retorna resultados (probablemente no hay inspecciones registradas)</strong></p>";
    } else {
        echo "<p style='color: green;'><strong>✅ Se encontraron " . count($result) . " piezas inspeccionadas</strong></p>";
    }
}

// 6. Verificar conceptos disponibles
echo "<h3>6. Conceptos disponibles (inspection_concepts):</h3>";
$sql = "SELECT * FROM inspection_concepts";
$stmt = $conn->prepare($sql);
$stmt->execute();
$concepts = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($concepts);
echo "</pre>";
?>
