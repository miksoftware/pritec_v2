<?php

// Configuración de la aplicación
define('APP_NAME', 'Pritec v2.0');
define('APP_VERSION', '2.0.0');
define('APP_URL', 'http://localhost/pritec_v2/');

// Configuración de rutas
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('ASSETS_URL', APP_URL . 'public/assets/');

// Configuración de sesiones
define('SESSION_LIFETIME', 3600); // 1 hora
define('SESSION_NAME', 'pritec_session');

// Configuración de seguridad
define('HASH_ALGO', PASSWORD_DEFAULT);
define('CSRF_TOKEN_NAME', '_token');

// Configuración de timezone
date_default_timezone_set('America/Mexico_City');

// Configuración de errores (cambiar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); // Cambiar a 1 en HTTPS
    ini_set('session.use_strict_mode', 1);
    session_name(SESSION_NAME);
    session_start();
}
