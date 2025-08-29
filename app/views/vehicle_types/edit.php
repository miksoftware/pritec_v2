<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/vehicle-types.css" rel="stylesheet">

<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2"></i>
            Editar Tipo de Vehículo
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= APP_URL ?>dashboard">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?= APP_URL ?>vehicle-types">Tipos de Vehículos</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Content Body -->
<div class="content-body">
    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card vehicle-type-card">
                <div class="card-header vehicle-type-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Editar Información del Tipo de Vehículo
                    </h5>
                </div>
                <div class="card-body">
                    <form id="editVehicleTypeForm" novalidate>
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                        <input type="hidden" name="id" value="<?= $vehicleType['id'] ?>">
                        
                        <div class="mb-3">
                            <label for="type" class="form-label">
                                <i class="fas fa-tags me-1"></i>
                                Tipo de Vehículo *
                            </label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Seleccionar tipo...</option>
                                <option value="carro" <?= $vehicleType['type'] === 'carro' ? 'selected' : '' ?>>🚗 Carro</option>
                                <option value="moto" <?= $vehicleType['type'] === 'moto' ? 'selected' : '' ?>>🏍️ Moto</option>
                            </select>
                            <div class="invalid-feedback"></div>
                            <?php if (count($sections) > 0): ?>
                            <div class="form-text text-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Nota: Este tipo de vehículo ya tiene secciones configuradas. Cambiar el tipo puede afectar la configuración existente.
                            </div>
                            <?php endif; ?>
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
                                   value="<?= htmlspecialchars($vehicleType['name']) ?>"
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
                                      placeholder="Descripción opcional del tipo de vehículo..."><?= htmlspecialchars($vehicleType['description'] ?? '') ?></textarea>
                            <div class="form-text">Opcional: Proporciona detalles adicionales sobre este tipo de vehículo</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on me-1"></i>
                                Estado
                            </label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" <?= $vehicleType['status'] === 'active' ? 'selected' : '' ?>>Activo</option>
                                <option value="inactive" <?= $vehicleType['status'] === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
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
                                <i class="fas fa-save me-2" id="saveIcon"></i>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Panel de información de secciones -->
        <div class="col-md-4 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-puzzle-piece me-2"></i>
                        Secciones Configuradas
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (count($sections) > 0): ?>
                        <div class="alert alert-info">
                            <strong>Este tipo de vehículo tiene <?= count($sections) ?> sección(es) configurada(s):</strong>
                        </div>
                        
                        <div class="list-group">
                            <?php foreach ($sections as $section): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($section['name']) ?></h6>
                                    <?php if ($section['image_path']): ?>
                                    <small class="text-success">
                                        <i class="fas fa-image me-1"></i>
                                        Con imagen
                                    </small>
                                    <?php else: ?>
                                    <small class="text-muted">
                                        <i class="fas fa-image me-1"></i>
                                        Sin imagen
                                    </small>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    <?= $section['pieces_count'] ?? 0 ?> piezas
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="d-grid mt-3">
                            <a href="<?= APP_URL ?>vehicle-types/<?= $vehicleType['id'] ?>/sections" class="btn btn-outline-primary">
                                <i class="fas fa-cog me-2"></i>
                                Gestionar Secciones
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Sin secciones:</strong> Este tipo de vehículo aún no tiene secciones configuradas.
                        </div>
                        
                        <div class="d-grid">
                            <a href="<?= APP_URL ?>vehicle-types/<?= $vehicleType['id'] ?>/sections" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Configurar Secciones
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editVehicleTypeForm');
    const submitBtn = document.getElementById('submitBtn');
    const saveIcon = document.getElementById('saveIcon');
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
        saveIcon.classList.add('d-none');
        loading.classList.remove('d-none');
        
        // Enviar formulario
        const formData = new FormData(form);
        
        fetch('<?= APP_URL ?>vehicle-types/update', {
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
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    window.location.href = '<?= APP_URL ?>vehicle-types';
                });
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error al actualizar el tipo de vehículo');
        })
        .finally(() => {
            // Ocultar loading
            submitBtn.disabled = false;
            saveIcon.classList.remove('d-none');
            loading.classList.add('d-none');
        });
    });
});

// Configurar validación en tiempo real
function setupRealTimeValidation() {
    const form = document.getElementById('editVehicleTypeForm');
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
