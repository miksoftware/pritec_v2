<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/clients.css" rel="stylesheet">

<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-user-plus me-2"></i>
                Nuevo Cliente
            </h1>
            <p class="text-muted mb-0">Completa la información del nuevo cliente</p>
        </div>
        <a href="<?= APP_URL ?>clients" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Volver a Clientes
        </a>
    </div>
</div>

<!-- Client Form -->
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Información del Cliente
                </h6>
            </div>
            <div class="card-body">
                <form id="createClientForm" action="<?= APP_URL ?>clients/store" method="POST">
                    <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                    
                    <div class="row">
                        <!-- Nombres -->
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">
                                <i class="fas fa-user me-1"></i>
                                Nombres *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="first_name" 
                                   name="first_name" 
                                   required
                                   placeholder="Ej: Carmen Rosa"
                                   value="<?= isset($_SESSION['old']['first_name']) ? htmlspecialchars($_SESSION['old']['first_name']) : '' ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <!-- Apellidos -->
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">
                                <i class="fas fa-user me-1"></i>
                                Apellidos *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="last_name" 
                                   name="last_name" 
                                   required
                                   placeholder="Ej: González"
                                   value="<?= isset($_SESSION['old']['last_name']) ? htmlspecialchars($_SESSION['old']['last_name']) : '' ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Identificación -->
                        <div class="col-md-6 mb-3">
                            <label for="identification" class="form-label">
                                <i class="fas fa-id-card me-1"></i>
                                Identificación *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="identification" 
                                   name="identification" 
                                   required
                                   placeholder="Ej: 66778899"
                                   value="<?= isset($_SESSION['old']['identification']) ? htmlspecialchars($_SESSION['old']['identification']) : '' ?>">
                            <div class="invalid-feedback"></div>
                            <div class="form-text">Número de cédula, RUT o documento de identidad</div>
                        </div>
                        
                        <!-- Teléfono -->
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone me-1"></i>
                                Teléfono *
                            </label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="phone" 
                                   name="phone" 
                                   required
                                   placeholder="Ej: 3134455667"
                                   value="<?= isset($_SESSION['old']['phone']) ? htmlspecialchars($_SESSION['old']['phone']) : '' ?>">
                            <div class="invalid-feedback"></div>
                            <div class="form-text">Número de teléfono móvil o fijo</div>
                        </div>
                    </div>
                    
                    <!-- Dirección -->
                    <div class="mb-3">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Dirección *
                        </label>
                        <textarea class="form-control" 
                                  id="address" 
                                  name="address" 
                                  rows="2" 
                                  required
                                  placeholder="Ej: Transversal 67 #89-01"><?= isset($_SESSION['old']['address']) ? htmlspecialchars($_SESSION['old']['address']) : '' ?></textarea>
                        <div class="invalid-feedback"></div>
                        <div class="form-text">Dirección completa de residencia o trabajo</div>
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-1"></i>
                            Correo Electrónico *
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               required
                               placeholder="Ej: prueba1@correo.com"
                               value="<?= isset($_SESSION['old']['email']) ? htmlspecialchars($_SESSION['old']['email']) : '' ?>">
                        <div class="invalid-feedback"></div>
                        <div class="form-text">Email válido para comunicaciones</div>
                    </div>
                    
                    <!-- Estado -->
                    <div class="mb-4">
                        <label for="status" class="form-label">
                            <i class="fas fa-toggle-on me-1"></i>
                            Estado
                        </label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?= (isset($_SESSION['old']['status']) ? $_SESSION['old']['status'] : 'active') === 'active' ? 'selected' : '' ?>>
                                Activo
                            </option>
                            <option value="inactive" <?= (isset($_SESSION['old']['status']) ? $_SESSION['old']['status'] : '') === 'inactive' ? 'selected' : '' ?>>
                                Inactivo
                            </option>
                        </select>
                        <div class="form-text">Estado inicial del cliente</div>
                    </div>
                    
                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <a href="<?= APP_URL ?>clients" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>
                            Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createClientForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Validación en tiempo real
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearValidation);
    });
    
    // Validación específica para email
    document.getElementById('email').addEventListener('input', function() {
        validateEmail(this);
    });
    
    // Validación específica para identificación
    document.getElementById('identification').addEventListener('input', function() {
        this.value = this.value.replace(/[^\d]/g, ''); // Solo números
    });
    
    // Validación específica para teléfono
    document.getElementById('phone').addEventListener('input', function() {
        this.value = this.value.replace(/[^\d]/g, ''); // Solo números
    });
    
    // Envío del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            submitForm();
        }
    });
    
    function validateField(event) {
        const field = event.target;
        const value = field.value.trim();
        
        clearValidation(event);
        
        if (field.hasAttribute('required') && !value) {
            showFieldError(field, 'Este campo es requerido');
            return false;
        }
        
        // Validaciones específicas
        switch (field.type) {
            case 'email':
                return validateEmail(field);
            case 'tel':
                return validatePhone(field);
        }
        
        // Validaciones por nombre
        switch (field.name) {
            case 'first_name':
            case 'last_name':
                return validateName(field);
            case 'identification':
                return validateIdentification(field);
        }
        
        showFieldSuccess(field);
        return true;
    }
    
    function validateEmail(field) {
        const email = field.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailRegex.test(email)) {
            showFieldError(field, 'Ingresa un email válido');
            return false;
        }
        
        showFieldSuccess(field);
        return true;
    }
    
    function validatePhone(field) {
        const phone = field.value.trim();
        
        if (phone.length < 7) {
            showFieldError(field, 'El teléfono debe tener al menos 7 dígitos');
            return false;
        }
        
        if (phone.length > 15) {
            showFieldError(field, 'El teléfono no puede tener más de 15 dígitos');
            return false;
        }
        
        showFieldSuccess(field);
        return true;
    }
    
    function validateName(field) {
        const name = field.value.trim();
        
        if (name.length < 2) {
            showFieldError(field, 'Debe tener al menos 2 caracteres');
            return false;
        }
        
        if (!/^[a-zA-ZáéíóúñÑ\s]+$/.test(name)) {
            showFieldError(field, 'Solo se permiten letras y espacios');
            return false;
        }
        
        showFieldSuccess(field);
        return true;
    }
    
    function validateIdentification(field) {
        const identification = field.value.trim();
        
        if (identification.length < 6) {
            showFieldError(field, 'La identificación debe tener al menos 6 dígitos');
            return false;
        }
        
        showFieldSuccess(field);
        return true;
    }
    
    function validateForm() {
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField({ target: input })) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    function showFieldError(field, message) {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
        }
    }
    
    function showFieldSuccess(field) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
    }
    
    function clearValidation(event) {
        const field = event.target;
        field.classList.remove('is-valid', 'is-invalid');
        
        const feedback = field.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    }
    
    function submitForm() {
        // Deshabilitar botón
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = data.redirect || '<?= APP_URL ?>clients';
                });
            } else {
                throw new Error(data.message || 'Error al guardar el cliente');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Ocurrió un error al guardar el cliente'
            });
        })
        .finally(() => {
            // Rehabilitar botón
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Guardar Cliente';
        });
    }
    
    // Animación de entrada
    const card = document.querySelector('.card');
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'all 0.3s ease';
    
    setTimeout(() => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 100);
});
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
