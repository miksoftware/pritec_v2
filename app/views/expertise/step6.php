<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/expertise.css" rel="stylesheet">

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}

// Configurar el header de contenido
renderContentHeader('Nuevo Peritaje Completo', [
    'subtitle' => 'Paso 6 de 12: Inspección de Llantas',
    'icon' => 'fas fa-ring',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
        ['text' => 'Nuevo Peritaje Completo', 'url' => null]
    ])
]);
?>

<?php renderExpertiseProgressIndicator(6); ?>
</div>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <!-- Resumen de Pasos Anteriores -->
            <?php 
            if (isset($_SESSION['expertise_step1']) && isset($_SESSION['expertise_step2'])) {
                $step1 = $_SESSION['expertise_step1'];
                $step2 = $_SESSION['expertise_step2'];
                $step3 = $_SESSION['expertise_step3'] ?? [];
                $step4 = $_SESSION['expertise_step4'] ?? [];
                $step5 = $_SESSION['expertise_step5'] ?? [];
                
                $total_inspecciones = 0;
                if (!empty($step3['inspecciones'])) $total_inspecciones += count($step3['inspecciones']);
                if (!empty($step4['inspecciones'])) $total_inspecciones += count($step4['inspecciones']);
                if (!empty($step5['inspecciones'])) $total_inspecciones += count($step5['inspecciones']);
                
                renderPreviousStepSummary([
                    ['label' => 'Placa', 'value' => $step2['placa'], 'icon' => 'fas fa-car', 'col' => 2],
                    ['label' => 'Marca', 'value' => $step2['marca'] ?? 'N/A', 'icon' => 'fas fa-tag', 'col' => 2],
                    ['label' => 'Modelo', 'value' => $step2['modelo'] ?? 'N/A', 'icon' => 'fas fa-calendar', 'col' => 2],
                    ['label' => 'Inspecciones', 'value' => $total_inspecciones . ' piezas', 'icon' => 'fas fa-clipboard-check', 'col' => 3],
                    ['label' => 'Progreso', 'value' => 'Paso ' . $stepNum . ' de 12', 'icon' => 'fas fa-tasks', 'col' => 3]
                ], 'alert-success');
            }
            ?>
            
            <!-- Formulario del Paso 6 -->
            <form id="step6Form" method="POST" action="<?= APP_URL ?>expertise/save-step6">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white text-dark me-3">
                                <i class="fas fa-ring"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Inspección de Llantas</h5>
                                <small class="opacity-75">Ingrese el porcentaje de vida útil de cada llanta</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <!-- Ilustración de Llantas -->
                        <div class="alert alert-light border mb-4">
                            <div class="row text-center">
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded shadow-sm mb-2">
                                        <i class="fas fa-circle fa-3x text-secondary mb-2"></i>
                                        <p class="mb-0 fw-bold">Anterior Izquierda</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded shadow-sm mb-2">
                                        <i class="fas fa-circle fa-3x text-secondary mb-2"></i>
                                        <p class="mb-0 fw-bold">Anterior Derecha</p>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center my-3">
                                <i class="fas fa-car fa-3x text-primary"></i>
                            </div>
                            <div class="row text-center">
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <i class="fas fa-circle fa-3x text-secondary mb-2"></i>
                                        <p class="mb-0 fw-bold">Posterior Izquierda</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <i class="fas fa-circle fa-3x text-secondary mb-2"></i>
                                        <p class="mb-0 fw-bold">Posterior Derecha</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Campos de Porcentaje -->
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="llanta_anterior_izquierda" class="form-label fw-bold">
                                    <i class="fas fa-circle text-secondary me-2"></i>
                                    Anterior Izquierda (%)
                                </label>
                                <input 
                                    type="number" 
                                    id="llanta_anterior_izquierda"
                                    class="form-control form-control-lg" 
                                    name="llanta_anterior_izquierda" 
                                    min="0" 
                                    max="100"
                                    placeholder="0-100"
                                    required>
                                <small class="text-muted">Porcentaje de vida útil</small>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="llanta_anterior_derecha" class="form-label fw-bold">
                                    <i class="fas fa-circle text-secondary me-2"></i>
                                    Anterior Derecha (%)
                                </label>
                                <input 
                                    type="number" 
                                    id="llanta_anterior_derecha"
                                    class="form-control form-control-lg" 
                                    name="llanta_anterior_derecha" 
                                    min="0" 
                                    max="100"
                                    placeholder="0-100"
                                    required>
                                <small class="text-muted">Porcentaje de vida útil</small>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="llanta_posterior_izquierda" class="form-label fw-bold">
                                    <i class="fas fa-circle text-secondary me-2"></i>
                                    Posterior Izquierda (%)
                                </label>
                                <input 
                                    type="number" 
                                    id="llanta_posterior_izquierda"
                                    class="form-control form-control-lg" 
                                    name="llanta_posterior_izquierda" 
                                    min="0" 
                                    max="100"
                                    placeholder="0-100"
                                    required>
                                <small class="text-muted">Porcentaje de vida útil</small>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="llanta_posterior_derecha" class="form-label fw-bold">
                                    <i class="fas fa-circle text-secondary me-2"></i>
                                    Posterior Derecha (%)
                                </label>
                                <input 
                                    type="number" 
                                    id="llanta_posterior_derecha"
                                    class="form-control form-control-lg" 
                                    name="llanta_posterior_derecha" 
                                    min="0" 
                                    max="100"
                                    placeholder="0-100"
                                    required>
                                <small class="text-muted">Porcentaje de vida útil</small>
                            </div>
                        </div>
                        
                        <!-- Indicador de Estado -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Guía de Referencia:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>80-100%:</strong> Excelente estado</li>
                                <li><strong>50-79%:</strong> Buen estado</li>
                                <li><strong>30-49%:</strong> Estado aceptable - considerar reemplazo pronto</li>
                                <li><strong>0-29%:</strong> Estado crítico - reemplazo inmediato recomendado</li>
                            </ul>
                        </div>
                        
                        <!-- Observaciones Generales -->
                        <div class="row">
                            <div class="col-md-12">
                                <label for="observaciones_llantas" class="form-label fw-bold">
                                    <i class="fas fa-comment-alt me-2"></i>
                                    Observaciones Generales
                                </label>
                                <textarea 
                                    id="observaciones_llantas" 
                                    class="form-control" 
                                    name="observaciones_llantas" 
                                    rows="4"
                                    placeholder="Ingrese observaciones sobre el estado de las llantas (marca, desgaste irregular, daños, etc.)..."></textarea>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Botones de Navegación -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= APP_URL ?>expertise/step5" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Paso 5
                            </a>
                            
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Continuar al Paso 7
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<!-- Script para validación de llantas -->
<script src="<?= ASSETS_URL ?>js/expertise-step6.js"></script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
