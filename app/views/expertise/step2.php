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
    'subtitle' => 'Paso 2 de 12: Datos del Vehículo',
    'icon' => 'fas fa-clipboard-check',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
        ['text' => 'Nuevo Peritaje Completo', 'url' => null]
    ])
]);

// Renderizar indicador de progreso
renderExpertiseProgressIndicator(2);
?>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- Formulario del Paso 2 -->
            <form id="step2Form" method="POST" action="<?= APP_URL ?>expertise/save-step2">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                <input type="hidden" name="tipo_vehiculo" id="tipo_vehiculo_input">
                
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white text-dark me-3">
                                <i class="fas fa-car"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Datos del Vehículo</h5>
                                <small class="opacity-75">Complete la información del vehículo a inspeccionar</small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-light btn-sm" id="cambiarTipoVehiculo" style="display: none;">
                            <i class="fas fa-edit me-1"></i> Cambiar Tipo
                        </button>
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <!-- Selección de Tipo de Vehículo -->
                        <div id="vehicleTypeSearchSection">
                            <h6 class="mb-3">
                                <i class="fas fa-search me-2 text-dark"></i>
                                Seleccionar Tipo de Vehículo *
                            </h6>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text">
                                            <i class="fas fa-car"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control" 
                                               id="vehicleTypeSearchInput" 
                                               placeholder="Buscar tipo de vehículo..."
                                               autocomplete="off">
                                        <button class="btn btn-dark" type="button" id="searchVehicleTypeBtn">
                                            <i class="fas fa-search me-2"></i>Buscar
                                        </button>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Escriba para buscar o haga clic en "Buscar" para ver todos
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Resultados de Búsqueda de Tipo de Vehículo -->
                            <div id="vehicleTypeResults" class="mb-4" style="display: none;">
                                <div class="card border-dark">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-list me-2"></i>Tipos de Vehículos Disponibles
                                            <span id="vehicleTypeCount" class="badge bg-dark ms-2">0</span>
                                        </h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="vehicleTypesList" class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                                            <!-- Los resultados se cargarán aquí dinámicamente -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Loading Indicator -->
                            <div id="vehicleTypeLoading" class="text-center py-3" style="display: none;">
                                <div class="spinner-border text-dark" role="status">
                                    <span class="visually-hidden">Buscando...</span>
                                </div>
                                <p class="mt-2 mb-0 text-muted">Buscando tipos de vehículos...</p>
                            </div>
                            
                            <!-- No Results Message -->
                            <div id="vehicleTypeNoResults" class="alert alert-warning" style="display: none;">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                No se encontraron tipos de vehículos.
                            </div>
                        </div>
                        
                        <!-- Tipo de Vehículo Seleccionado -->
                        <div id="selectedVehicleTypeSection" class="mb-4" style="display: none;">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Tipo de Vehículo Seleccionado
                                </div>
                                <div class="card-body">
                                    <div id="selectedVehicleTypeInfo">
                                        <!-- La información del tipo seleccionado se mostrará aquí -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Formulario de Datos del Vehículo -->
                        <div id="vehicleDataSection" style="display: none;">
                            <hr class="my-4">
                            
                            <h6 class="mb-3">
                                <i class="fas fa-edit me-2 text-dark"></i>
                                Información del Vehículo
                            </h6>
                            
                            <div class="row g-3">
                                <!-- Placa -->
                                <div class="col-md-4">
                                    <label class="form-label">Placa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="placa" id="placa" required>
                                </div>
                                
                                <!-- Clase -->
                                <div class="col-md-4">
                                    <label class="form-label">Clase</label>
                                    <input type="text" class="form-control" name="clase">
                                </div>
                                
                                <!-- Marca -->
                                <div class="col-md-4">
                                    <label class="form-label">Marca</label>
                                    <input type="text" class="form-control" name="marca">
                                </div>
                                
                                <!-- Línea -->
                                <div class="col-md-4">
                                    <label class="form-label">Línea</label>
                                    <input type="text" class="form-control" name="linea">
                                </div>
                                
                                <!-- Cilindraje -->
                                <div class="col-md-4">
                                    <label class="form-label">Cilindraje</label>
                                    <input type="text" class="form-control" name="cilindraje">
                                </div>
                                
                                <!-- Servicio -->
                                <div class="col-md-4">
                                    <label class="form-label">Servicio</label>
                                    <input type="text" class="form-control" name="servicio">
                                </div>
                                
                                <!-- Modelo -->
                                <div class="col-md-4">
                                    <label class="form-label">Modelo</label>
                                    <input type="text" class="form-control" name="modelo">
                                </div>
                                
                                <!-- Color -->
                                <div class="col-md-4">
                                    <label class="form-label">Color</label>
                                    <input type="text" class="form-control" name="color">
                                </div>
                                
                                <!-- No de Chasis -->
                                <div class="col-md-4">
                                    <label class="form-label">No de Chasis</label>
                                    <input type="text" class="form-control" name="no_chasis">
                                </div>
                                
                                <!-- No de Motor -->
                                <div class="col-md-4">
                                    <label class="form-label">No de Motor</label>
                                    <input type="text" class="form-control" name="no_motor">
                                </div>
                                
                                <!-- No de Serie -->
                                <div class="col-md-4">
                                    <label class="form-label">No de Serie</label>
                                    <input type="text" class="form-control" name="no_serie">
                                </div>
                                
                                <!-- Tipo de Carrocería -->
                                <div class="col-md-4">
                                    <label class="form-label">Tipo de Carrocería</label>
                                    <input type="text" class="form-control" name="tipo_carroceria">
                                </div>
                                
                                <!-- Organismo de Tránsito -->
                                <div class="col-md-4">
                                    <label class="form-label">Organismo de Tránsito</label>
                                    <input type="text" class="form-control" name="organismo_transito">
                                </div>
                                
                                <!-- Kilometraje -->
                                <div class="col-md-4">
                                    <label class="form-label">Kilometraje</label>
                                    <input type="number" class="form-control" name="kilometraje" id="kilometraje">
                                </div>
                                
                                <!-- Código Fasecolda -->
                                <div class="col-md-4">
                                    <label class="form-label">Código Fasecolda</label>
                                    <input type="text" class="form-control" name="codigo_fasecolda" id="codigo_fasecolda">
                                </div>
                                
                                <!-- Valor Fasecolda -->
                                <div class="col-md-4">
                                    <label class="form-label">Valor Fasecolda</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="valor_fasecolda" id="valor_fasecolda">
                                    </div>
                                </div>
                                
                                <!-- Valor Sugerido -->
                                <div class="col-md-4">
                                    <label class="form-label">Valor Sugerido</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="valor_sugerido" id="valor_sugerido">
                                    </div>
                                </div>
                                
                                <!-- Valor Accesorios -->
                                <div class="col-md-4">
                                    <label class="form-label">Valor Accesorios</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control" name="valor_accesorios" id="valor_accesorios">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <!-- Botones de Navegación -->
                    <?php renderStepNavigation(2, null, true); ?>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para búsqueda de tipos de vehículo -->
<script src="<?= ASSETS_URL ?>js/expertise-step2-new.js"></script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
