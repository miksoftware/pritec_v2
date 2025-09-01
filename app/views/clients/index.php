<?php ob_start(); ?>

<link href="<?= ASSETS_URL ?>css/clients.css" rel="stylesheet">

<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-users me-2"></i>
                Gestión de Clientes
            </h1>
            <p class="text-muted mb-0">Administra la información de todos los clientes</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= APP_URL ?>clients/export<?= !empty($search) || !empty($status) ? '?' . http_build_query(['search' => $search, 'status' => $status]) : '' ?>" 
               class="btn btn-outline-success">
                <i class="fas fa-download me-2"></i>
                Exportar CSV
            </a>
            <a href="<?= APP_URL ?>clients/create" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Nuevo Cliente
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Clientes</h5>
                        <h2 class="mb-0"><?= number_format($stats['total']) ?></h2>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Clientes Activos</h5>
                        <h2 class="mb-0"><?= number_format($stats['active']) ?></h2>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Nuevos Este Mes</h5>
                        <h2 class="mb-0"><?= number_format($stats['this_month']) ?></h2>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-calendar-plus fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card stats-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Nuevos Hoy</h5>
                        <h2 class="mb-0"><?= number_format($stats['today']) ?></h2>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-user-plus fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= APP_URL ?>clients" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?= htmlspecialchars($search) ?>" 
                           placeholder="Nombre, identificación, email...">
                </div>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Estado</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Todos los estados</option>
                    <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Activos</option>
                    <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactivos</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>
                        Filtrar
                    </button>
                    <a href="<?= APP_URL ?>clients" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>
                        Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Clients Table -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                Lista de Clientes
                <span class="badge bg-primary ms-2"><?= number_format($totalClients) ?></span>
            </h6>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (count($clients) > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Identificación</th>
                            <th>Contacto</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                        <tr>
                            <td>
                                <span class="fw-bold text-muted">#<?= $client['id'] ?></span>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($client['first_name'] . ' ' . $client['last_name']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($client['email']) ?></small>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold"><?= htmlspecialchars($client['identification']) ?></span>
                            </td>
                            <td>
                                <div>
                                    <div><i class="fas fa-phone me-1"></i> <?= htmlspecialchars($client['phone']) ?></div>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?= htmlspecialchars(strlen($client['address']) > 30 ? substr($client['address'], 0, 30) . '...' : $client['address']) ?>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <?php if ($client['status'] === 'active'): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>
                                        Activo
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times me-1"></i>
                                        Inactivo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= date('d/m/Y', strtotime($client['created_at'])) ?>
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="<?= APP_URL ?>clients/show/<?= $client['id'] ?>" 
                                       class="btn btn-outline-info" 
                                       title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= APP_URL ?>clients/edit/<?= $client['id'] ?>" 
                                       class="btn btn-outline-primary" 
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($client['status'] === 'active'): ?>
                                        <button type="button" 
                                                class="btn btn-outline-warning" 
                                                title="Desactivar"
                                                onclick="toggleClientStatus(<?= $client['id'] ?>, 'deactivate')">
                                            <i class="fas fa-user-slash"></i>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" 
                                                class="btn btn-outline-success" 
                                                title="Activar"
                                                onclick="toggleClientStatus(<?= $client['id'] ?>, 'activate')">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                    <?php endif; ?>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            title="Eliminar"
                                            onclick="deleteClient(<?= $client['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="card-footer">
                <nav aria-label="Paginación de clientes">
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= APP_URL ?>clients?page=<?= $currentPage - 1 ?><?= !empty($search) || !empty($status) ? '&' . http_build_query(['search' => $search, 'status' => $status]) : '' ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="<?= APP_URL ?>clients?page=<?= $i ?><?= !empty($search) || !empty($status) ? '&' . http_build_query(['search' => $search, 'status' => $status]) : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= APP_URL ?>clients?page=<?= $currentPage + 1 ?><?= !empty($search) || !empty($status) ? '&' . http_build_query(['search' => $search, 'status' => $status]) : '' ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                
                <div class="text-center mt-2">
                    <small class="text-muted">
                        Mostrando <?= count($clients) ?> de <?= number_format($totalClients) ?> clientes
                    </small>
                </div>
            </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay clientes registrados</h5>
                <?php if (!empty($search) || !empty($status)): ?>
                    <p class="text-muted mb-3">No se encontraron clientes con los filtros aplicados.</p>
                    <a href="<?= APP_URL ?>clients" class="btn btn-outline-primary">
                        <i class="fas fa-times me-2"></i>
                        Limpiar filtros
                    </a>
                <?php else: ?>
                    <p class="text-muted mb-3">Comienza agregando tu primer cliente.</p>
                    <a href="<?= APP_URL ?>clients/create" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Crear Primer Cliente
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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
    // Animar cards de estadísticas
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach((card, index) => {
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
    
    // Animar filas de la tabla
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        setTimeout(() => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            row.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, 50);
        }, index * 50);
    });
});
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
