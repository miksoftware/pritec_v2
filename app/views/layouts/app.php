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
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        
        .auth-container {
            min-height: 100vh;
            height: 100vh;
            width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .auth-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 600px;
        }
        
        .auth-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .auth-body {
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 10px;
            border: 1px solid #e0e6ed;
            padding: 12px 16px;
            font-size: 14px;
        }
        
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }
        
        .navbar {
            background: white !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .main-content {
            padding: 2rem 0;
        }
        
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            border: none;
        }
        
        .loading {
            display: none;
        }
        
        .loading.show {
            display: inline-block;
        }
    </style>
</head>
<body>
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <!-- Navbar para usuarios autenticados -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="<?= APP_URL ?>dashboard">
                    <i class="fas fa-clipboard-check me-2"></i>
                    <?= APP_NAME ?>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= APP_URL ?>dashboard">
                                <i class="fas fa-tachometer-alt me-1"></i>
                                Dashboard
                            </a>
                        </li>
                    </ul>
                    
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>
                                <?= $_SESSION['full_name'] ?? 'Usuario' ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog me-2"></i>Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" onclick="logout()"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Contenido principal -->
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <main class="main-content">
            <?php echo $content ?? ''; ?>
        </main>
    <?php else: ?>
        <!-- Para páginas de autenticación, sin contenedor principal -->
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
    
    <script>
        // Función global para cerrar sesión
        function logout() {
            Swal.fire({
                title: '¿Cerrar sesión?',
                text: '¿Estás seguro de que quieres cerrar la sesión?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cerrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= APP_URL ?>logout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Sesión cerrada',
                                text: data.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = '<?= APP_URL ?>' + data.redirect;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Error al cerrar sesión', 'error');
                    });
                }
            });
        }
        
        // Configuración global de SweetAlert2
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        
        // Función para mostrar notificaciones
        function showNotification(type, message) {
            Toast.fire({
                icon: type,
                title: message
            });
        }
    </script>
</body>
</html>
