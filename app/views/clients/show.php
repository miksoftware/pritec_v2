<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/clients.css" rel="stylesheet">

<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-user me-2"></i>
                Detalles del Cliente
            </h1>
            <p class="text-muted mb-0">Información completa de <?= htmlspecialchars($client['first_name'] . ' ' . $client['last_name']) ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= APP_URL ?>clients/edit/<?= $client['id'] ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>
                Editar Cliente
            </a>
            <a href="<?= APP_URL ?>clients" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Volver a Clientes
            </a>
        </div>
    </div>
</div>

<!-- Client Details -->
<div class="row">
    <!-- Información Principal -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Información Personal
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">
                            <i class="fas fa-user me-1"></i>
                            Nombres
                        </label>
                        <div class="fw-bold fs-5"><?= htmlspecialchars($client['first_name']) ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">
                            <i class="fas fa-user me-1"></i>
                            Apellidos
                        </label>
                        <div class="fw-bold fs-5"><?= htmlspecialchars($client['last_name']) ?></div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">
                            <i class="fas fa-id-card me-1"></i>
                            Identificación
                        </label>
                        <div class="fw-bold fs-5"><?= htmlspecialchars($client['identification']) ?></div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-muted">
                            <i class="fas fa-phone me-1"></i>
                            Teléfono
                        </label>
                        <div class="fw-bold fs-5">
                            <a href="tel:<?= htmlspecialchars($client['phone']) ?>" class="text-decoration-none">
                                <?= htmlspecialchars($client['phone']) ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted">
                            <i class="fas fa-envelope me-1"></i>
                            Correo Electrónico
                        </label>
                        <div class="fw-bold fs-5">
                            <a href="mailto:<?= htmlspecialchars($client['email']) ?>" class="text-decoration-none">
                                <?= htmlspecialchars($client['email']) ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Dirección
                        </label>
                        <div class="fw-bold fs-5"><?= htmlspecialchars($client['address']) ?></div>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($client['address']) ?>" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-primary mt-2">
                            <i class="fas fa-map me-1"></i>
                            Ver en Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Panel Lateral -->
    <div class="col-lg-4">
        <!-- Estado del Cliente -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-toggle-on me-2"></i>
                    Estado
                </h6>
            </div>
            <div class="card-body text-center">
                <?php if ($client['status'] === 'active'): ?>
                    <div class="mb-3">
                        <i class="fas fa-user-check fa-3x text-success"></i>
                    </div>
                    <h5 class="text-success mb-2">Cliente Activo</h5>
                    <p class="text-muted mb-3">Este cliente está disponible para operaciones</p>
                    <button type="button" 
                            class="btn btn-outline-warning btn-sm"
                            onclick="toggleClientStatus(<?= $client['id'] ?>, 'deactivate')">
                        <i class="fas fa-user-slash me-1"></i>
                        Desactivar Cliente
                    </button>
                <?php else: ?>
                    <div class="mb-3">
                        <i class="fas fa-user-slash fa-3x text-danger"></i>
                    </div>
                    <h5 class="text-danger mb-2">Cliente Inactivo</h5>
                    <p class="text-muted mb-3">Este cliente está desactivado</p>
                    <button type="button" 
                            class="btn btn-outline-success btn-sm"
                            onclick="toggleClientStatus(<?= $client['id'] ?>, 'activate')">
                        <i class="fas fa-user-check me-1"></i>
                        Activar Cliente
                    </button>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Información de Registro -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-calendar me-2"></i>
                    Información de Registro
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">
                        <i class="fas fa-calendar-plus me-1"></i>
                        Fecha de Registro
                    </label>
                    <div class="fw-bold"><?= date('d/m/Y', strtotime($client['created_at'])) ?></div>
                    <small class="text-muted"><?= date('H:i:s', strtotime($client['created_at'])) ?></small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">
                        <i class="fas fa-edit me-1"></i>
                        Última Actualización
                    </label>
                    <div class="fw-bold"><?= date('d/m/Y', strtotime($client['updated_at'])) ?></div>
                    <small class="text-muted"><?= date('H:i:s', strtotime($client['updated_at'])) ?></small>
                </div>
                
                <div class="mb-0">
                    <label class="form-label text-muted">
                        <i class="fas fa-hashtag me-1"></i>
                        ID del Cliente
                    </label>
                    <div class="fw-bold">#<?= $client['id'] ?></div>
                </div>
            </div>
        </div>
        
        <!-- Acciones Rápidas -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-tools me-2"></i>
                    Acciones
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= APP_URL ?>clients/edit/<?= $client['id'] ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>
                        Editar Información
                    </a>
                    
                    <a href="tel:<?= htmlspecialchars($client['phone']) ?>" class="btn btn-outline-success">
                        <i class="fas fa-phone me-2"></i>
                        Llamar Cliente
                    </a>
                    
                    <a href="mailto:<?= htmlspecialchars($client['email']) ?>" class="btn btn-outline-info">
                        <i class="fas fa-envelope me-2"></i>
                        Enviar Email
                    </a>
                    
                    <a href="https://wa.me/57<?= preg_replace('/[^\d]/', '', $client['phone']) ?>?text=Hola%20<?= urlencode($client['first_name']) ?>" 
                       target="_blank" 
                       class="btn btn-outline-success">
                        <i class="fab fa-whatsapp me-2"></i>
                        Enviar WhatsApp
                    </a>
                    
                    <hr>
                    
                    <button type="button" 
                            class="btn btn-outline-danger"
                            onclick="deleteClient(<?= $client['id'] ?>)">
                        <i class="fas fa-trash me-2"></i>
                        Eliminar Cliente
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que quieres eliminar este cliente?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> El cliente será marcado como inactivo. Podrás reactivarlo después si es necesario.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash me-2"></i>
                    Eliminar Cliente
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para alternar estado del cliente
function toggleClientStatus(clientId, action) {
    const title = action === 'activate' ? '¿Activar cliente?' : '¿Desactivar cliente?';
    const text = action === 'activate' ? 'El cliente podrá ser utilizado normalmente.' : 'El cliente será marcado como inactivo.';
    const confirmText = action === 'activate' ? 'Sí, activar' : 'Sí, desactivar';
    
    Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: action === 'activate' ? '#28a745' : '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmText,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('<?= CSRF_TOKEN_NAME ?>', '<?= $csrf_token ?>');
            
            const url = action === 'activate' 
                ? `<?= APP_URL ?>clients/activate/${clientId}`
                : `<?= APP_URL ?>clients/destroy/${clientId}`;
            
            fetch(url, {
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
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la solicitud'
                });
            });
        }
    });
}

// Función para eliminar cliente
function deleteClient(clientId) {
    Swal.fire({
        title: '¿Eliminar cliente?',
        text: 'Esta acción marcará el cliente como inactivo. Podrás reactivarlo después si es necesario.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            toggleClientStatus(clientId, 'deactivate');
        }
    });
}

// Animaciones al cargar
document.addEventListener('DOMContentLoaded', function() {
    // Animar cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
