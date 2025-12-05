<?php

class Router {
    private $routes = [];
    private $currentRoute;
    private $basePath;
    
    public function __construct() {
        $this->basePath = $this->detectBasePath();
        $this->currentRoute = $this->getCurrentRoute();
    }
    
    /**
     * Detectar automáticamente el path base de la aplicación
     */
    private function detectBasePath() {
        $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
        return ($scriptPath === '/' || $scriptPath === '\\') ? '' : $scriptPath;
    }
    
    private function getCurrentRoute() {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH);
        
        // Remover el directorio base si existe
        if (!empty($this->basePath) && strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
        }
        
        return $path ?: '/';
    }
    
    public function get($route, $callback) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->addRoute($route, $callback);
        }
    }
    
    public function post($route, $callback) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->addRoute($route, $callback);
        }
    }
    
    private function addRoute($route, $callback) {
        $this->routes[$route] = $callback;
    }
    
    public function dispatch() {
        // Buscar ruta exacta
        if (isset($this->routes[$this->currentRoute])) {
            return $this->executeCallback($this->routes[$this->currentRoute]);
        }
        
        // Buscar rutas con parámetros
        foreach ($this->routes as $route => $callback) {
            if ($this->matchRoute($route)) {
                return $this->executeCallback($callback);
            }
        }
        
        // Ruta no encontrada
        $this->notFound();
    }
    
    private function matchRoute($route) {
        $routePattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
        $routePattern = str_replace('/', '\/', $routePattern);
        return preg_match('/^' . $routePattern . '$/', $this->currentRoute);
    }
    
    private function executeCallback($callback) {
        if (is_string($callback)) {
            $parts = explode('@', $callback);
            $controller = $parts[0];
            $method = $parts[1] ?? 'index';
            
            $controllerFile = APP_PATH . '/controllers/' . $controller . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                if (class_exists($controller)) {
                    $instance = new $controller();
                    
                    if (method_exists($instance, $method)) {
                        // Extraer parámetros de la ruta
                        $params = $this->extractParams();
                        return call_user_func_array([$instance, $method], $params);
                    }
                }
            }
        } elseif (is_callable($callback)) {
            return call_user_func($callback);
        }
        
        $this->notFound();
    }
    
    private function extractParams() {
        // Buscar la ruta coincidente y extraer parámetros
        foreach ($this->routes as $route => $callback) {
            if ($this->matchRoute($route)) {
                $routePattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $route);
                $routePattern = str_replace('/', '\/', $routePattern);
                
                if (preg_match('/^' . $routePattern . '$/', $this->currentRoute, $matches)) {
                    array_shift($matches); // Remover la coincidencia completa
                    return $matches;
                }
            }
        }
        return [];
    }
    
    private function notFound() {
        http_response_code(404);
        echo "404 - Página no encontrada";
    }
    
    public function redirect($url) {
        header('Location: ' . APP_URL . ltrim($url, '/'));
        exit;
    }
}
