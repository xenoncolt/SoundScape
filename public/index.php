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

$router = new Router();

// let admin handle by ui 
$databaseReady = false;
$setupComplete = false;

// Public routes
$router->get('home', function(){
    include __DIR__ . '/../src/UI/home.php';
});

// Setup routes
$router->get('setup', function() {
    global $setupComplete;
    if ($setupComplete) {
        redirect('?page=home');
    }
    include __DIR__ . '/../src/UI/setup.php';
});

$router->post('complete-setup', function() {
    global $setupComplete;
    if ($setupComplete) {
        redirect('?page=home');
    }
    include __DIR__ . '/../src/Controllers/SetupController.php';
});

// Auth routes
$router->get('login', function() {
    if (isLoggedIn()) {
        redirect('?page=dashboard');
    }
    include __DIR__ . '/../src/UI/login.php';
});

$router->post('login', function() {
    include __DIR__ . '/../src/Controllers/AuthController.php';
});

$router->get('register', function() {
    if (isLoggedIn()) {
        redirect('?page=dashboard');
    }
    include __DIR__ . '/../src/UI/register.php';
});

$router->post('register', function() {
    include __DIR__ . '/../src/Controllers/AuthController.php';
});

$router->get('logout', function() {
    session_unset();
    session_destroy();
    redirect('?page=home');
});

// login required routes
$router->get('dashboard', function() {
    requiredLogin();
    include __DIR__ . '/../src/UI/dashboard.php';
});
// admin routes

// artist routes

// music routes

// error routes

// API routes

try {
    $router->dispatch();

} catch (Exception $e) {
    error_log('Router dispatch failed. Error: ' . $e->getMessage());
    http_response_code(500);
    echo '<h1>Something went wrong with server. Please stay tuned. It will be fixed soon.</h1>';
}

ob_end_flush();
?>