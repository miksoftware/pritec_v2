<?php

// Configuración de la aplicación
define('APP_NAME', 'Pritec v2.0');
define('APP_VERSION', '2.0.0');

// Detectar automáticamente la URL base
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = ($scriptPath === '/' || $scriptPath === '\\') ? '' : $scriptPath;
define('APP_URL', $protocol . $host . $basePath . '/');

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

// Configuración de errores (desactivar display_errors en producción)
$isProduction = ($host !== 'localhost' && strpos($host, '127.0.0.1') === false);
error_reporting(E_ALL);
ini_set('display_errors', $isProduction ? 0 : 1);
ini_set('log_errors', 1);

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', $protocol === 'https://' ? 1 : 0);
    ini_set('session.use_strict_mode', 1);
    session_name(SESSION_NAME);
    session_start();
}
