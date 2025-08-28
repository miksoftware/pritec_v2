<?php

// Incluir configuración
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// Incluir núcleo del sistema
require_once __DIR__ . '/app/core/Router.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/core/Model.php';

// Incluir modelos
require_once __DIR__ . '/app/models/User.php';

// Incluir controladores
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/DashboardController.php';
require_once __DIR__ . '/app/controllers/UserController.php';

// Crear instancia del router
$router = new Router();

// Definir rutas
$router->get('/', function() {
    // Redirigir al login si no está autenticado
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . APP_URL . 'login');
        exit;
    }
    // Si está autenticado, ir al dashboard
    header('Location: ' . APP_URL . 'dashboard');
    exit;
});

// Rutas de autenticación
$router->get('/login', 'AuthController@login');
$router->post('/login/process', 'AuthController@processLogin');
$router->get('/register', 'AuthController@register');
$router->post('/register/process', 'AuthController@processRegister');
$router->post('/logout', 'AuthController@logout');
$router->get('/auth/check', 'AuthController@checkAuth');

// Rutas del dashboard
$router->get('/dashboard', 'DashboardController@index');

// Rutas CRUD de usuarios
$router->get('/users', 'UserController@index');
$router->get('/users/create', 'UserController@create');
$router->post('/users/store', 'UserController@store');
$router->get('/users/edit/{id}', 'UserController@edit');
$router->post('/users/update/{id}', 'UserController@update');
$router->post('/users/delete/{id}', 'UserController@delete');
$router->post('/users/toggle-status/{id}', 'UserController@toggleStatus');

// Procesar la ruta actual
$router->dispatch();
