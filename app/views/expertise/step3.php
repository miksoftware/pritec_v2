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
    'subtitle' => 'Paso 3 de 12: Inspección Visual Externa (Carrocería)',
    'icon' => 'fas fa-clipboard-check',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
        ['text' => 'Nuevo Peritaje Completo', 'url' => null]
    ])
]);

// Renderizar indicador de progreso
renderExpertiseProgressIndicator(3);
?>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            <!-- Resumen de Pasos Anteriores -->
            <?php 
            if (isset($_SESSION['expertise_step1']) && isset($_SESSION['expertise_step2'])) {
                $step1 = $_SESSION['expertise_step1'];
                $step2 = $_SESSION['expertise_step2'];
                renderPreviousStepSummary([
                    ['label' => 'Fecha', 'value' => $step1['service_date'], 'icon' => 'fas fa-calendar', 'col' => 3],
                    ['label' => 'Servicio #', 'value' => $step1['service_number'], 'icon' => 'fas fa-hashtag', 'col' => 3],
                    ['label' => 'Placa', 'value' => $step2['placa'], 'icon' => 'fas fa-car', 'col' => 3],
                    ['label' => 'Marca', 'value' => $step2['marca'] ?? 'N/A', 'icon' => 'fas fa-tag', 'col' => 3]
                ]);
            }
            ?>
            
            <!-- Formulario del Paso 3 -->
            <form id="step3Form" method="POST" action="<?= APP_URL ?>expertise/save-step3">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white text-dark me-3">
                                <i class="fas fa-external-link-alt"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Inspección Visual Externa (Carrocería)</h5>
                                <small class="opacity-75">Evalúe el estado de cada pieza de la carrocería</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <!-- Tabla de Inspección -->
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tablaInspeccionCarroceria">
                                <thead class="table-light">
                                    <tr>
                                        <th width="40%">Descripción de Pieza</th>
                                        <th width="40%">Concepto</th>
                                        <th width="20%" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyInspeccion">
                                    <!-- Las filas se agregarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Botón Agregar -->
                        <div class="mb-4">
                            <button type="button" class="btn btn-success" id="agregarFilaBtn">
                                <i class="fas fa-plus me-2"></i>Agregar Elemento
                            </button>
                        </div>
                        
                        <!-- Observaciones Generales -->
                        <div class="row">
                            <div class="col-md-12">
                                <label for="observaciones_carroceria" class="form-label fw-bold">
                                    <i class="fas fa-comment-alt me-2"></i>
                                    Observaciones Generales
                                </label>
                                <textarea 
                                    id="observaciones_carroceria" 
                                    class="form-control" 
                                    name="observaciones_carroceria" 
                                    rows="4"
                                    placeholder="Ingrese observaciones generales sobre la inspección de la carrocería..."></textarea>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Botones de Navegación -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= APP_URL ?>expertise/step2" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Paso 2
                            </a>
                            
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Continuar al Paso 4
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
            <!-- Loading Indicator -->
            <div id="loadingPieces" class="text-center py-4" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando piezas...</span>
                </div>
                <p class="mt-2 text-muted">Cargando piezas de carrocería...</p>
            </div>
            
        </div>
    </div>
</div>

<!-- Template para nueva fila (hidden) -->
<template id="filaInspeccionTemplate">
    <tr class="fila-inspeccion">
        <td>
            <select class="form-select pieza-select" name="pieza_id[]" required>
                <option value="">-- Seleccione una pieza --</option>
            </select>
        </td>
        <td>
            <select class="form-select concepto-select" name="concepto_id[]" required>
                <option value="">-- Seleccione un concepto --</option>
            </select>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm eliminar-fila-btn">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    </tr>
</template>

<!-- Script para inspección de carrocería -->
<script>
    // Pasar datos PHP a JavaScript
    const VEHICLE_TYPE_ID = <?= isset($_SESSION['expertise_step2']['tipo_vehiculo']) ? $_SESSION['expertise_step2']['tipo_vehiculo'] : 'null' ?>;
</script>
<script src="<?= ASSETS_URL ?>js/expertise-step3.js"></script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
