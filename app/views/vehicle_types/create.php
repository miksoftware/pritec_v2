<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/vehicle-types.css" rel="stylesheet">

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}

// Configurar el header de contenido
renderContentHeader('Crear Tipo de Vehículo', [
    'subtitle' => 'Configura un nuevo tipo de vehículo para el sistema',
    'icon' => 'fas fa-car',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Tipos de Vehículos', 'url' => APP_URL . 'vehicle-types'],
        ['text' => 'Crear', 'url' => null]
    ]),
    'actions' => [
        createHeaderAction('Volver a Tipos', APP_URL . 'vehicle-types', [
            'icon' => 'fas fa-arrow-left',
            'class' => 'btn-outline-secondary'
        ])
    ]
]);
?>

<!-- Content Body -->
<div class="content-body">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card vehicle-type-card">
                <div class="card-header vehicle-type-header">
                    <h5 class="card-title mb-0 text-center">
                        <i class="fas fa-car me-2"></i>
                        Información del Tipo de Vehículo
                    </h5>
                </div>
                <div class="card-body">
                    <form id="createVehicleTypeForm" novalidate>
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">
                                <i class="fas fa-tags me-1"></i>
                                Tipo de Vehículo *
                            </label>
                            <select class="form-select" id="type" name="type" required onchange="updateVehicleIcon()">
                                <option value="">Seleccionar tipo...</option>
                                <option value="carro">🚗 Carro</option>
                                <option value="moto">🏍️ Moto</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-signature me-1"></i>
                                Nombre del Tipo *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   required
                                   placeholder="Ej: Sedán Compacto, Motocicleta Deportiva">
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Descripción
                            </label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3"
                                      placeholder="Descripción opcional del tipo de vehículo..."></textarea>
                            <div class="form-text">Opcional: Proporciona detalles adicionales sobre este tipo de vehículo</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on me-1"></i>
                                Estado
                            </label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" selected>Activo</option>
                                <option value="inactive">Inactivo</option>
                            </select>
                        </div>
                        
                        <!-- Preview de secciones -->
                        <div id="sectionsPreview" class="mb-4" style="display: none;">
                            <h6><i class="fas fa-eye me-2"></i>Secciones que se crearán:</h6>
                            <div id="sectionsInfo" class="alert alert-info"></div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= APP_URL ?>vehicle-types" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span class="loading d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                </span>
                                <i class="fas fa-arrow-right me-2" id="nextIcon"></i>
                                Crear y Continuar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createVehicleTypeForm');
    const submitBtn = document.getElementById('submitBtn');
    const nextIcon = document.getElementById('nextIcon');
    const loading = submitBtn.querySelector('.loading');
    
    // Animación de entrada
    animateElements('.vehicle-type-card', 0);
    
    // Validación en tiempo real
    setupRealTimeValidation();
    
    // Manejo del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm(form)) {
            showNotification('error', 'Por favor, corrige los errores en el formulario');
            return;
        }
        
        // Mostrar loading
        submitBtn.disabled = true;
        nextIcon.classList.add('d-none');
        loading.classList.remove('d-none');
        
        // Enviar formulario
        const formData = new FormData(form);
        
        fetch('<?= APP_URL ?>vehicle-types/store', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#28a745',
                    confirmButtonText: 'Continuar al Paso 2'
                }).then(() => {
                    window.location.href = '<?= APP_URL ?>' + data.redirect;
                });
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error al crear el tipo de vehículo');
        })
        .finally(() => {
            // Ocultar loading
            submitBtn.disabled = false;
            nextIcon.classList.remove('d-none');
            loading.classList.add('d-none');
        });
    });
});

// Actualizar icono y preview de secciones
function updateVehicleIcon() {
    const typeSelect = document.getElementById('type');
    const sectionsPreview = document.getElementById('sectionsPreview');
    const sectionsInfo = document.getElementById('sectionsInfo');
    
    if (typeSelect.value) {
        sectionsPreview.style.display = 'block';
        
        if (typeSelect.value === 'carro') {
            sectionsInfo.innerHTML = `
                <strong>Se crearán 3 secciones:</strong>
                <ul class="mb-0 mt-2">
                    <li><i class="fas fa-car me-2"></i>Carrocería</li>
                    <li><i class="fas fa-tools me-2"></i>Estructura</li>
                    <li><i class="fas fa-cogs me-2"></i>Chasis</li>
                </ul>
            `;
        } else {
            sectionsInfo.innerHTML = `
                <strong>Se crearán 2 secciones:</strong>
                <ul class="mb-0 mt-2">
                    <li><i class="fas fa-motorcycle me-2"></i>Estructura</li>
                    <li><i class="fas fa-cogs me-2"></i>Chasis</li>
                </ul>
            `;
        }
    } else {
        sectionsPreview.style.display = 'none';
    }
}

// Configurar validación en tiempo real
function setupRealTimeValidation() {
    const form = document.getElementById('createVehicleTypeForm');
    const inputs = form.querySelectorAll('input[required], select[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
}

// Validar campo individual
function validateField(field) {
    const value = field.value.trim();
    let isValid = true;
    let message = '';
    
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        message = 'Este campo es requerido';
    } else if (field.name === 'name' && value && value.length < 3) {
        isValid = false;
        message = 'El nombre debe tener al menos 3 caracteres';
    } else if (field.name === 'type' && value && !['carro', 'moto'].includes(value)) {
        isValid = false;
        message = 'Selecciona un tipo válido';
    }
    
    if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
        }
    }
    
    return isValid;
}
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
