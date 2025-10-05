<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/expertise.css" rel="stylesheet">

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}

// Cargar componentes de expertise
if (!function_exists('renderExpertiseProgressIndicator')) {
    require_once APP_PATH . '/helpers/expertise_components.php';
}

// Configurar el header de contenido
renderContentHeader('Nuevo Peritaje Completo', [
    'subtitle' => 'Paso 1 de 12: Información del Servicio y Cliente',
    'icon' => 'fas fa-clipboard-check',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Peritajes', 'url' => APP_URL . 'expertise'],
        ['text' => 'Nuevo Peritaje Completo', 'url' => null]
    ])
]);
?>

<?php renderExpertiseProgressIndicator(1); ?>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Formulario del Paso 1 -->
            <form id="step1Form" method="POST" action="<?= APP_URL ?>expertise/store">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                <input type="hidden" name="client_id" id="selectedClientId">
                
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white text-primary me-3">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Información del Servicio y Cliente</h5>
                                <small class="opacity-75">Complete los datos básicos del servicio de peritaje</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Fecha del Servicio -->
                            <div class="col-md-6">
                                <label for="service_date" class="form-label">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    Fecha del Servicio *
                                </label>
                                <input type="date" 
                                       class="form-control form-control-lg" 
                                       id="service_date" 
                                       name="service_date" 
                                       value="<?= date('Y-m-d') ?>"
                                       required>
                                <div class="invalid-feedback">
                                    Por favor ingrese la fecha del servicio
                                </div>
                            </div>
                            
                            <!-- Número de Servicio -->
                            <div class="col-md-6">
                                <label for="service_number" class="form-label">
                                    <i class="fas fa-hashtag text-primary me-2"></i>
                                    Número de Servicio *
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="service_number" 
                                       name="service_number" 
                                       placeholder="Ej: 2025-001"
                                       required>
                                <div class="invalid-feedback">
                                    Por favor ingrese el número de servicio
                                </div>
                            </div>
                            
                            <!-- Servicio Para (Aseguradora/Entidad) -->
                            <div class="col-md-6">
                                <label for="service_for" class="form-label">
                                    <i class="fas fa-building text-primary me-2"></i>
                                    Servicio Para (Aseguradora/Entidad) *
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="service_for" 
                                       name="service_for" 
                                       placeholder="Ej: Seguros La Fortaleza"
                                       required>
                                <div class="invalid-feedback">
                                    Por favor ingrese la aseguradora o entidad
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Nombre de la aseguradora o entidad que solicita el servicio
                                </small>
                            </div>
                            
                            <!-- Número de Convenio (Opcional) -->
                            <div class="col-md-6">
                                <label for="agreement" class="form-label">
                                    <i class="fas fa-file-contract text-primary me-2"></i>
                                    Número de Convenio 
                                    <span class="badge bg-secondary ms-2">Opcional</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="agreement" 
                                       name="agreement" 
                                       placeholder="Si aplica, ingrese el número de convenio">
                                <small class="text-muted">
                                    Este campo es opcional y puede dejarse en blanco
                                </small>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <!-- Búsqueda de Cliente -->
                        <h6 class="mb-3">
                            <i class="fas fa-user-search me-2 text-primary"></i>
                            Buscar Cliente *
                        </h6>
                        
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="clientSearchInput" 
                                           placeholder="Buscar por nombre, cédula/RUC o teléfono..."
                                           autocomplete="off">
                                    <button class="btn btn-primary" type="button" id="searchBtn">
                                        <i class="fas fa-search me-2"></i>Buscar
                                    </button>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Escriba al menos 3 caracteres para buscar
                                </small>
                            </div>
                        </div>
                        
                        <!-- Resultados de Búsqueda -->
                        <div id="searchResults" class="mt-3" style="display: none;">
                            <div class="card border-primary">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-list me-2"></i>Resultados de la Búsqueda
                                        <span id="resultsCount" class="badge bg-primary ms-2">0</span>
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div id="clientsList" class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                                        <!-- Los resultados se cargarán aquí dinámicamente -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Loading Indicator -->
                        <div id="searchLoading" class="text-center py-3" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Buscando...</span>
                            </div>
                            <p class="mt-2 mb-0 text-muted">Buscando clientes...</p>
                        </div>
                        
                        <!-- No Results Message -->
                        <div id="noResults" class="alert alert-warning mt-3" style="display: none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No se encontraron clientes con ese criterio de búsqueda.
                            <a href="<?= APP_URL ?>clients/create" class="alert-link ms-2" target="_blank">
                                <i class="fas fa-plus-circle me-1"></i>Crear Nuevo Cliente
                            </a>
                        </div>
                        
                        <!-- Cliente Seleccionado -->
                        <div id="selectedClientSection" class="mt-3" style="display: none;">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-user-check me-2"></i>
                                    Cliente Seleccionado
                                </div>
                                <div class="card-body">
                                    <div id="selectedClientInfo" class="row">
                                        <!-- La información del cliente seleccionado se mostrará aquí -->
                                    </div>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="clearSelectionBtn">
                                            <i class="fas fa-times me-2"></i>Cambiar Cliente
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información Adicional -->
                        <div class="alert alert-info mt-4 mb-0" role="alert">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-lightbulb fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading mb-2">Información importante</h6>
                                    <ul class="mb-0 ps-3">
                                        <li>Los campos marcados con (*) son obligatorios</li>
                                        <li><strong>Debe seleccionar un cliente antes de continuar</strong></li>
                                        <li>La información se guardará automáticamente al continuar al siguiente paso</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones de Navegación -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= APP_URL ?>expertise" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                            
                            <button type="submit" class="btn btn-primary btn-lg px-5" id="submitBtn" disabled>
                                Siguiente: Datos del Vehículo
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script para búsqueda de clientes -->
<script src="<?= ASSETS_URL ?>js/expertise-step1.js"></script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>