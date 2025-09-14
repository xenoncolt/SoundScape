<?php
namespace App\Controllers;

use App\Database\Connection;
use PDO;
use Exception;

class AuthController {
    private PDO $db;

    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
    }

    public function handle(string $logMode) {
        if ($logMode === 'login') {
            $this->processLogin();
        } elseif ($logMode === 'register') {
            $this->processRegister();
        } else {
            $_SESSION['login_error'] = "Invalid auth action.";
            header('Location: ?page=login');
        }
    }

    private function processLogin() {
        $identifier = trim($_POST['username'] ?? '');
        $pass = trim($_POST['password'] ?? '');
        $remember = isset($_POST['remember_me']);

        if (empty($identifier) || empty($pass)) {
            $_SESSION['login_error'] = "Username and password required.";
            header('Location: ?page=login');
            return;
        }

        $sqlQuery = $this->db-> prepare('
        SELECT id, username, email, password_hash, user_type, status, display_name, profile_image
            FROM users
            WHERE username = :ident OR email = :ident
            LIMIT 1');
        $sqlQuery->execute(['ident'=>$identifier]);
        $user = $sqlQuery->fetch();

        if (!$user || !password_verify($pass, $user['password_hash'])) {
            $_SESSION['login_error'] = "Username or password incorrect.";
            header('Location: ?page=login');
            return;
        }

        if ($user['status'] !== 'approved') {
            $_SESSION['login_error'] = "Account not approved. Contact admin.";
            header('Location: ?page=login');
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_type']  = $user['user_type'];
        $_SESSION['display_name'] = $user['display_name'] ?: $user['username'];
        $_SESSION['pfp_img'] = $user['profile_image'] ?? null;

        $this->db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?')->execute([$user['id']]);

        if ($remember) {
            ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30); // total 30 days

        }

        header('Location: ?page=dashboard');
    }

    private function processRegister() {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $pass = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';
        $userType = $_POST['user_type'] ?? 'general';
        $agreeTerms = isset($_POST['agree_terms']);

        $_SESSION['register_form_data'] = [
            'username' => $username,
            'email' => $email,
            'user_type' => $userType
        ];

        $errs = [];

        if (empty($username) || !preg_match('/^[a-zA-Z0-9_]{3,50}$/', $username)) {
            $errs[] = 'Username must be 3 to 50 char (letters, numbers or underscores)';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errs[] = 'This is not a valid email address (e.g. contact@xenoncolt.live)';
        }

        if (empty($pass) || strlen($pass) < 6) {
            $errs[] = 'Password must be at least 6 characters';
        } elseif ($pass !== $confirm) {
            $errs[] = 'Not matched with confirm password. Already forgotten?';
        }

        if (!$agreeTerms) {
            $errs[] = 'You must agree to the terms of service';
        }

        if (!in_array($userType, ['general', 'artist'])) {
            $errs[] = 'Umm... You have to selected at least one user type';
        }

        if ($errs) {
            $_SESSION['register_errors'] = implode('\n', $errs);
            header('Location: ?page=login');
            return;
        }

        $existUser = $this->db->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
        $existUser->execute([$username, $email]);
        if ($existUser->fetch()) {
            $_SESSION['register_errors'] = 'Username or email already exists. Please choose another.';
            header('Location: ?page=register');
            return;
        }

        $needUserApproval = $this-> getConfigBool('require_user_approval', true);
        $needArtistApproval = $this-> getConfigBool('require_artist_approval', true);

        $status = 'approved';
        if ($needUserApproval) {
            $status = 'pending';
        } elseif ($userType === 'artist' && $needArtistApproval) {
            $status = 'pending';
        }

        $displayName = $username;
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        
        $this->db->prepare(
            'INSERT INTO users (username, email, password_hash, user_type, status, display_name, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())'
        )->execute([$username, $email, $hash, $userType, $status, $displayName]);

        unset($_SESSION['register_form_data']);

        if ($status === 'approved') {
            $_SESSION['login_success'] = 'Account created!!! You can login now';
            header('Location: ?page=login');
        } else {
            $_SESSION['login_success'] = 'Account created!!! Awaiting admin approval.';
            header('Location: ?page=login');
        }
     }

     private function getConfigBool(string $key, bool $default) {
        try {
            $userQuery = $this->db->prepare(
                'SELECT key_value FROM config WHERE key_name = ? LIMIT 1',
            );
            $userQuery->execute([$key]);
            $userVal = $userQuery->fetchColumn();
            if ($userVal === false) return $default;
            return in_array($userVal, ['1', 'true', 'on', 'yes'], true);
        } catch (\Throwable) {
            return $default;
        }
    }
}
?>