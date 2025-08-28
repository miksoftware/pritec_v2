<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Pritec v2.0' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= ASSETS_URL ?>css/style.css" rel="stylesheet">
    <link href="<?= ASSETS_URL ?>css/sidebar.css" rel="stylesheet">
    <link href="<?= ASSETS_URL ?>css/main.css" rel="stylesheet">
</head>
<body>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <!-- Incluir Sidebar -->
        <?php include APP_PATH . '/views/components/sidebar.php'; ?>

        <!-- Main Content Wrapper -->
        <div class="main-wrapper" id="mainWrapper">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button class="btn btn-link mobile-toggle me-3" onclick="toggleMobileSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <span class="navbar-brand mb-0 h1">
                        Panel de Administración
                    </span>
                    
                    <div class="navbar-nav ms-auto">
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <div class="me-2" style="width: 30px; height: 30px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;">
                                    <?= strtoupper(substr($_SESSION['full_name'] ?? 'U', 0, 1)) ?>
                                </div>
                                <span><?= $_SESSION['full_name'] ?? 'Usuario' ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog me-2"></i>Mi Perfil</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-bell me-2"></i>Notificaciones</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="logout()"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <?php echo $content ?? ''; ?>
        </div>
    <?php else: ?>
        <!-- Para páginas de autenticación, sin sidebar -->
        <?php echo $content ?? ''; ?>
    <?php endif; ?>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.all.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= ASSETS_URL ?>js/app.js"></script>
    <script src="<?= ASSETS_URL ?>js/sidebar.js"></script>
    <script src="<?= ASSETS_URL ?>js/main.js"></script>
    
    <script>
        // Variables globales necesarias para el funcionamiento
        const APP_URL = '<?= APP_URL ?>';
        const ASSETS_URL = '<?= ASSETS_URL ?>';
    </script>
</body>
</html>
