<?php
// Crear una imagen de prueba para las secciones
$width = 400;
$height = 400;

// Crear imagen
$image = imagecreatetruecolor($width, $height);

// Colores
$background = imagecolorallocate($image, 52, 58, 64); // Gris oscuro
$text_color = imagecolorallocate($image, 255, 255, 255); // Blanco
$accent = imagecolorallocate($image, 0, 123, 255); // Azul

// Fondo
imagefill($image, 0, 0, $background);

// Dibujar un rectángulo decorativo
imagerectangle($image, 50, 50, $width-50, $height-50, $accent);
imagerectangle($image, 52, 52, $width-52, $height-52, $accent);

// Agregar texto
$font_file = 5; // Fuente built-in
$text = "CARROCERIA";
$text_width = imagefontwidth($font_file) * strlen($text);
$text_height = imagefontheight($font_file);
$x = ($width - $text_width) / 2;
$y = ($height - $text_height) / 2;

imagestring($image, $font_file, $x, $y, $text, $text_color);

// Guardar imagen
$output_dir = 'public/assets/uploads/vehicle_sections/';
if (!file_exists($output_dir)) {
    mkdir($output_dir, 0755, true);
}

$filename = 'section_1_' . time() . '.png';
$filepath = $output_dir . $filename;

if (imagepng($image, $filepath)) {
    echo "Imagen de prueba creada: " . $filename . "\n";
    
    // Actualizar la base de datos
    require_once 'app/config/Database.php';
    $database = new Database();
    $db = $database->getConnection();
    
    $stmt = $db->prepare('UPDATE vehicle_sections SET image_path = ? WHERE id = 1');
    if ($stmt->execute([$filename])) {
        echo "Base de datos actualizada correctamente\n";
    } else {
        echo "Error al actualizar la base de datos\n";
    }
} else {
    echo "Error al crear la imagen\n";
}

imagedestroy($image);
?>
