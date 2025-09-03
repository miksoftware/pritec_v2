<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/users.css" rel="stylesheet">

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}

// Configurar el header de contenido
renderContentHeader('Editar Usuario', [
    'subtitle' => 'Actualiza la información de ' . htmlspecialchars($user['full_name']),
    'icon' => 'fas fa-user-edit',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Usuarios', 'url' => APP_URL . 'users'],
        ['text' => htmlspecialchars($user['full_name']), 'url' => null],
        ['text' => 'Editar', 'url' => null]
    ]),
    'actions' => [
        createHeaderAction('Volver a Usuarios', APP_URL . 'users', [
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
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Editar Información de Usuario
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div style="width: 80px; height: 80px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 2rem; margin: 0 auto;">
                            <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                        </div>
                        <h6 class="mt-2 mb-0"><?= htmlspecialchars($user['full_name']) ?></h6>
                    </div>
                    
                    <form id="editUserForm" novalidate>
                        <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    Nombre de Usuario *
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="username" 
                                       name="username" 
                                       required
                                       value="<?= htmlspecialchars($user['username']) ?>"
                                       placeholder="Ej: juan.perez">
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Email *
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       required
                                       value="<?= htmlspecialchars($user['email']) ?>"
                                       placeholder="ejemplo@correo.com">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="full_name" class="form-label">
                                <i class="fas fa-id-card me-1"></i>
                                Nombre Completo *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="full_name" 
                                   name="full_name" 
                                   required
                                   value="<?= htmlspecialchars($user['full_name']) ?>"
                                   placeholder="Juan Pérez García">
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on me-1"></i>
                                Estado del Usuario
                            </label>
                            <select class="form-select" id="status" name="status">
                                <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Activo</option>
                                <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>
                        
                        <hr>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="change_password" 
                                       onchange="togglePasswordFields()">
                                <label class="form-check-label" for="change_password">
                                    <i class="fas fa-key me-1"></i>
                                    Cambiar contraseña
                                </label>
                            </div>
                        </div>
                        
                        <div id="passwordFields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>
                                        Nueva Contraseña
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="password" 
                                               name="password" 
                                               minlength="6"
                                               placeholder="Mínimo 6 caracteres">
                                        <button class="btn btn-outline-secondary" 
                                                type="button" 
                                                onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">
                                        <i class="fas fa-lock me-1"></i>
                                        Confirmar Contraseña
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control" 
                                               id="confirm_password" 
                                               name="confirm_password" 
                                               placeholder="Repetir contraseña">
                                        <button class="btn btn-outline-secondary" 
                                                type="button" 
                                                onclick="togglePassword('confirm_password')">
                                            <i class="fas fa-eye" id="confirm_password-icon"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Información:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Fecha de registro: <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></li>
                                <?php if ($user['last_login']): ?>
                                    <li>Último acceso: <?= date('d/m/Y H:i', strtotime($user['last_login'])) ?></li>
                                <?php else: ?>
                                    <li>Último acceso: Nunca</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= APP_URL ?>users" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span class="loading d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>
                                </span>
                                <i class="fas fa-save me-2" id="saveIcon"></i>
                                Actualizar Usuario
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
    const form = document.getElementById('editUserForm');
    const submitBtn = document.getElementById('submitBtn');
    const saveIcon = document.getElementById('saveIcon');
    const loading = submitBtn.querySelector('.loading');
    
    // Animación de entrada
    animateElements('.card', 0);
    
    // Validación en tiempo real
    setupRealTimeValidation();
    
    // Manejo del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm(form)) {
            showNotification('error', 'Por favor, corrige los errores en el formulario');
            return;
        }
        
        // Verificar contraseñas si se está cambiando
        const changePassword = document.getElementById('change_password').checked;
        if (changePassword) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (!password) {
                document.getElementById('password').classList.add('is-invalid');
                document.getElementById('password').nextElementSibling.nextElementSibling.textContent = 'La contraseña es requerida';
                showNotification('error', 'Debes ingresar la nueva contraseña');
                return;
            }
            
            if (password !== confirmPassword) {
                document.getElementById('confirm_password').classList.add('is-invalid');
                document.getElementById('confirm_password').nextElementSibling.nextElementSibling.textContent = 'Las contraseñas no coinciden';
                showNotification('error', 'Las contraseñas no coinciden');
                return;
            }
        }
        
        // Mostrar loading
        submitBtn.disabled = true;
        saveIcon.classList.add('d-none');
        loading.classList.remove('d-none');
        
        // Enviar formulario
        const formData = new FormData(form);
        
        fetch(`<?= APP_URL ?>users/update/<?= $user['id'] ?>`, {
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
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    window.location.href = '<?= APP_URL ?>' + data.redirect;
                });
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Error al actualizar el usuario');
        })
        .finally(() => {
            // Ocultar loading
            submitBtn.disabled = false;
            saveIcon.classList.remove('d-none');
            loading.classList.add('d-none');
        });
    });
});

// Función para mostrar/ocultar campos de contraseña
function togglePasswordFields() {
    const checkbox = document.getElementById('change_password');
    const passwordFields = document.getElementById('passwordFields');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    if (checkbox.checked) {
        passwordFields.style.display = 'block';
        passwordInput.setAttribute('required', 'required');
        confirmPasswordInput.setAttribute('required', 'required');
    } else {
        passwordFields.style.display = 'none';
        passwordInput.removeAttribute('required');
        confirmPasswordInput.removeAttribute('required');
        passwordInput.value = '';
        confirmPasswordInput.value = '';
        passwordInput.classList.remove('is-invalid', 'is-valid');
        confirmPasswordInput.classList.remove('is-invalid', 'is-valid');
    }
}

// Función para mostrar/ocultar contraseña
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Configurar validación en tiempo real
function setupRealTimeValidation() {
    const form = document.getElementById('editUserForm');
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
    
    // Validación especial para confirmar contraseña
    document.getElementById('confirm_password').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmPassword = this.value;
        const changePassword = document.getElementById('change_password').checked;
        
        if (changePassword && confirmPassword && password !== confirmPassword) {
            this.classList.add('is-invalid');
            this.nextElementSibling.nextElementSibling.textContent = 'Las contraseñas no coinciden';
        } else if (changePassword && confirmPassword && password === confirmPassword) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            this.nextElementSibling.nextElementSibling.textContent = '';
        }
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
    } else if (field.type === 'email' && value && !isValidEmail(value)) {
        isValid = false;
        message = 'Ingresa un email válido';
    } else if (field.name === 'password' && value && value.length < 6) {
        isValid = false;
        message = 'La contraseña debe tener al menos 6 caracteres';
    } else if (field.name === 'username' && value && value.length < 3) {
        isValid = false;
        message = 'El nombre de usuario debe tener al menos 3 caracteres';
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

// Validar email
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
