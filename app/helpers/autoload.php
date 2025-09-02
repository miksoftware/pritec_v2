<?php
/**
 * Autoloader para helpers y componentes del sistema
 */

// Función para cargar helpers automáticamente
function loadHelpers() {
    $helpersPath = APP_PATH . '/helpers/view_helpers.php';
    $indexHelpersPath = APP_PATH . '/helpers/index_helpers.php';
    
    if (file_exists($helpersPath) && !function_exists('renderContentHeader')) {
        require_once $helpersPath;
    }
    
    if (file_exists($indexHelpersPath) && !function_exists('renderIndexView')) {
        require_once $indexHelpersPath;
    }
}

// Auto-cargar helpers si no están cargados
loadHelpers();

// Función de verificación para debugging
function debugHelpers() {
    $functions = [
        'renderContentHeader',
        'createBreadcrumbs', 
        'createHeaderAction',
        'renderContentCard',
        'renderIndexView',
        'renderStatsCards',
        'renderFiltersCard',
        'renderMainTable'
    ];
    
    foreach ($functions as $function) {
        echo "Function $function: " . (function_exists($function) ? 'EXISTS' : 'NOT FOUND') . "<br>";
    }
}
?>
