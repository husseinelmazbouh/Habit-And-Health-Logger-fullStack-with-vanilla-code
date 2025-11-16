<?php
require_once __DIR__ . '/services/ResponseService.php';
require_once __DIR__ . '/routes/apis.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

$base_path = dirname($_SERVER['SCRIPT_NAME']);
$request_uri = $_SERVER['REQUEST_URI'];


if ($base_path !== '/' && strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}

$path = parse_url($request_uri, PHP_URL_PATH);

$path = rtrim($path, '/');
if (empty($path)) {
    $path = '/';
}


if (isset($apis[$path])) {
    $route = $apis[$path];
    
    if (!isset($route['controller']) || !isset($route['method'])) {
        echo ResponseService::response(500, "Invalid route configuration for: $path");
        exit;
    }
    
    $controller_name = $route['controller'];
    $method = $route['method'];
    
    $controller_file = __DIR__ . "/controllers/{$controller_name}.php";
    
    if (!file_exists($controller_file)) {
        echo ResponseService::response(500, "Controller file not found: {$controller_name}.php");
        exit;
    }
    
    require_once $controller_file;
    
    if (!class_exists($controller_name)) {
        echo ResponseService::response(500, "Controller class not found: {$controller_name}");
        exit;
    }
    
    $controller = new $controller_name();
    
    if (!method_exists($controller, $method)) {
        echo ResponseService::response(500, "Method not found: {$controller_name}::{$method}()");
        exit;
    }
    
    $controller->$method();
} else {
    echo ResponseService::response(404, [
        "message" => "Route Not Found",
        "requested_path" => $path,
        "available_routes" => array_keys($apis)
    ]);
}
?>