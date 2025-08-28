<?php ob_start(); ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                    Dashboard
                </h1>
                <div class="text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    <?= date('d/m/Y H:i') ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Bienvenido, <?= htmlspecialchars($user['full_name']) ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>
                                    <i class="fas fa-at me-2 text-muted"></i>
                                    Usuario:
                                </strong>
                                <?= htmlspecialchars($user['username']) ?>
                            </p>
                            <p class="mb-2">
                                <strong>
                                    <i class="fas fa-envelope me-2 text-muted"></i>
                                    Email:
                                </strong>
                                <?= htmlspecialchars($user['email']) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>
                                    <i class="fas fa-calendar-plus me-2 text-muted"></i>
                                    Miembro desde:
                                </strong>
                                <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                            </p>
                            <p class="mb-2">
                                <strong>
                                    <i class="fas fa-clock me-2 text-muted"></i>
                                    Último acceso:
                                </strong>
                                <?php if ($user['last_login']): ?>
                                    <?= date('d/m/Y H:i', strtotime($user['last_login'])) ?>
                                <?php else: ?>
                                    Primera vez
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'danger' ?> fs-6">
                            <i class="fas fa-<?= $user['status'] === 'active' ? 'check-circle' : 'times-circle' ?> me-1"></i>
                            <?= $user['status'] === 'active' ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Estadísticas Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-0 text-primary">0</h4>
                            <small class="text-muted">Peritajes Activos</small>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-0 text-success">0</h4>
                            <small class="text-muted">Completados</small>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 text-warning">0</h4>
                            <small class="text-muted">Pendientes</small>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historial de Accesos
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($login_history)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>IP</th>
                                        <th>Dispositivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($login_history as $login): ?>
                                        <tr>
                                            <td>
                                                <small>
                                                    <?= date('d/m/Y H:i', strtotime($login['login_at'])) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= htmlspecialchars($login['ip_address']) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?php
                                                    $userAgent = $login['user_agent'];
                                                    if (strpos($userAgent, 'Chrome') !== false) {
                                                        echo '<i class="fab fa-chrome text-warning"></i> Chrome';
                                                    } elseif (strpos($userAgent, 'Firefox') !== false) {
                                                        echo '<i class="fab fa-firefox text-danger"></i> Firefox';
                                                    } elseif (strpos($userAgent, 'Safari') !== false) {
                                                        echo '<i class="fab fa-safari text-info"></i> Safari';
                                                    } elseif (strpos($userAgent, 'Edge') !== false) {
                                                        echo '<i class="fab fa-edge text-primary"></i> Edge';
                                                    } else {
                                                        echo '<i class="fas fa-globe text-secondary"></i> Otro';
                                                    }
                                                    ?>
                                                </small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p class="mb-0">No hay historial de accesos disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks me-2"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="showComingSoon('Nuevo Peritaje')">
                            <i class="fas fa-plus me-2"></i>
                            Nuevo Peritaje
                        </button>
                        
                        <button class="btn btn-outline-secondary" onclick="showComingSoon('Buscar Peritajes')">
                            <i class="fas fa-search me-2"></i>
                            Buscar Peritajes
                        </button>
                        
                        <button class="btn btn-outline-info" onclick="showComingSoon('Reportes')">
                            <i class="fas fa-chart-bar me-2"></i>
                            Ver Reportes
                        </button>
                        
                        <button class="btn btn-outline-warning" onclick="showComingSoon('Configuración')">
                            <i class="fas fa-cog me-2"></i>
                            Configuración
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showComingSoon(feature) {
        Swal.fire({
            title: 'Próximamente',
            text: `La funcionalidad "${feature}" estará disponible pronto.`,
            icon: 'info',
            confirmButtonColor: '#3498db'
        });
    }
    
    // Mostrar notificación de bienvenida
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('success', '¡Bienvenido al sistema!');
    });
</script>

<?php 
$content = ob_get_clean();
include APP_PATH . '/views/layouts/app.php';
?>
