<?php
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

$db = new Database();
$conn = $db->getConnection();

echo "<h2>Estructura de la tabla expertise_inspections</h2>";

// Ver estructura de la tabla
$sql = "DESCRIBE expertise_inspections";
$stmt = $conn->query($sql);
$columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($columns);
echo "</pre>";

// Ver algunos registros de ejemplo
echo "<h3>Registros en expertise_inspections:</h3>";
$sql = "SELECT * FROM expertise_inspections LIMIT 10";
$stmt = $conn->query($sql);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($records);
echo "</pre>";

if (empty($records)) {
    echo "<p style='color: red;'><strong>⚠️ NO HAY REGISTROS en la tabla expertise_inspections</strong></p>";
}
?>
