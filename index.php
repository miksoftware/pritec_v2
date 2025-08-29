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

// Rutas CRUD de tipos de vehículos
$router->get('/vehicle-types', 'VehicleTypeController@index');
$router->get('/vehicle-types/create', 'VehicleTypeController@create');
$router->post('/vehicle-types/store', 'VehicleTypeController@store');
$router->get('/vehicle-types/{id}/edit', 'VehicleTypeController@edit');
$router->post('/vehicle-types/update', 'VehicleTypeController@update');
$router->post('/vehicle-types/delete', 'VehicleTypeController@delete');
$router->post('/vehicle-types/toggle-status', 'VehicleTypeController@toggleStatus');

// Rutas de secciones
$router->get('/vehicle-types/{id}/sections', 'VehicleTypeController@sections');
$router->post('/vehicle-types/create-sections', 'VehicleTypeController@createSections');
$router->post('/vehicle-types/upload-section-image', 'VehicleTypeController@uploadSectionImage');
$router->get('/vehicle-types/section/{id}', 'VehicleTypeController@section');

// Rutas de piezas
$router->get('/vehicle-types/section/{id}/pieces', 'VehicleTypeController@pieces');
$router->post('/vehicle-types/add-piece', 'VehicleTypeController@addPiece');
$router->post('/vehicle-types/update-piece', 'VehicleTypeController@updatePiece');
$router->post('/vehicle-types/update-piece-position', 'VehicleTypeController@updatePiecePosition');
$router->post('/vehicle-types/delete-piece', 'VehicleTypeController@deletePiece');
$router->post('/vehicle-types/clear-pieces', 'VehicleTypeController@clearPieces');

// Procesar la ruta actual
$router->dispatch();
