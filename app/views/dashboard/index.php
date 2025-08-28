<?php 
ob_start(); 

// Función helper para obtener el nombre del navegador
function getBrowserName($userAgent) {
    if (strpos($userAgent, 'Chrome') !== false) {
        return 'Google Chrome';
    } elseif (strpos($userAgent, 'Firefox') !== false) {
        return 'Mozilla Firefox';
    } elseif (strpos($userAgent, 'Safari') !== false) {
        return 'Safari';
    } elseif (strpos($userAgent, 'Edge') !== false) {
        return 'Microsoft Edge';
    } elseif (strpos($userAgent, 'Opera') !== false) {
        return 'Opera';
    } else {
        return 'Navegador desconocido';
    }
}
?>

<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0">
            <i class="fas fa-tachometer-alt me-2"></i>
            Dashboard
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="<?= APP_URL ?>dashboard">Inicio</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Content Body -->
<div class="content-body">
    <!-- Estadísticas principales -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="display-6 text-primary mb-3">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h5 class="card-title">Peritajes</h5>
                    <h3 class="text-primary">0</h3>
                    <p class="card-text text-muted">Total activos</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="display-6 text-success mb-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5 class="card-title">Clientes</h5>
                    <h3 class="text-success">0</h3>
                    <p class="card-text text-muted">Registrados</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="display-6 text-warning mb-3">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h5 class="card-title">Citas</h5>
                    <h3 class="text-warning">0</h3>
                    <p class="card-text text-muted">Pendientes</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="display-6 text-info mb-3">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h5 class="card-title">Reportes</h5>
                    <h3 class="text-info">0</h3>
                    <p class="card-text text-muted">Generados</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Información del usuario -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        Mi Perfil
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div style="width: 80px; height: 80px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 2rem; margin: 0 auto;">
                            <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                        </div>
                    </div>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Usuario:</strong></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td>
                                <span class="badge bg-<?= $user['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= $user['status'] === 'active' ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Registro:</strong></td>
                            <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        </tr>
                        <?php if ($user['last_login']): ?>
                            <tr>
                                <td><strong>Último acceso:</strong></td>
                                <td><?= date('d/m/Y H:i', strtotime($user['last_login'])) ?></td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Historial de accesos -->
        <div class="col-md-6 col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historial de Accesos Recientes
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($login_history)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha y Hora</th>
                                        <th>IP</th>
                                        <th>Navegador</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($login_history as $log): ?>
                                        <tr>
                                            <td><?= date('d/m/Y H:i:s', strtotime($log['login_at'])) ?></td>
                                            <td>
                                                <code><?= htmlspecialchars($log['ip_address']) ?></code>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= getBrowserName($log['user_agent']) ?>
                                                </small>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <p>No hay historial de accesos disponible</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Acciones rápidas -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <button class="btn btn-primary w-100" onclick="showComingSoon('Nuevo Peritaje')">
                                <i class="fas fa-plus me-2"></i>
                                Nuevo Peritaje
                            </button>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <button class="btn btn-success w-100" onclick="showComingSoon('Nuevo Cliente')">
                                <i class="fas fa-user-plus me-2"></i>
                                Nuevo Cliente
                            </button>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <button class="btn btn-warning w-100" onclick="showComingSoon('Nueva Cita')">
                                <i class="fas fa-calendar-plus me-2"></i>
                                Nueva Cita
                            </button>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <button class="btn btn-info w-100" onclick="showComingSoon('Generar Reporte')">
                                <i class="fas fa-file-export me-2"></i>
                                Generar Reporte
                            </button>
                        </div>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Estas funciones estarán disponibles próximamente
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Animaciones de entrada para las tarjetas
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.6s ease';
                
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
