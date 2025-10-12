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
    'subtitle' => 'Paso 8 de 12: Inspección de Batería',
    'icon' => 'fas fa-battery-full',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
        ['text' => 'Nuevo Peritaje Completo', 'url' => null]
    ])
]);
?>

<?php renderExpertiseProgressIndicator(8); ?>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Formulario del Paso 8 -->
            <form id="step8Form" method="POST" action="<?= APP_URL ?>expertise/save-step8">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white text-dark me-3">
                                <i class="fas fa-battery-full"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Inspección de Batería</h5>
                                <small class="opacity-75">Realice las pruebas de batería, arranque y carga</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <!-- Ilustración de Batería -->
                        <div class="alert alert-light border mb-4 text-center">
                            <i class="fas fa-car-battery fa-5x text-success mb-3"></i>
                            <h5 class="fw-bold">Sistema Eléctrico del Vehículo</h5>
                            <p class="text-muted mb-0">Evaluación completa del estado de la batería</p>
                        </div>
                        
                        <!-- Campos de Pruebas -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="prueba_bateria" class="form-label fw-bold">
                                    <i class="fas fa-battery-three-quarters text-success me-2"></i>
                                    Prueba de Batería (%)
                                </label>
                                <input 
                                    type="number" 
                                    id="prueba_bateria"
                                    class="form-control form-control-lg" 
                                    name="prueba_bateria" 
                                    min="0" 
                                    max="100"
                                    placeholder="0-100"
                                    value="<?= htmlspecialchars($expertise['prueba_bateria'] ?? '') ?>"
                                    required>
                                <small class="text-muted">Estado de la batería</small>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="prueba_arranque" class="form-label fw-bold">
                                    <i class="fas fa-power-off text-warning me-2"></i>
                                    Prueba de Arranque (%)
                                </label>
                                <input 
                                    type="number" 
                                    id="prueba_arranque"
                                    class="form-control form-control-lg" 
                                    name="prueba_arranque" 
                                    min="0" 
                                    max="100"
                                    placeholder="0-100"
                                    value="<?= htmlspecialchars($expertise['prueba_arranque'] ?? '') ?>"
                                    required>
                                <small class="text-muted">Capacidad de arranque</small>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="carga_bateria" class="form-label fw-bold">
                                    <i class="fas fa-charging-station text-info me-2"></i>
                                    Carga de Batería (%)
                                </label>
                                <input 
                                    type="number" 
                                    id="carga_bateria"
                                    class="form-control form-control-lg" 
                                    name="carga_bateria" 
                                    min="0" 
                                    max="100"
                                    placeholder="0-100"
                                    value="<?= htmlspecialchars($expertise['carga_bateria'] ?? '') ?>"
                                    required>
                                <small class="text-muted">Nivel de carga actual</small>
                            </div>
                        </div>
                        
                        <!-- Indicador de Estado -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Guía de Referencia:</strong>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <strong>Prueba de Batería:</strong>
                                    <ul class="mb-0 mt-1 small">
                                        <li><strong>90-100%:</strong> Excelente</li>
                                        <li><strong>70-89%:</strong> Bueno</li>
                                        <li><strong>50-69%:</strong> Regular</li>
                                        <li><strong>0-49%:</strong> Reemplazo recomendado</li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <strong>Prueba de Arranque:</strong>
                                    <ul class="mb-0 mt-1 small">
                                        <li><strong>80-100%:</strong> Óptimo</li>
                                        <li><strong>60-79%:</strong> Aceptable</li>
                                        <li><strong>40-59%:</strong> Bajo</li>
                                        <li><strong>0-39%:</strong> Crítico</li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <strong>Carga de Batería:</strong>
                                    <ul class="mb-0 mt-1 small">
                                        <li><strong>80-100%:</strong> Carga completa</li>
                                        <li><strong>60-79%:</strong> Carga media</li>
                                        <li><strong>40-59%:</strong> Carga baja</li>
                                        <li><strong>0-39%:</strong> Necesita recarga</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Observaciones Generales -->
                        <div class="row">
                            <div class="col-md-12">
                                <label for="observaciones_bateria" class="form-label fw-bold">
                                    <i class="fas fa-comment-alt me-2"></i>
                                    Observaciones Generales
                                </label>
                                <textarea 
                                    id="observaciones_bateria" 
                                    class="form-control" 
                                    name="observaciones_bateria" 
                                    rows="4"
                                    placeholder="Ingrese observaciones sobre el estado de la batería (edad, sulfatación, conexiones, voltaje, etc.)..."><?= htmlspecialchars($expertise['observaciones_bateria'] ?? '') ?></textarea>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Botones de Navegación -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= APP_URL ?>expertise/step7" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Paso 7
                            </a>
                            
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Continuar al Paso 9
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>

<!-- Script para validación de batería -->
<script src="<?= ASSETS_URL ?>js/expertise-step8.js"></script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
