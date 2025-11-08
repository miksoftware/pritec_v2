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
    'subtitle' => 'Paso 7 de 12: Inspección de Amortiguadores',
    'icon' => 'fas fa-compress-alt',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
        ['text' => 'Nuevo Peritaje Completo', 'url' => null]
    ])
]);

// Detectar tipo de vehículo
$vehicleType = $expertise['tipo_vehiculo_type'] ?? 'carro';
?>

<?php renderExpertiseProgressIndicator(7, $vehicleType); ?>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Formulario del Paso 7 -->
            <form id="step7Form" method="POST" action="<?= APP_URL ?>expertise/save-step7">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white text-dark me-3">
                                <i class="fas fa-compress-alt"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Inspección de Amortiguadores</h5>
                                <small class="opacity-75">Ingrese el porcentaje de vida útil de cada amortiguador</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <!-- Ilustración de Amortiguadores -->
                        <div class="alert alert-light border mb-4">
                            <?php if ($vehicleType === 'moto'): ?>
                                <!-- Ilustración para Moto (2 amortiguadores) -->
                                <div class="row text-center">
                                    <div class="col-md-6 offset-md-3">
                                        <div class="p-3 bg-white rounded shadow-sm mb-3">
                                            <i class="fas fa-arrows-alt-v fa-4x text-info mb-2"></i>
                                            <p class="mb-0 fw-bold">Amortiguador Delantero</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center my-3">
                                    <i class="fas fa-motorcycle fa-4x text-warning"></i>
                                </div>
                                <div class="row text-center">
                                    <div class="col-md-6 offset-md-3">
                                        <div class="p-3 bg-white rounded shadow-sm">
                                            <i class="fas fa-arrows-alt-v fa-4x text-info mb-2"></i>
                                            <p class="mb-0 fw-bold">Amortiguador Trasero</p>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Ilustración para Carro (4 amortiguadores) -->
                                <div class="row text-center">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-white rounded shadow-sm mb-2">
                                            <i class="fas fa-arrows-alt-v fa-3x text-info mb-2"></i>
                                            <p class="mb-0 fw-bold">Anterior Izquierdo</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-white rounded shadow-sm mb-2">
                                            <i class="fas fa-arrows-alt-v fa-3x text-info mb-2"></i>
                                            <p class="mb-0 fw-bold">Anterior Derecho</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center my-3">
                                    <i class="fas fa-car fa-3x text-primary"></i>
                                </div>
                                <div class="row text-center">
                                    <div class="col-md-6">
                                        <div class="p-3 bg-white rounded shadow-sm">
                                            <i class="fas fa-arrows-alt-v fa-3x text-info mb-2"></i>
                                            <p class="mb-0 fw-bold">Posterior Izquierdo</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 bg-white rounded shadow-sm">
                                            <i class="fas fa-arrows-alt-v fa-3x text-info mb-2"></i>
                                            <p class="mb-0 fw-bold">Posterior Derecho</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Campos de Porcentaje -->
                        <div class="row">
                            <?php if ($vehicleType === 'moto'): ?>
                                <!-- Campos para Moto (2 amortiguadores) -->
                                <div class="col-md-6 mb-3">
                                    <label for="amortiguador_anterior_derecho" class="form-label fw-bold">
                                        <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                        Amortiguador Delantero (%)
                                    </label>
                                    <input 
                                        type="number" 
                                        id="amortiguador_anterior_derecho"
                                        class="form-control form-control-lg" 
                                        name="amortiguador_anterior_derecho" 
                                        min="0" 
                                        max="100"
                                        placeholder="0-100"
                                        value="<?= htmlspecialchars($expertise['amortiguador_anterior_derecho'] ?? '') ?>"
                                        required>
                                    <small class="text-muted">Porcentaje de vida útil del amortiguador delantero</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="amortiguador_posterior_derecho" class="form-label fw-bold">
                                        <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                        Amortiguador Trasero (%)
                                    </label>
                                    <input 
                                        type="number" 
                                        id="amortiguador_posterior_derecho"
                                        class="form-control form-control-lg" 
                                        name="amortiguador_posterior_derecho" 
                                        min="0" 
                                        max="100"
                                        placeholder="0-100"
                                        value="<?= htmlspecialchars($expertise['amortiguador_posterior_derecho'] ?? '') ?>"
                                        required>
                                    <small class="text-muted">Porcentaje de vida útil del amortiguador trasero</small>
                                </div>
                                
                                <!-- Campos ocultos para amortiguadores izquierdos (no se usan en motos) -->
                                <input type="hidden" name="amortiguador_anterior_izquierdo" value="0">
                                <input type="hidden" name="amortiguador_posterior_izquierdo" value="0">
                                
                            <?php else: ?>
                                <!-- Campos para Carro (4 amortiguadores) -->
                                <div class="col-md-3 mb-3">
                                    <label for="amortiguador_anterior_izquierdo" class="form-label fw-bold">
                                        <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                        Anterior Izquierdo (%)
                                    </label>
                                    <input 
                                        type="number" 
                                        id="amortiguador_anterior_izquierdo"
                                        class="form-control form-control-lg" 
                                        name="amortiguador_anterior_izquierdo" 
                                        min="0" 
                                        max="100"
                                        placeholder="0-100"
                                        value="<?= htmlspecialchars($expertise['amortiguador_anterior_izquierdo'] ?? '') ?>"
                                        required>
                                    <small class="text-muted">Porcentaje de vida útil</small>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="amortiguador_anterior_derecho" class="form-label fw-bold">
                                        <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                        Anterior Derecho (%)
                                    </label>
                                    <input 
                                        type="number" 
                                        id="amortiguador_anterior_derecho"
                                        class="form-control form-control-lg" 
                                        name="amortiguador_anterior_derecho" 
                                        min="0" 
                                        max="100"
                                        placeholder="0-100"
                                        value="<?= htmlspecialchars($expertise['amortiguador_anterior_derecho'] ?? '') ?>"
                                        required>
                                    <small class="text-muted">Porcentaje de vida útil</small>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="amortiguador_posterior_izquierdo" class="form-label fw-bold">
                                        <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                        Posterior Izquierdo (%)
                                    </label>
                                    <input 
                                        type="number" 
                                        id="amortiguador_posterior_izquierdo"
                                        class="form-control form-control-lg" 
                                        name="amortiguador_posterior_izquierdo" 
                                        min="0" 
                                        max="100"
                                        placeholder="0-100"
                                        value="<?= htmlspecialchars($expertise['amortiguador_posterior_izquierdo'] ?? '') ?>"
                                        required>
                                    <small class="text-muted">Porcentaje de vida útil</small>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="amortiguador_posterior_derecho" class="form-label fw-bold">
                                        <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                        Posterior Derecho (%)
                                    </label>
                                    <input 
                                        type="number" 
                                        id="amortiguador_posterior_derecho"
                                        class="form-control form-control-lg" 
                                        name="amortiguador_posterior_derecho" 
                                        min="0" 
                                        max="100"
                                        placeholder="0-100"
                                        value="<?= htmlspecialchars($expertise['amortiguador_posterior_derecho'] ?? '') ?>"
                                        required>
                                    <small class="text-muted">Porcentaje de vida útil</small>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Indicador de Estado -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Guía de Referencia:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>80-100%:</strong> Excelente estado - Funcionamiento óptimo</li>
                                <li><strong>50-79%:</strong> Buen estado - Funcionamiento adecuado</li>
                                <li><strong>30-49%:</strong> Estado regular - Considerar reemplazo pronto</li>
                                <li><strong>0-29%:</strong> Estado crítico - Reemplazo inmediato recomendado</li>
                            </ul>
                        </div>
                        
                        <!-- Observaciones Generales -->
                        <div class="row">
                            <div class="col-md-12">
                                <label for="observaciones_amortiguadores" class="form-label fw-bold">
                                    <i class="fas fa-comment-alt me-2"></i>
                                    Observaciones Generales
                                </label>
                                <textarea 
                                    id="observaciones_amortiguadores" 
                                    class="form-control" 
                                    name="observaciones_amortiguadores" 
                                    rows="4"
                                    placeholder="Ingrese observaciones sobre el estado de los amortiguadores (fugas, ruidos, desgaste, etc.)..."><?= htmlspecialchars($expertise['observaciones_amortiguadores'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Botones de Navegación -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= APP_URL ?>expertise/step6" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Paso 6
                            </a>
                            
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Continuar al Paso 8
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<!-- Script para validación de amortiguadores -->
<script src="<?= ASSETS_URL ?>js/expertise-step7.js"></script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
