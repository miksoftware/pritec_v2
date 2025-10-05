<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/expertise.css" rel="stylesheet">

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}

// Configurar el header de contenido
renderContentHeader('Nuevo Peritaje Completo', [
    'subtitle' => 'Paso 5 de 12: Inspección Chasis',
    'icon' => 'fas fa-car-side',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
        ['text' => 'Nuevo Peritaje Completo', 'url' => null]
    ])
]);
?>

<?php renderExpertiseProgressIndicator(5); ?>
</div>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            <!-- Resumen de Pasos Anteriores -->
            <?php if (isset($_SESSION['expertise_step1']) && isset($_SESSION['expertise_step2'])): ?>
            <div class="alert alert-info mb-4">
                <div class="row">
                    <div class="col-md-2">
                        <strong><i class="fas fa-calendar me-2"></i>Fecha:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step1']['service_date']) ?>
                    </div>
                    <div class="col-md-2">
                        <strong><i class="fas fa-hashtag me-2"></i>Servicio #:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step1']['service_number']) ?>
                    </div>
                    <div class="col-md-2">
                        <strong><i class="fas fa-car me-2"></i>Placa:</strong>
                        <?= htmlspecialchars($_SESSION['expertise_step2']['placa']) ?>
                    </div>
                    <div class="col-md-2">
                        <strong><i class="fas fa-clipboard-check me-2"></i>Carrocería:</strong>
                        <?= isset($_SESSION['expertise_step3']['inspecciones']) ? count($_SESSION['expertise_step3']['inspecciones']) : 'N/A' ?>
                    </div>
                    <div class="col-md-2">
                        <strong><i class="fas fa-columns me-2"></i>Estructura:</strong>
                        <?= isset($_SESSION['expertise_step4']['inspecciones']) ? count($_SESSION['expertise_step4']['inspecciones']) : 'N/A' ?>
                    </div>
                    <div class="col-md-2">
                        <strong><i class="fas fa-percentage me-2"></i>Progreso:</strong>
                        50%
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Formulario del Paso 5 -->
            <form id="step5Form" method="POST" action="<?= APP_URL ?>expertise/save-step5">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white text-dark me-3">
                                <i class="fas fa-car-side"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Inspección Chasis</h5>
                                <small class="opacity-75">Evalúe el estado de cada componente del chasis del vehículo</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <!-- Tabla de Inspección -->
                        <div class="table-responsive">
                            <table class="table table-bordered" id="tablaInspeccionChasis">
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
                                <label for="observaciones_chasis" class="form-label fw-bold">
                                    <i class="fas fa-comment-alt me-2"></i>
                                    Observaciones Generales
                                </label>
                                <textarea 
                                    id="observaciones_chasis" 
                                    class="form-control" 
                                    name="observaciones_chasis" 
                                    rows="4"
                                    placeholder="Ingrese observaciones generales sobre la inspección del chasis..."></textarea>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Botones de Navegación -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= APP_URL ?>expertise/step4" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Paso 4
                            </a>
                            
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Continuar al Paso 6
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
                <p class="mt-2 text-muted">Cargando piezas del chasis...</p>
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

<!-- Script para inspección de chasis -->
<script>
    // Pasar datos PHP a JavaScript
    const VEHICLE_TYPE_ID = <?= isset($_SESSION['expertise_step2']['tipo_vehiculo']) ? $_SESSION['expertise_step2']['tipo_vehiculo'] : 'null' ?>;
    const SECTION_NAME = 'chasis';
</script>
<script src="<?= ASSETS_URL ?>js/expertise-step5.js"></script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
