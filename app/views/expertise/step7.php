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

// Obtener cantidad de amortiguadores guardados (para motos)
$cantAmortiguadoresDelanteros = $expertise['cant_amortiguadores_delanteros'] ?? 1;
$cantAmortiguadoresTraseros = $expertise['cant_amortiguadores_traseros'] ?? 1;
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
                        
                        <?php if ($vehicleType === 'moto'): ?>
                        <!-- ========== SECCIÓN PARA MOTO ========== -->
                        
                        <!-- Selector de cantidad de amortiguadores -->
                        <div class="alert alert-warning border mb-4" id="motoAmortiguadoresConfig">
                            <h6 class="alert-heading mb-3">
                                <i class="fas fa-motorcycle me-2"></i>
                                Configuración de Amortiguadores de la Moto
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="cant_amortiguadores_delanteros" class="form-label fw-bold">
                                        ¿Cuántos amortiguadores delanteros tiene?
                                    </label>
                                    <select id="cant_amortiguadores_delanteros" 
                                            name="cant_amortiguadores_delanteros" 
                                            class="form-select form-select-lg">
                                        <option value="1" <?= $cantAmortiguadoresDelanteros == 1 ? 'selected' : '' ?>>1 Amortiguador</option>
                                        <option value="2" <?= $cantAmortiguadoresDelanteros == 2 ? 'selected' : '' ?>>2 Amortiguadores</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="cant_amortiguadores_traseros" class="form-label fw-bold">
                                        ¿Cuántos amortiguadores traseros tiene?
                                    </label>
                                    <select id="cant_amortiguadores_traseros" 
                                            name="cant_amortiguadores_traseros" 
                                            class="form-select form-select-lg">
                                        <option value="1" <?= $cantAmortiguadoresTraseros == 1 ? 'selected' : '' ?>>1 Amortiguador</option>
                                        <option value="2" <?= $cantAmortiguadoresTraseros == 2 ? 'selected' : '' ?>>2 Amortiguadores</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Ilustración dinámica para Moto -->
                        <div class="alert alert-light border mb-4">
                            <div class="text-center">
                                <!-- Amortiguadores Delanteros -->
                                <div class="row justify-content-center mb-2" id="ilustracionDelanteros">
                                    <div class="col-5 col-md-4 amort-delantero-izq" style="display: none;">
                                        <div class="p-2 bg-white rounded shadow-sm">
                                            <i class="fas fa-arrows-alt-v fa-2x text-info mb-1"></i>
                                            <p class="mb-0 small fw-bold">Delantero Izq.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 col-md-4 amort-delantero-der">
                                        <div class="p-2 bg-white rounded shadow-sm">
                                            <i class="fas fa-arrows-alt-v fa-2x text-info mb-1"></i>
                                            <p class="mb-0 small fw-bold amort-del-label">Delantero</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="my-2">
                                    <i class="fas fa-motorcycle fa-3x text-warning"></i>
                                </div>
                                
                                <!-- Amortiguadores Traseros -->
                                <div class="row justify-content-center mt-2" id="ilustracionTraseros">
                                    <div class="col-5 col-md-4 amort-trasero-izq" style="display: none;">
                                        <div class="p-2 bg-white rounded shadow-sm">
                                            <i class="fas fa-arrows-alt-v fa-2x text-info mb-1"></i>
                                            <p class="mb-0 small fw-bold">Trasero Izq.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 col-md-4 amort-trasero-der">
                                        <div class="p-2 bg-white rounded shadow-sm">
                                            <i class="fas fa-arrows-alt-v fa-2x text-info mb-1"></i>
                                            <p class="mb-0 small fw-bold amort-tras-label">Trasero</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Campos de Porcentaje para MOTO -->
                        <div class="row" id="camposMoto">
                            <!-- Delantero Izquierdo (solo si tiene 2) -->
                            <div class="col-md-6 mb-3 campo-delantero-izq" style="display: none;">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                    Delantero Izquierdo (%)
                                </label>
                                <input 
                                    type="number" 
                                    id="amortiguador_anterior_izquierdo"
                                    class="form-control form-control-lg" 
                                    name="amortiguador_anterior_izquierdo" 
                                    min="0" 
                                    max="100"
                                    placeholder="0-100"
                                    value="<?= htmlspecialchars($expertise['amortiguador_anterior_izquierdo'] ?? '') ?>">
                                <small class="text-muted">Porcentaje de vida útil</small>
                            </div>
                            
                            <!-- Delantero Derecho (siempre visible, cambia etiqueta) -->
                            <div class="col-md-6 mb-3 campo-delantero-der">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                    <span class="label-delantero">Delantero (%)</span>
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
                            
                            <!-- Trasero Izquierdo (solo si tiene 2) -->
                            <div class="col-md-6 mb-3 campo-trasero-izq" style="display: none;">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                    Trasero Izquierdo (%)
                                </label>
                                <input 
                                    type="number" 
                                    id="amortiguador_posterior_izquierdo"
                                    class="form-control form-control-lg" 
                                    name="amortiguador_posterior_izquierdo" 
                                    min="0" 
                                    max="100"
                                    placeholder="0-100"
                                    value="<?= htmlspecialchars($expertise['amortiguador_posterior_izquierdo'] ?? '') ?>">
                                <small class="text-muted">Porcentaje de vida útil</small>
                            </div>
                            
                            <!-- Trasero Derecho (siempre visible, cambia etiqueta) -->
                            <div class="col-md-6 mb-3 campo-trasero-der">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                    <span class="label-trasero">Trasero (%)</span>
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
                        </div>
                        
                        <?php else: ?>
                        <!-- ========== SECCIÓN PARA CARRO ========== -->
                        
                        <!-- Ilustración para CARRO (4 amortiguadores) -->
                        <div class="alert alert-light border mb-4">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="p-3 bg-white rounded shadow-sm mb-2">
                                        <i class="fas fa-arrows-alt-v fa-3x text-info mb-2"></i>
                                        <p class="mb-0 fw-bold">Anterior Izquierdo</p>
                                    </div>
                                </div>
                                <div class="col-6">
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
                                <div class="col-6">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <i class="fas fa-arrows-alt-v fa-3x text-info mb-2"></i>
                                        <p class="mb-0 fw-bold">Posterior Izquierdo</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-white rounded shadow-sm">
                                        <i class="fas fa-arrows-alt-v fa-3x text-info mb-2"></i>
                                        <p class="mb-0 fw-bold">Posterior Derecho</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Campos de Porcentaje para CARRO -->
                        <div class="row">
                            <div class="col-md-3 col-6 mb-3">
                                <label for="amortiguador_anterior_izquierdo" class="form-label fw-bold">
                                    <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                    Anterior Izq. (%)
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
                            </div>
                            
                            <div class="col-md-3 col-6 mb-3">
                                <label for="amortiguador_anterior_derecho" class="form-label fw-bold">
                                    <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                    Anterior Der. (%)
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
                            </div>
                            
                            <div class="col-md-3 col-6 mb-3">
                                <label for="amortiguador_posterior_izquierdo" class="form-label fw-bold">
                                    <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                    Posterior Izq. (%)
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
                            </div>
                            
                            <div class="col-md-3 col-6 mb-3">
                                <label for="amortiguador_posterior_derecho" class="form-label fw-bold">
                                    <i class="fas fa-arrows-alt-v text-info me-2"></i>
                                    Posterior Der. (%)
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
                            </div>
                        </div>
                        <?php endif; ?>
                        
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
                            <div class="col-12">
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

<?php if ($vehicleType === 'moto'): ?>
<!-- Script para manejar campos dinámicos de moto -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectDelanteros = document.getElementById('cant_amortiguadores_delanteros');
    const selectTraseros = document.getElementById('cant_amortiguadores_traseros');
    
    function actualizarCamposMoto() {
        const cantDelanteros = parseInt(selectDelanteros.value);
        const cantTraseros = parseInt(selectTraseros.value);
        
        // Campos e ilustraciones delanteros
        const campoDelanteroIzq = document.querySelector('.campo-delantero-izq');
        const ilustracionDelanteroIzq = document.querySelector('.amort-delantero-izq');
        const labelDelantero = document.querySelector('.label-delantero');
        const labelIlustracionDel = document.querySelector('.amort-del-label');
        const inputDelanteroIzq = document.getElementById('amortiguador_anterior_izquierdo');
        
        if (cantDelanteros === 2) {
            campoDelanteroIzq.style.display = 'block';
            ilustracionDelanteroIzq.style.display = 'block';
            labelDelantero.textContent = 'Delantero Derecho (%)';
            labelIlustracionDel.textContent = 'Delantero Der.';
            inputDelanteroIzq.required = true;
        } else {
            campoDelanteroIzq.style.display = 'none';
            ilustracionDelanteroIzq.style.display = 'none';
            labelDelantero.textContent = 'Delantero (%)';
            labelIlustracionDel.textContent = 'Delantero';
            inputDelanteroIzq.required = false;
            inputDelanteroIzq.value = '0';
        }
        
        // Campos e ilustraciones traseros
        const campoTraseroIzq = document.querySelector('.campo-trasero-izq');
        const ilustracionTraseroIzq = document.querySelector('.amort-trasero-izq');
        const labelTrasero = document.querySelector('.label-trasero');
        const labelIlustracionTras = document.querySelector('.amort-tras-label');
        const inputTraseroIzq = document.getElementById('amortiguador_posterior_izquierdo');
        
        if (cantTraseros === 2) {
            campoTraseroIzq.style.display = 'block';
            ilustracionTraseroIzq.style.display = 'block';
            labelTrasero.textContent = 'Trasero Derecho (%)';
            labelIlustracionTras.textContent = 'Trasero Der.';
            inputTraseroIzq.required = true;
        } else {
            campoTraseroIzq.style.display = 'none';
            ilustracionTraseroIzq.style.display = 'none';
            labelTrasero.textContent = 'Trasero (%)';
            labelIlustracionTras.textContent = 'Trasero';
            inputTraseroIzq.required = false;
            inputTraseroIzq.value = '0';
        }
    }
    
    // Eventos de cambio
    selectDelanteros.addEventListener('change', actualizarCamposMoto);
    selectTraseros.addEventListener('change', actualizarCamposMoto);
    
    // Ejecutar al cargar para aplicar valores guardados
    actualizarCamposMoto();
});
</script>
<?php endif; ?>

<!-- Script para validación de amortiguadores -->
<script src="<?= ASSETS_URL ?>js/expertise-step7.js"></script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
