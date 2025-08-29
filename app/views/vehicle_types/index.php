<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/vehicle-types.css" rel="stylesheet">

<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">
            <i class="fas fa-car me-2"></i>
            Tipos de Vehículos
        </h1>
        <a href="<?= APP_URL ?>vehicle-types/create" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Nuevo Tipo de Vehículo
        </a>
    </div>
</div>

<!-- Content Body -->
<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                Lista de Tipos de Vehículos
            </h5>
        </div>
        <div class="card-body">
            <?php if (empty($vehicleTypes)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-car fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay tipos de vehículos registrados</h5>
                    <p class="text-muted">Comienza creando tu primer tipo de vehículo</p>
                    <a href="<?= APP_URL ?>vehicle-types/create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Crear Primer Tipo
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Fecha de Creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vehicleTypes as $vehicleType): ?>
                                <tr id="vehicle-type-row-<?= $vehicleType['id'] ?>">
                                    <td><?= $vehicleType['id'] ?></td>
                                    <td>
                                        <span class="badge bg-<?= $vehicleType['type'] === 'carro' ? 'primary' : 'info' ?>">
                                            <i class="fas fa-<?= $vehicleType['type'] === 'carro' ? 'car' : 'motorcycle' ?> me-1"></i>
                                            <?= ucfirst($vehicleType['type']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong><?= htmlspecialchars($vehicleType['name']) ?></strong>
                                    </td>
                                    <td>
                                        <?php if ($vehicleType['description']): ?>
                                            <span title="<?= htmlspecialchars($vehicleType['description']) ?>">
                                                <?= htmlspecialchars(substr($vehicleType['description'], 0, 50)) ?>
                                                <?= strlen($vehicleType['description']) > 50 ? '...' : '' ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">Sin descripción</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span id="status-badge-<?= $vehicleType['id'] ?>" 
                                              class="badge bg-<?= $vehicleType['status'] === 'active' ? 'success' : 'danger' ?>">
                                            <?= $vehicleType['status'] === 'active' ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y H:i', strtotime($vehicleType['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= APP_URL ?>vehicle-types/<?= $vehicleType['id'] ?>/sections" 
                                               class="btn btn-sm btn-info" 
                                               title="Configurar Secciones">
                                                <i class="fas fa-cogs"></i>
                                            </a>
                                            <a href="<?= APP_URL ?>vehicle-types/edit/<?= $vehicleType['id'] ?>" 
                                               class="btn btn-sm btn-warning" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-<?= $vehicleType['status'] === 'active' ? 'secondary' : 'success' ?>" 
                                                    onclick="toggleStatus(<?= $vehicleType['id'] ?>, '<?= $vehicleType['status'] ?>')"
                                                    title="<?= $vehicleType['status'] === 'active' ? 'Desactivar' : 'Activar' ?>">
                                                <i class="fas fa-toggle-<?= $vehicleType['status'] === 'active' ? 'off' : 'on' ?>"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" 
                                                    onclick="deleteVehicleType(<?= $vehicleType['id'] ?>, '<?= htmlspecialchars($vehicleType['name']) ?>')"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animación de entrada
    animateElements('.card', 0);
});

// Función para cambiar estado
function toggleStatus(vehicleTypeId, currentStatus) {
    const action = currentStatus === 'active' ? 'desactivar' : 'activar';
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas ${action} este tipo de vehículo?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: currentStatus === 'active' ? '#dc3545' : '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Sí, ${action}`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${APP_URL}vehicle-types/toggle-status/${vehicleTypeId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    csrf_token: '<?= $csrf_token ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar badge de estado
                    const badge = document.getElementById(`status-badge-${vehicleTypeId}`);
                    badge.className = `badge bg-${data.new_status === 'active' ? 'success' : 'danger'}`;
                    badge.textContent = data.new_status === 'active' ? 'Activo' : 'Inactivo';
                    
                    // Actualizar botón
                    const button = event.target.closest('button');
                    button.className = `btn btn-sm btn-${data.new_status === 'active' ? 'secondary' : 'success'}`;
                    button.title = data.new_status === 'active' ? 'Desactivar' : 'Activar';
                    button.innerHTML = `<i class="fas fa-toggle-${data.new_status === 'active' ? 'off' : 'on'}"></i>`;
                    
                    showNotification('success', data.message);
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error al cambiar el estado');
            });
        }
    });
}

// Función para eliminar tipo de vehículo
function deleteVehicleType(vehicleTypeId, vehicleTypeName) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas eliminar el tipo de vehículo "${vehicleTypeName}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${APP_URL}vehicle-types/delete/${vehicleTypeId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    csrf_token: '<?= $csrf_token ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remover fila de la tabla con animación
                    const row = document.getElementById(`vehicle-type-row-${vehicleTypeId}`);
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-100%)';
                    
                    setTimeout(() => {
                        row.remove();
                    }, 300);
                    
                    showNotification('success', data.message);
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error al eliminar el tipo de vehículo');
            });
        }
    });
}
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
