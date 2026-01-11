<?php
// filepath: c:\xampp\htdocs\notas-app\backend\api\index.php

ini_set('display_errors', '0');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // solo en desarrollo

set_exception_handler(function($e){
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
});

set_error_handler(function($severity, $message, $file, $line){
    throw new ErrorException($message, 0, $severity, $file, $line);
});

register_shutdown_function(function(){
    $err = error_get_last();
    if ($err !== null) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Fatal error: ' . $err['message']]);
        exit;
    }
});

require_once __DIR__ . "/../database/conection/pool.php";
require_once __DIR__ . '/router.php';

$router = new Router();

// Registrar rutas para GET y POST
$router->post('/register', 'user/RegisterUser', 'handleRegister');
$router->get('/register', 'user/RegisterUser', 'handleRegister');

$router->post('/login', 'user/LoginUser', 'handleLogin');
$router->get('/login', 'user/LoginUser', 'handleLogin');

// Rutas de notas (próximas)
// $router->get('/notas', 'note/NoteUser', 'getNotes');
// $router->post('/notas', 'note/NoteUser', 'createNote');
// $router->put('/notas/:id', 'note/NoteUser', 'updateNote');
// $router->delete('/notas/:id', 'note/NoteUser', 'deleteNote');

// Si ninguna ruta coincide
http_response_code(404);
echo json_encode(['success' => false, 'message' => 'Ruta no encontrada']);
?>