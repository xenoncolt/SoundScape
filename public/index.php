<?php
require_once __DIR__ . '/../vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

switch ($path) {
    case '/':
        include __DIR__ . '/pages/home.php';
        break;    
    default:
        http_response_code(404);
        echo "<h1>You r trying to access a page which doesn't exist</h1>";
}
?>