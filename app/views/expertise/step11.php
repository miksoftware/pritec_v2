<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/expertise.css" rel="stylesheet">

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}
if (!function_exists('renderExpertiseProgressIndicator')) {
    require_once APP_PATH . '/helpers/expertise_components.php';
}

// Configurar el header de contenido
renderContentHeader('Nuevo Peritaje Completo', [
    'subtitle' => 'Paso 11 de 12: Fijación Fotográfica',
    'icon' => 'fas fa-camera',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
        ['text' => 'Nuevo Peritaje Completo', 'url' => null]
    ])
]);
?>

<?php renderExpertiseProgressIndicator(11); ?>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- Formulario del Paso 11 -->
            <form id="step11Form" method="POST" action="<?= APP_URL ?>expertise/save-step11" enctype="multipart/form-data">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white text-dark me-3">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Fijación Fotográfica</h5>
                                <small class="opacity-75">Adjunte fotografías del vehículo para documentar su estado</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <!-- Instrucciones -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Instrucciones:</strong> Haga clic en "Seleccionar Fotografías" para agregar múltiples fotos del vehículo. Las imágenes se mostrarán automáticamente.
                        </div>
                        
                        <!-- Input de archivo múltiple -->
                        <div class="text-center mb-4">
                            <label for="fotosInput" class="btn btn-primary btn-lg">
                                <i class="fas fa-camera me-2"></i>
                                Seleccionar Fotografías
                            </label>
                            <input type="file" id="fotosInput" name="fotos[]" multiple accept="image/*" style="display: none;">
                        </div>
                        
                        <!-- Contador de fotos -->
                        <div id="contadorFotos" class="alert alert-secondary text-center" style="display: none;">
                            <i class="fas fa-images me-2"></i>
                            <strong>Fotografías seleccionadas: <span id="numeroFotos">0</span></strong>
                        </div>
                        
                        <!-- Contenedor de previews -->
                        <div id="fotosContainer" class="row g-3 mb-4">
                            <!-- Las previews se agregarán aquí dinámicamente -->
                        </div>
                        
                    </div>
                    
                    <!-- Botones de Navegación -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= APP_URL ?>expertise/step10" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Paso 10
                            </a>
                            
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-check-double me-2"></i>
                                Finalizar y Ver Resumen
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<!-- Script para fijación fotográfica -->
<script src="<?= ASSETS_URL ?>js/expertise-step11.js"></script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
