<?php

// Incluir configuración
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

// Incluir núcleo del sistema
require_once __DIR__ . '/app/core/Router.php';
require_once __DIR__ . '/app/core/Controller.php';
require_once __DIR__ . '/app/core/Model.php';

// Incluir helpers y autoloader
require_once __DIR__ . '/app/helpers/autoload.php';

// Incluir modelos
require_once __DIR__ . '/app/models/User.php';
require_once __DIR__ . '/app/models/VehicleType.php';
require_once __DIR__ . '/app/models/Client.php';

// Incluir controladores
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/DashboardController.php';
require_once __DIR__ . '/app/controllers/UserController.php';
require_once __DIR__ . '/app/controllers/VehicleTypeController.php';
require_once __DIR__ . '/app/controllers/ClientController.php';
require_once __DIR__ . '/app/controllers/ExpertiseController.php';

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
$router->post('/vehicle-types/delete/{id}', 'VehicleTypeController@delete');
$router->post('/vehicle-types/toggle-status/{id}', 'VehicleTypeController@toggleStatus');

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

// Rutas CRUD de clientes
$router->get('/clients', 'ClientController@index');
$router->get('/clients/create', 'ClientController@create');
$router->post('/clients/store', 'ClientController@store');
$router->get('/clients/show/{id}', 'ClientController@show');
$router->get('/clients/edit/{id}', 'ClientController@edit');
$router->post('/clients/update/{id}', 'ClientController@update');
$router->post('/clients/destroy/{id}', 'ClientController@destroy');
$router->post('/clients/activate/{id}', 'ClientController@activate');
$router->post('/clients/deactivate/{id}', 'ClientController@deactivate');
$router->get('/clients/search', 'ClientController@search');
$router->get('/clients/stats', 'ClientController@stats');
$router->get('/clients/export', 'ClientController@export');

// Rutas de Peritajes (Expertise)
$router->get('/expertise', 'ExpertiseController@index');
$router->get('/expertise/create', 'ExpertiseController@create');
$router->get('/expertise/show/{id}', 'ExpertiseController@show');
$router->post('/expertise/store', 'ExpertiseController@store');
$router->get('/expertise/search-clients', 'ExpertiseController@searchClients');
$router->get('/expertise/step2', 'ExpertiseController@step2');
$router->get('/expertise/search-vehicle-types', 'ExpertiseController@searchVehicleTypes');
$router->post('/expertise/save-step2', 'ExpertiseController@saveStep2');
$router->get('/expertise/step3', 'ExpertiseController@step3');
$router->get('/expertise/get-pieces-by-vehicle-type', 'ExpertiseController@getPiecesByVehicleType');
$router->get('/expertise/get-inspection-concepts', 'ExpertiseController@getInspectionConcepts');
$router->post('/expertise/save-step3', 'ExpertiseController@saveStep3');
$router->get('/expertise/step4', 'ExpertiseController@step4');
$router->post('/expertise/save-step4', 'ExpertiseController@saveStep4');
$router->get('/expertise/step5', 'ExpertiseController@step5');
$router->post('/expertise/save-step5', 'ExpertiseController@saveStep5');
$router->get('/expertise/step6', 'ExpertiseController@step6');
$router->post('/expertise/save-step6', 'ExpertiseController@saveStep6');
$router->get('/expertise/step7', 'ExpertiseController@step7');
$router->post('/expertise/save-step7', 'ExpertiseController@saveStep7');
$router->get('/expertise/step8', 'ExpertiseController@step8');
$router->post('/expertise/save-step8', 'ExpertiseController@saveStep8');
$router->get('/expertise/step9', 'ExpertiseController@step9');
$router->post('/expertise/save-step9', 'ExpertiseController@saveStep9');
$router->get('/expertise/step10', 'ExpertiseController@step10');
$router->post('/expertise/save-step10', 'ExpertiseController@saveStep10');
$router->get('/expertise/step11', 'ExpertiseController@step11');
$router->post('/expertise/save-step11', 'ExpertiseController@saveStep11');
$router->get('/expertise/step12', 'ExpertiseController@step12');
$router->post('/expertise/save-final', 'ExpertiseController@saveFinal');

// Procesar la ruta actual
$router->dispatch();
