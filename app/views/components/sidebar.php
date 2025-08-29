<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="sidebar-header">
        <h3>
            <span class="sidebar-text">Pritec v2.0</span>
        </h3>
        <p class="text-muted mb-0">
            <small class="sidebar-text">Sistema de Peritajes</small>
        </p>
    </div>
    
    <nav class="sidebar-nav">
        <div class="sidebar-nav-item">
            <a href="<?= APP_URL ?>dashboard" class="sidebar-nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </div>
        
        <div class="sidebar-nav-item">
            <a href="<?= APP_URL ?>users" class="sidebar-nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'users') !== false) ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span class="sidebar-text">Usuarios</span>
            </a>
        </div>
        
        <div class="sidebar-nav-item">
            <a href="<?= APP_URL ?>vehicle-types" class="sidebar-nav-link <?= (strpos($_SERVER['REQUEST_URI'], 'vehicle-types') !== false) ? 'active' : '' ?>">
                <i class="fas fa-car"></i>
                <span class="sidebar-text">Tipos de Vehículos</span>
            </a>
        </div>
        
        <div class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link" onclick="showComingSoon('Peritajes')">
                <i class="fas fa-clipboard-check"></i>
                <span class="sidebar-text">Peritajes</span>
            </a>
        </div>
        
        <div class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link" onclick="showComingSoon('Clientes')">
                <i class="fas fa-users"></i>
                <span class="sidebar-text">Clientes</span>
            </a>
        </div>
        
        <div class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link" onclick="showComingSoon('Reportes')">
                <i class="fas fa-file-alt"></i>
                <span class="sidebar-text">Reportes</span>
            </a>
        </div>
        
        <div class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link" onclick="showComingSoon('Citas')">
                <i class="fas fa-calendar-alt"></i>
                <span class="sidebar-text">Citas</span>
            </a>
        </div>
        
        <div class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link" onclick="showComingSoon('Estadísticas')">
                <i class="fas fa-chart-bar"></i>
                <span class="sidebar-text">Estadísticas</span>
            </a>
        </div>
        
        <div class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link" onclick="showComingSoon('Configuración')">
                <i class="fas fa-cog"></i>
                <span class="sidebar-text">Configuración</span>
            </a>
        </div>
    </nav>
    
    <div class="sidebar-user">
        <div class="sidebar-user-info">
            <div class="sidebar-user-avatar">
                <?= strtoupper(substr($_SESSION['full_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div class="sidebar-user-details sidebar-text">
                <h6><?= $_SESSION['full_name'] ?? 'Usuario' ?></h6>
                <small><?= $_SESSION['email'] ?? '' ?></small>
            </div>
        </div>
    </div>
</aside>
