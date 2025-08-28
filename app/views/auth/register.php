<?php ob_start(); ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h2 class="mb-0">
                <i class="fas fa-clipboard-check me-2"></i>
                Pritec v2.0
            </h2>
            <p class="mb-0 mt-2 opacity-75">Sistema de Peritajes</p>
        </div>
        
        <div class="auth-body">
            <h4 class="text-center mb-4">Crear Cuenta</h4>
            
            <form id="registerForm">
                <input type="hidden" name="<?= CSRF_TOKEN_NAME ?>" value="<?= $csrf_token ?>">
                
                <div class="row">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="fas fa-user me-1"></i>
                                Usuario
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   placeholder="usuario123"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>
                                Correo
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="usuario@ejemplo.com"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>
                                Contraseña
                            </label>
                            <div class="position-relative">
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Mínimo 6 caracteres"
                                       required>
                                <button type="button" 
                                        class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted"
                                        onclick="togglePassword('password')"
                                        style="border: none; background: none; padding: 0 15px;">
                                    <i class="fas fa-eye" id="toggleIcon1"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">
                                <i class="fas fa-id-card me-1"></i>
                                Nombre Completo
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="full_name" 
                                   name="full_name" 
                                   placeholder="Juan Pérez González"
                                   required>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirm" class="form-label">
                                <i class="fas fa-lock me-1"></i>
                                Confirmar Contraseña
                            </label>
                            <div class="position-relative">
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirm" 
                                       name="password_confirm" 
                                       placeholder="Repite tu contraseña"
                                       required>
                                <button type="button" 
                                        class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted"
                                        onclick="togglePassword('password_confirm')"
                                        style="border: none; background: none; padding: 0 15px;">
                                    <i class="fas fa-eye" id="toggleIcon2"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">
                        <span class="loading spinner-border spinner-border-sm me-2" role="status"></span>
                        <i class="fas fa-user-plus me-2"></i>
                        Crear Cuenta
                    </button>
                </div>
                
                <div class="text-center">
                    <p class="mb-0">
                        ¿Ya tienes cuenta? 
                        <a href="<?= APP_URL ?>login" class="text-decoration-none">
                            Inicia sesión aquí
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const toggleIcon = document.getElementById(fieldId === 'password' ? 'toggleIcon1' : 'toggleIcon2');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Form submission
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const loading = submitBtn.querySelector('.loading');
        const formData = new FormData(form);
        
        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        
        // Validate password confirmation
        const password = form.querySelector('[name="password"]').value;
        const passwordConfirm = form.querySelector('[name="password_confirm"]').value;
        
        if (password !== passwordConfirm) {
            const confirmInput = form.querySelector('[name="password_confirm"]');
            confirmInput.classList.add('is-invalid');
            const feedback = confirmInput.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.textContent = 'Las contraseñas no coinciden';
            }
            return;
        }
        
        // Show loading
        submitBtn.disabled = true;
        loading.classList.add('show');
        
        fetch('<?= APP_URL ?>register/process', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: '¡Registro exitoso!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#27ae60'
                }).then(() => {
                    window.location.href = '<?= APP_URL ?>' + data.redirect;
                });
            } else {
                // Show errors
                if (data.errors) {
                    for (const [field, message] of Object.entries(data.errors)) {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = input.parentNode.querySelector('.invalid-feedback');
                            if (feedback) {
                                feedback.textContent = message;
                            }
                        }
                    }
                }
                
                Swal.fire({
                    title: 'Error',
                    text: data.message,
                    icon: 'error',
                    confirmButtonColor: '#e74c3c'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Error de conexión. Intenta de nuevo.',
                icon: 'error',
                confirmButtonColor: '#e74c3c'
            });
        })
        .finally(() => {
            submitBtn.disabled = false;
            loading.classList.remove('show');
        });
    });
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
