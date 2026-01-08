<?php
class Router
{
    private $method;
    private $path;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = $_SERVER['SCRIPT_NAME']; // /notas-app/backend/api/index.php

        // Extraer la parte después de index.php o del directorio del script
        if (strpos($requestUri, $scriptName) === 0) {
            $this->path = substr($requestUri, strlen($scriptName));
        } else {
            $indexPos = strpos($requestUri, basename($scriptName));
            if ($indexPos !== false) {
                $this->path = substr($requestUri, $indexPos + strlen(basename($scriptName)));
            } else {
                $dir = dirname($scriptName);
                if ($dir === '/' || $dir === '\\') {
                    $this->path = $requestUri;
                } elseif (strpos($requestUri, $dir) === 0) {
                    $this->path = substr($requestUri, strlen($dir));
                } else {
                    $this->path = $requestUri;
                }
            }
        }

        $this->path = '/' . trim($this->path, '/'); // normalizar: '/register'
    }

    public function post($route, $controller, $action)
    {
        if ($this->method === 'POST' && $this->matchRoute($route)) {
            $this->callController($controller, $action);
            exit; // <- Detener ejecución después de manejar la ruta
        }
    }

    public function get($route, $controller, $action)
    {
        if ($this->method === 'GET' && $this->matchRoute($route)) {
            $this->callController($controller, $action);
            exit; // <- Detener ejecución después de manejar la ruta
        }
    }

    private function matchRoute($route)
    {
        // Acepta exact match o prefijo (ej: /register o /register/... )
        return $this->path === $route || strpos($this->path, $route . '/') === 0;
    }

    private function callController($controller, $action)
    {
        try {
            $controllerFile = __DIR__ . "/../services/" . str_replace(['\\','/'], DIRECTORY_SEPARATOR, $controller) . ".php";
            if (!file_exists($controllerFile)) {
                throw new Exception("Controller file not found: $controllerFile");
            }

            require_once $controllerFile;

            $className = basename(str_replace('\\', '/', $controller));
            if (!class_exists($className)) {
                throw new Exception("Controller class not found: $className");
            }

            $instance = new $className();
            if (!method_exists($instance, $action)) {
                throw new Exception("Action not found: {$className}::{$action}");
            }

            return $instance->$action();
        } catch (Exception $e) {
            $this->sendJson(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function sendJson($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
?>