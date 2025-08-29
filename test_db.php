<?php
require_once 'app/config/Database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    
    // Verificar tipos de vehículos
    $stmt = $db->query('SELECT * FROM vehicle_types LIMIT 5');
    $vehicleTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Tipos de vehículos existentes: " . count($vehicleTypes) . "\n";
    foreach($vehicleTypes as $row) {
        echo "ID: " . $row['id'] . " - " . $row['name'] . " (" . $row['type'] . ")\n";
        
        // Verificar secciones para este tipo de vehículo
        $sectionStmt = $db->prepare('SELECT * FROM vehicle_sections WHERE vehicle_type_id = ?');
        $sectionStmt->execute([$row['id']]);
        $sections = $sectionStmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "  Secciones: " . count($sections) . "\n";
        foreach($sections as $section) {
            echo "    - " . $section['section_name'] . " (ID: " . $section['id'] . ")\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
