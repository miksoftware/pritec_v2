<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/users.css" rel="stylesheet">

<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">
            <i class="fas fa-users me-2"></i>
            Gestión de Usuarios
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= APP_URL ?>dashboard">Inicio</a></li>
                <li class="breadcrumb-item active">Usuarios</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Content Body -->
<div class="content-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list me-2"></i>
                            Lista de Usuarios
                        </h5>
                        <a href="<?= APP_URL ?>users/create" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Nuevo Usuario
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($users)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover" id="usersTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuario</th>
                                        <th>Nombre Completo</th>
                                        <th>Email</th>
                                        <th>Estado</th>
                                        <th>Fecha Registro</th>
                                        <th>Último Acceso</th>
                                        <th width="150">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr id="user-row-<?= $user['id'] ?>">
                                            <td><?= $user['id'] ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2" style="width: 30px; height: 30px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;">
                                                        <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                                                    </div>
                                                    <strong><?= htmlspecialchars($user['username']) ?></strong>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($user['full_name']) ?></td>
                                            <td><?= htmlspecialchars($user['email']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'danger' ?>" id="status-badge-<?= $user['id'] ?>">
                                                    <?= $user['status'] === 'active' ? 'Activo' : 'Inactivo' ?>
                                                </span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                            <td>
                                                <?php if ($user['last_login']): ?>
                                                    <?= date('d/m/Y H:i', strtotime($user['last_login'])) ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Nunca</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= APP_URL ?>users/edit/<?= $user['id'] ?>" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-<?= $user['status'] === 'active' ? 'warning' : 'success' ?>" 
                                                                onclick="toggleUserStatus(<?= $user['id'] ?>, '<?= $user['status'] ?>')"
                                                                title="<?= $user['status'] === 'active' ? 'Inactivar' : 'Activar' ?>">
                                                            <i class="fas fa-<?= $user['status'] === 'active' ? 'ban' : 'check' ?>"></i>
                                                        </button>
                                                        
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                onclick="deleteUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')"
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="btn btn-sm btn-outline-secondary disabled" title="Tu cuenta">
                                                            <i class="fas fa-user"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay usuarios registrados</h5>
                            <p class="text-muted">Comienza creando el primer usuario del sistema.</p>
                            <a href="<?= APP_URL ?>users/create" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Crear Primer Usuario
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
    // Animación de entrada para las filas
    animateElements('#usersTable tbody tr', 50);
});

// Función para cambiar estado del usuario
function toggleUserStatus(userId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
    const action = newStatus === 'active' ? 'activar' : 'inactivar';
    
    Swal.fire({
        title: '¿Confirmar acción?',
        text: `¿Estás seguro de que quieres ${action} este usuario?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: newStatus === 'active' ? '#28a745' : '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Sí, ${action}`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${APP_URL}users/toggle-status/${userId}`, {
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
                    const badge = document.getElementById(`status-badge-${userId}`);
                    badge.className = `badge bg-${data.new_status === 'active' ? 'success' : 'danger'}`;
                    badge.textContent = data.new_status === 'active' ? 'Activo' : 'Inactivo';
                    
                    // Actualizar botón de toggle
                    const toggleBtn = document.querySelector(`button[onclick*="toggleUserStatus(${userId}"]`);
                    toggleBtn.className = `btn btn-sm btn-outline-${data.new_status === 'active' ? 'warning' : 'success'}`;
                    toggleBtn.innerHTML = `<i class="fas fa-${data.new_status === 'active' ? 'ban' : 'check'}"></i>`;
                    toggleBtn.title = data.new_status === 'active' ? 'Inactivar' : 'Activar';
                    toggleBtn.setAttribute('onclick', `toggleUserStatus(${userId}, '${data.new_status}')`);
                    
                    showNotification('success', data.message);
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error al cambiar el estado del usuario');
            });
        }
    });
}

// Función para eliminar usuario
function deleteUser(userId, username) {
    Swal.fire({
        title: '¿Eliminar usuario?',
        text: `¿Estás seguro de que quieres eliminar al usuario "${username}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`${APP_URL}users/delete/${userId}`, {
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
                    const row = document.getElementById(`user-row-${userId}`);
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-100%)';
                    
                    setTimeout(() => {
                        row.remove();
                        showNotification('success', data.message);
                    }, 300);
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Error al eliminar el usuario');
            });
        }
    });
}
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
