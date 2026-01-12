<?php
// filepath: c:\xampp\htdocs\notas-app\backend\api\index.php

// -------------------------------------------------
// Configuración mínima (puedes dejarla tal cual)
// -------------------------------------------------
ini_set('display_errors', '0');
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');   // solo para desarrollo

// -------------------------------------------------
// Cargar el router (versión simplificada)
// -------------------------------------------------
require_once __DIR__ . "/../database/conection/pool.php";
require_once __DIR__ . '/router.php';

$router = new Router();

/* -------------------------------------------------
   REGISTRO DE RUTAS
   Cada ruta se escribe con:
   - la URL que recibirá (/login, /register)
   - la ruta física del archivo del controlador
   - el nombre de la clase dentro del archivo
   - el método que debe ejecutarse
   ------------------------------------------------- */

$router->post(
    '/login',                                 // URL que el cliente enviará
    __DIR__ . '/../services/user/LoginUser.php', // archivo a incluir
    'LoginUser',                              // clase dentro del archivo
    'handleLogin'                             // método a ejecutar
);

$router->post(
    '/register',
    __DIR__ . '/../services/user/RegisterUser.php',
    'RegisterUser',
    'handleRegister'
);

/* Si quisieras una ruta GET, sería similar:
$router->get('/profile', __DIR__.'/../services/user/Profile.php', 'Profile', 'show');
*/

// -----------------------------------------------------------------
// Si llegamos aquí, ninguna ruta coincidió → responder 404 JSON
// -----------------------------------------------------------------
http_response_code(404);
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'success' => false,
    'message' => 'Ruta no encontrada'
]);
