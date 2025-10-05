<?php
/**
 * Script para actualizar todos los pasos del peritaje
 * para usar los componentes reutilizables
 */

// Definir la ruta base
$basePath = __DIR__ . '/app/views/expertise/';


// Definir los pasos a actualizar (1-12)
$steps = [
    1 => ['title' => 'Información del Servicio', 'subtitle' => 'Complete los datos del servicio y seleccione el cliente'],
    2 => ['title' => 'Datos del Vehículo', 'subtitle' => 'Complete la información del vehículo a inspeccionar'],
    3 => ['title' => 'Inspección Visual Externa (Carrocería)', 'subtitle' => 'Evaluación del estado de las piezas de la carrocería'],
    4 => ['title' => 'Inspección Visual Externa (Estructura)', 'subtitle' => 'Evaluación del estado de la estructura del vehículo'],
    5 => ['title' => 'Inspección Visual Externa (Chasis)', 'subtitle' => 'Evaluación del estado del chasis y componentes'],
    6 => ['title' => 'Inspección de Llantas', 'subtitle' => 'Evaluación del porcentaje de vida útil de las llantas'],
    7 => ['title' => 'Inspección de Amortiguadores', 'subtitle' => 'Evaluación del estado de los amortiguadores'],
    8 => ['title' => 'Pruebas de Batería', 'subtitle' => 'Evaluación del estado y rendimiento de la batería'],
    9 => ['title' => 'Motor y Sistemas', 'subtitle' => 'Evaluación completa del motor y sistemas del vehículo'],
    10 => ['title' => 'Fugas y Niveles', 'subtitle' => 'Evaluación de fugas y niveles de fluidos del vehículo'],
    11 => ['title' => 'Fijación Fotográfica', 'subtitle' => 'Adjunte fotografías del vehículo para documentar su estado'],
    12 => ['title' => 'Resumen Final', 'subtitle' => 'Revise todos los datos antes de guardar el peritaje']
];

echo "Iniciando actualización de archivos...\n\n";

foreach ($steps as $stepNum => $stepInfo) {
    $fileName = $stepNum == 1 ? 'create.php' : "step{$stepNum}.php";
    $filePath = $basePath . $fileName;
    
    if (!file_exists($filePath)) {
        echo "⚠️  Archivo no encontrado: {$fileName}\n";
        continue;
    }
    
    echo "📝 Procesando: {$fileName}...\n";
    
    $content = file_get_contents($filePath);
    $original = $content;
    
    // 1. Actualizar el require de helpers
    $oldHelper = "if (!function_exists('renderContentHeader')) {\n    require_once APP_PATH . '/helpers/view_helpers.php';\n}";
    $newHelper = "if (!function_exists('renderContentHeader')) {\n    require_once APP_PATH . '/helpers/view_helpers.php';\n}\nif (!function_exists('renderExpertiseProgressIndicator')) {\n    require_once APP_PATH . '/helpers/expertise_components.php';\n}";
    
    $content = str_replace($oldHelper, $newHelper, $content);
    
    // 2. Actualizar el subtitle en renderContentHeader
    $oldSubtitle = "'subtitle' => 'Paso {$stepNum} de 10:";
    $newSubtitle = "'subtitle' => 'Paso {$stepNum} de 12:";
    $content = str_replace($oldSubtitle, $newSubtitle, $content);
    
    // 3. Reemplazar el bloque completo del indicador de progreso
    $pattern = '/<!-- Indicador de Progreso -->.*?<\/div>\s*<\/div>\s*<\/div>/s';
    $replacement = "<?php renderExpertiseProgressIndicator({$stepNum}); ?>";
    $content = preg_replace($pattern, $replacement, $content);
    
    // 4. Guardar el archivo si hubo cambios
    if ($content !== $original) {
        file_put_contents($filePath, $content);
        echo "   ✅ Actualizado correctamente\n";
    } else {
        echo "   ℹ️  Sin cambios necesarios\n";
    }
}

echo "\n🎉 Proceso completado!\n";
echo "\nArchivos actualizados para usar componentes reutilizables.\n";
echo "Ahora todos los pasos usan: renderExpertiseProgressIndicator(\$stepNum)\n";
