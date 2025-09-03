<?php ob_start(); ?>

<?php
// Asegurar que los helpers estén cargados
if (!function_exists('renderContentHeader')) {
    require_once APP_PATH . '/helpers/view_helpers.php';
}

// Configurar el header de contenido
renderContentHeader('Detalles del Cliente', [
    'subtitle' => 'Información completa',
    'icon' => 'fas fa-user',
    'breadcrumbs' => createBreadcrumbs([
        ['text' => 'Clientes', 'url' => APP_URL . 'clients'],
        ['text' => htmlspecialchars($client['first_name'] . ' ' . $client['last_name']), 'url' => null]
    ]),
    'actions' => [
        createHeaderAction('Editar Cliente', APP_URL . 'clients/edit/' . $client['id'], [
            'icon' => 'fas fa-edit',
            'class' => 'btn-primary'
        ]),
        createHeaderAction('Volver a Clientes', APP_URL . 'clients', [
            'icon' => 'fas fa-arrow-left',
            'class' => 'btn-outline-secondary'
        ])
    ]
]);
?>

<!-- Client Details -->
<div class="content-body">
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
                            <label class="form-label text-muted small">
                                <i class="fas fa-user me-1"></i>
                                Nombres
                            </label>
                            <div class="fw-bold"><?= htmlspecialchars($client['first_name']) ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">
                                <i class="fas fa-user me-1"></i>
                                Apellidos
                            </label>
                            <div class="fw-bold"><?= htmlspecialchars($client['last_name']) ?></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">
                                <i class="fas fa-id-card me-1"></i>
                                Identificación
                            </label>
                            <div class="fw-bold"><?= htmlspecialchars($client['identification']) ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">
                                <i class="fas fa-phone me-1"></i>
                                Teléfono
                            </label>
                            <div class="fw-bold">
                                <a href="tel:<?= htmlspecialchars($client['phone']) ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($client['phone']) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small">
                                <i class="fas fa-envelope me-1"></i>
                                Correo Electrónico
                            </label>
                            <div class="fw-bold">
                                <a href="mailto:<?= htmlspecialchars($client['email']) ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($client['email']) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Dirección
                            </label>
                            <div class="fw-bold"><?= htmlspecialchars($client['address']) ?></div>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($client['address']) ?>" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-primary mt-2">
                                <i class="fas fa-map me-1"></i>
                                Ver en Google Maps
                            </a>
                        </div>
                    </div>
                    
                    <!-- Estado del Cliente -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">
                                <i class="fas fa-toggle-on me-1"></i>
                                Estado
                            </label>
                            <div>
                                <?php if ($client['status'] === 'active'): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-user-check me-1"></i>
                                        Cliente Activo
                                    </span>
                                    <p class="text-muted mb-0 mt-1 small">Este cliente está disponible para operaciones</p>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-user-slash me-1"></i>
                                        Cliente Inactivo
                                    </span>
                                    <p class="text-muted mb-0 mt-1 small">Este cliente está desactivado</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">
                                <i class="fas fa-hashtag me-1"></i>
                                ID del Cliente
                            </label>
                            <div class="fw-bold">#<?= $client['id'] ?></div>
                        </div>
                    </div>
                    
                    <hr class="my-3">
                    
                    <!-- Información de Registro -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small">
                                <i class="fas fa-calendar-plus me-1"></i>
                                Fecha de Registro
                            </label>
                            <div class="fw-bold"><?= date('d/m/Y', strtotime($client['created_at'])) ?></div>
                            <small class="text-muted"><?= date('H:i:s', strtotime($client['created_at'])) ?></small>
                        </div>
                        
                        <div class="col-md-6 mb-0">
                            <label class="form-label text-muted small">
                                <i class="fas fa-edit me-1"></i>
                                Última Actualización
                            </label>
                            <div class="fw-bold"><?= date('d/m/Y', strtotime($client['updated_at'])) ?></div>
                            <small class="text-muted"><?= date('H:i:s', strtotime($client['updated_at'])) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Panel Lateral -->
        <div class="col-lg-4">
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
                        
                        <!-- Estado Toggle -->
                        <?php if ($client['status'] === 'active'): ?>
                            <button type="button" 
                                    class="btn btn-outline-warning"
                                    onclick="toggleClientStatus(<?= $client['id'] ?>, 'deactivate')">
                                <i class="fas fa-user-slash me-2"></i>
                                Desactivar Cliente
                            </button>
                        <?php else: ?>
                            <button type="button" 
                                    class="btn btn-outline-success"
                                    onclick="toggleClientStatus(<?= $client['id'] ?>, 'activate')">
                                <i class="fas fa-user-check me-2"></i>
                                Activar Cliente
                            </button>
                        <?php endif; ?>
                        
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
