<?php
class Router
{
    private $method;
    private $path;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = $_SERVER['SCRIPT_NAME'];          // /notas-app/backend/api/index.php

        // Obtener la parte después de index.php (ej: /login)
        $this->path = str_replace($scriptName, '', $requestUri);
        $this->path = '/' . trim($this->path, '/');     // normaliza: "/login"

        // Debug: registrar método y ruta calculada en el log de PHP/Apache
        error_log('Router init - method: ' . $this->method . ', path: ' . $this->path);

        // Si la petición es OPTIONS (preflight CORS), respondemos inmediatamente
        // con los encabezados que el navegador necesita. No ejecutamos ninguna ruta.
        if ($this->method === 'OPTIONS') {
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            // 204 No Content indica que la petición preflight fue exitosa.
            http_response_code(204);
            exit;
        }
    }

    /* -------------------------------------------------
       Métodos GET y POST – versión estática
       ------------------------------------------------- */
    public function post($route, $controllerFile, $className, $action)
    {

        if ($this->method === 'POST' && $this->path === $route) {

            // 1️⃣ Incluir el archivo del controlador (hard‑codeado)
            require_once $controllerFile;

            // 2️⃣ Instanciar la clase (nombre ya conocido)
            $instance = new $className();

            // 3️⃣ Ejecutar el método
            $instance->$action();

            exit;          // detener ejecución después de atender la ruta
        }
    }

    public function get($route, $controllerFile, $className, $action)
    {
        if ($this->method === 'GET' && $this->path === $route) {

            echo "controllerFile: " . $controllerFile . "\n";
            require $controllerFile;
            echo "className: " . $className;
            $instance = new $className();
            $instance->$action();
            exit;
        }
    }

    /* -------------------------------------------------
       Utilidad para devolver JSON (igual que antes)
       ------------------------------------------------- */
    public function sendJson($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }
}
