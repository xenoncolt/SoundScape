<?php
ob_start();
session_start();

date_default_timezone_set('Asia/Dhaka');

require_once __DIR__ . '/../vendor/autoload.php';

// $uri = $_SERVER['REQUEST_URI'];
// $path = parse_url($uri, PHP_URL_PATH);

// switch ($path) {
//     case '/':
//         include __DIR__ . '/pages/home.php';
//         break;    
//     default:
//         http_response_code(404);
//         echo "<h1>You r trying to access a page which doesn't exist</h1>";
// }

class Router {
    private array $routes = [];
    public function get (string $path, callable $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post (string $path, callable $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch (): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $page = $_GET['page'] ?? 'home';

        if (isset($this->routes[$method][$page])) {
            call_user_func($this->routes[$method][$page]);
        } else {
            $this->showError();
        }
    }

    private function showError(): void {
        http_response_code(404);
        include __DIR__ . '/../src/UI/ErrorPage.php';
    }
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requiredLogin(): void {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect('?page=login');
    }
}

function getCurrentUser(): array {
    if (!isLoggedIn()) {
        return [];
    }
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'user_type' => $_SESSION['user_type'] ?? 'general',
        'display_name' => $_SESSION['display_name'] ?? $_SESSION['username'] ?? '',
        'pfp_img' => $_SESSION['pfp_img'] ?? null
    ];
}

// Public routes

// Setup routes

// Auth routes

// login required routes

// admin routes

// artist routes

// music routes

// error routes

// API routes

try {
    $routes->dispatch();

} catch (Exception $e) {
    error_log('Router dispatch failed. Error: ' . $e->getMessage());
    http_response_code(500);
    echo '<h1>Something went wrong with server. Please stay tuned. It will be fixed soon.</h1>';
}

ob_end_flush();
?>