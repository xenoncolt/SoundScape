<?php
namespace App\Controllers;

use App\Database\Connection;
use PDO;
use Exception;

class DiscordController {
    private PDO $db;
    private string $clientId;
    private string $clientSecret;
    private string $redirectUri;

    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
        $this->loadDiscordConfig();
    }

    private function loadDiscordConfig(): void {
        try {
            $config = $this->getConfigValue('discord_client_id');
            $this->clientId = $config ?: '';
            
            $config = $this->getConfigValue('discord_client_secret');
            $this->clientSecret = $config ?: '';
            
            $config = $this->getConfigValue('discord_redirect_uri');
            $this->redirectUri = $config ?: '';
        } catch (Exception $e) {
            $this->clientId = '';
            $this->clientSecret = '';
            $this->redirectUri = '';
        }
    }

    private function getConfigValue(string $key): ?string {
        $stmt = $this->db->prepare('SELECT key_value FROM config WHERE key_name = ? LIMIT 1');
        $stmt->execute([$key]);
        return $stmt->fetchColumn() ?: null;
    }

    public function initiate(): void {
        if (empty($this->clientId) || empty($this->redirectUri)) {
            $_SESSION['login_error'] = 'Discord authentication is not configured.';
            header('Location: ?page=login');
            return;
        }

        $state = bin2hex(random_bytes(16));
        $_SESSION['discord_oauth_state'] = $state;

        $params = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'identify email',
            'state' => $state
        ]);

        header('Location: https://discord.com/api/oauth2/authorize?' . $params);
        exit;
    }

    public function callback(): void {
        $code = $_GET['code'] ?? '';
        $state = $_GET['state'] ?? '';
        $storedState = $_SESSION['discord_oauth_state'] ?? '';

        unset($_SESSION['discord_oauth_state']);

        if (empty($code) || $state !== $storedState) {
            $_SESSION['login_error'] = 'Invalid Discord authentication response.';
            header('Location: ?page=login');
            return;
        }

        try {
            $accessToken = $this->getAccessToken($code);
            $userInfo = $this->getUserInfo($accessToken);
            
            $this->processDiscordUser($userInfo);
        } catch (Exception $e) {
            $_SESSION['login_error'] = 'Discord authentication failed: ' . $e->getMessage();
            header('Location: ?page=login');
        }
    }

    private function getAccessToken(string $code): string {
        $tokenUrl = 'https://discord.com/api/oauth2/token';
        
        $postData = http_build_query([
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUri
        ]);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postData
            ]
        ]);

        $response = file_get_contents($tokenUrl, false, $context);
        if ($response === false) {
            throw new Exception('Failed to get access token');
        }

        $data = json_decode($response, true);
        if (!isset($data['access_token'])) {
            throw new Exception('Invalid token response');
        }

        return $data['access_token'];
    }

    private function getUserInfo(string $accessToken): array {
        $userUrl = 'https://discord.com/api/users/@me';
        
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => 'Authorization: Bearer ' . $accessToken
            ]
        ]);

        $response = file_get_contents($userUrl, false, $context);
        if ($response === false) {
            throw new Exception('Failed to get user info');
        }

        $userData = json_decode($response, true);
        if (!$userData) {
            throw new Exception('Invalid user data response');
        }

        return $userData;
    }

    private function processDiscordUser(array $userInfo): void {
        $discordId = $userInfo['id'] ?? '';
        $email = $userInfo['email'] ?? '';
        $username = $userInfo['username'] ?? '';
        $discriminator = $userInfo['discriminator'] ?? '';
        $avatar = $userInfo['avatar'] ?? '';

        if (empty($discordId) || empty($email)) {
            throw new Exception('Missing required user information');
        }

        $existingUser = $this->db->prepare('SELECT * FROM users WHERE discord_id = ? OR email = ? LIMIT 1');
        $existingUser->execute([$discordId, $email]);
        $user = $existingUser->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (empty($user['discord_id'])) {
                $this->linkDiscordAccount($user['id'], $discordId, $username);
            }
            
            $this->loginUser($user);
        } else {
            $discordUsername = $username . ($discriminator !== '0' ? '#' . $discriminator : '');
            $this->createDiscordUser($discordId, $email, $username, $discordUsername, $avatar);
        }
    }

    private function linkDiscordAccount(int $userId, string $discordId, string $discordUsername): void {
        $stmt = $this->db->prepare('UPDATE users SET discord_id = ?, discord_username = ? WHERE id = ?');
        $stmt->execute([$discordId, $discordUsername, $userId]);
    }

    private function createDiscordUser(string $discordId, string $email, string $username, string $discordUsername, ?string $avatar): void {
        $baseUsername = $this->sanitizeUsername($username);
        $finalUsername = $this->getUniqueUsername($baseUsername);
        
        $displayName = $username;
        $profileImage = $avatar ? "https://cdn.discordapp.com/avatars/{$discordId}/{$avatar}.png?size=128" : null;
        
        $randomPassword = bin2hex(random_bytes(16));
        $passwordHash = password_hash($randomPassword, PASSWORD_DEFAULT);
        
        $needUserApproval = $this->getConfigBool('require_user_approval', true);
        $status = $needUserApproval ? 'pending' : 'approved';

        $stmt = $this->db->prepare('
            INSERT INTO users (username, email, password_hash, user_type, status, display_name, profile_image, discord_id, discord_username, email_verified, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ');
        
        $stmt->execute([
            $finalUsername,
            $email,
            $passwordHash,
            'general',
            $status,
            $displayName,
            $profileImage,
            $discordId,
            $discordUsername,
            1
        ]);

        $userId = $this->db->lastInsertId();
        
        $newUser = $this->db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $newUser->execute([$userId]);
        $user = $newUser->fetch(PDO::FETCH_ASSOC);

        if ($status === 'approved') {
            $this->loginUser($user);
        } else {
            $_SESSION['login_success'] = 'Discord account linked! Awaiting admin approval.';
            header('Location: ?page=login');
        }
    }

    private function sanitizeUsername(string $username): string {
        $clean = preg_replace('/[^a-zA-Z0-9_]/', '', $username);
        $clean = substr($clean, 0, 30);
        return $clean ?: 'discord_user';
    }

    private function getUniqueUsername(string $baseUsername): string {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
        $stmt->execute([$baseUsername]);
        
        if ($stmt->fetchColumn() == 0) {
            return $baseUsername;
        }

        $counter = 1;
        do {
            $testUsername = $baseUsername . $counter;
            $stmt->execute([$testUsername]);
            $exists = $stmt->fetchColumn() > 0;
            $counter++;
        } while ($exists);

        return $testUsername;
    }

    private function loginUser(array $user): void {
        if ($user['status'] !== 'approved') {
            $_SESSION['login_error'] = 'Your account is pending approval.';
            header('Location: ?page=login');
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['display_name'] = $user['display_name'] ?: $user['username'];
        $_SESSION['pfp_img'] = $user['profile_image'];

        $stmt = $this->db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = ?');
        $stmt->execute([$user['id']]);

        header('Location: ?page=dashboard');
    }

    private function getConfigBool(string $key, bool $default): bool {
        try {
            $value = $this->getConfigValue($key);
            if ($value === null) return $default;
            return in_array(strtolower($value), ['1', 'true', 'on', 'yes'], true);
        } catch (Exception $e) {
            return $default;
        }
    }
}