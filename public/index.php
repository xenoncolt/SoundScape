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

function isDBReady() {
    try {
        if (!file_exists(__DIR__ . '/../.env')) {
            return false;
        }

        $connection  = \App\Database\Connection::getInstance();
        if(!$connection ->isConnected()) {
            return false;
        }

        $migration = new \App\Database\Migration();
        return $migration->isSetupCompleted();
    } catch (Exception) {
        return false;
    }
}

$router = new Router();

$dbReady = isDBReady();

// let admin handle by ui 
$databaseReady = false;
$setupComplete = false;

// Public routes
$router->get('home', function(){
    include __DIR__ . '/../src/UI/home.php';
});

// Setup routes
$router->get('setup', function() use ($dbReady) {
    if ($dbReady) redirect('?page=home');

    global $setupComplete;
    if ($setupComplete) {
        redirect('?page=home');
    }
    include __DIR__ . '/../src/UI/setup.php';
});

$router->post('complete-setup', function() use ($dbReady) {
    if ($dbReady) redirect('?page=home');

    global $setupComplete;
    if ($setupComplete) {
        redirect('?page=home');
    }
    include __DIR__ . '/../src/Controllers/SetupController.php';
});

// Auth routes
$router->get('login', function() use ($dbReady) {
    if (!$dbReady) {
        redirect('?page=setup');
        return;
    }

    if (isLoggedIn()) {
        redirect('?page=dashboard');
    }
    include __DIR__ . '/../src/UI/login.php';
});

$router->post('login', function() use ($dbReady) {
    if (!$dbReady) {
        redirect('?page=setup');
        return;
    }

    $controller = new \App\Controllers\AuthController();
    $controller->handle('login');
});

$router->get('register', function() use ($dbReady) {
    if (!$dbReady) {
        redirect('?page=setup');
        return;
    }


    if (isLoggedIn()) {
        redirect('?page=dashboard');
    }
    include __DIR__ . '/../src/UI/register.php';
});

$router->post('register', function() use ($dbReady) {
    if (!$dbReady) {
        redirect('?page=setup');
        return;
    }

    $controller = new \App\Controllers\AuthController();
    $controller->handle('register');
    
});

$router->get('logout', function() {
    session_unset();
    session_destroy();
    redirect('?page=home');
});

// login required routes
$router->get('dashboard', function() use ($dbReady) {
    if (!$dbReady) {
        redirect('?page=setup');
        return;
    }
    
    requiredLogin();
    include __DIR__ . '/../src/UI/dashboard.php';
});
// admin routes

// artist routes

// music routes

// error routes

// API routes

try {
    $currentPage = $_GET['page'] ?? 'home';
    $setupPages = ['home', 'setup', 'complete-setup'];

    if (!$dbReady && !in_array($currentPage, $setupPages)) {
        redirect('?page=setup');
    }

    $router->dispatch();

} catch (Exception $e) {
    error_log('Router dispatch failed. Error: ' . $e->getMessage());
    http_response_code(500);
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Server Error | SoundScape</title>
        <link href="assets/css/styles.css" rel="stylesheet">
    </head>
    <body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-3xl font-bold mb-4">🚫 Server Error</h1>
            <p class="text-gray-400 mb-6">Something went wrong with the server.</p>
            <a href="?page=home" class="inline-block px-6 py-3 bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                🏠 Back to Home
            </a>
        </div>
    </body>
    </html>';
}

ob_end_flush();
?>