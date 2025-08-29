<?php
require_once 'app/config/Database.php';

$database = new Database();
$db = $database->getConnection();

// Obtener todas las secciones
$stmt = $db->query('SELECT * FROM vehicle_sections WHERE vehicle_type_id = 1');
$sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

$section_names = [
    'carroceria' => 'CARROCERIA',
    'estructura' => 'ESTRUCTURA', 
    'chasis' => 'CHASIS'
];

$colors = [
    'carroceria' => [52, 58, 64],   // Gris oscuro
    'estructura' => [40, 167, 69],  // Verde
    'chasis' => [220, 53, 69]       // Rojo
];

foreach ($sections as $section) {
    if ($section['image_path']) continue; // Skip si ya tiene imagen
    
    $width = 400;
    $height = 400;
    
    // Crear imagen
    $image = imagecreatetruecolor($width, $height);
    
    // Colores
    $bg_color = $colors[$section['section_name']];
    $background = imagecolorallocate($image, $bg_color[0], $bg_color[1], $bg_color[2]);
    $text_color = imagecolorallocate($image, 255, 255, 255);
    $accent = imagecolorallocate($image, 255, 255, 255);
    
    // Fondo
    imagefill($image, 0, 0, $background);
    
    // Dibujar rectángulo decorativo
    imagerectangle($image, 30, 30, $width-30, $height-30, $accent);
    imagerectangle($image, 32, 32, $width-32, $height-32, $accent);
    
    // Agregar texto
    $text = $section_names[$section['section_name']];
    $font_file = 5;
    $text_width = imagefontwidth($font_file) * strlen($text);
    $text_height = imagefontheight($font_file);
    $x = ($width - $text_width) / 2;
    $y = ($height - $text_height) / 2;
    
    imagestring($image, $font_file, $x, $y, $text, $text_color);
    
    // Guardar imagen
    $filename = 'section_' . $section['id'] . '_' . time() . '.png';
    $filepath = 'public/assets/uploads/vehicle_sections/' . $filename;
    
    if (imagepng($image, $filepath)) {
        echo "Imagen creada para {$section['section_name']}: $filename\n";
        
        // Actualizar base de datos
        $update_stmt = $db->prepare('UPDATE vehicle_sections SET image_path = ? WHERE id = ?');
        if ($update_stmt->execute([$filename, $section['id']])) {
            echo "Base de datos actualizada para sección {$section['id']}\n";
        }
    }
    
    imagedestroy($image);
    sleep(1); // Para que los timestamps sean diferentes
}

echo "¡Todas las imágenes de prueba han sido creadas!\n";
?>
