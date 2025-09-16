<?php
namespace App\Controllers;

use App\Database\Connection;
use PDO;
use Exception;

class DiscordConfigController {
    private PDO $db;

    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
    }

    public function saveConfig(): void {
        if ($_SESSION['user_type'] !== 'admin') {
            http_response_code(403);
            $_SESSION['discord_error'] = 'Admin access required';
            header('Location: ?page=discord-config');
            return;
        }

        try {
            $enabled = isset($_POST['discord_enabled']) ? '1' : '0';
            $clientId = trim($_POST['discord_client_id'] ?? '');
            $clientSecret = trim($_POST['discord_client_secret'] ?? '');
            $redirectUri = trim($_POST['discord_redirect_uri'] ?? '');

            if ($enabled === '1') {
                if (empty($clientId)) {
                    throw new Exception('Discord Client ID is required when Discord OAuth is enabled');
                }
                if (empty($clientSecret)) {
                    throw new Exception('Discord Client Secret is required when Discord OAuth is enabled');
                }
                if (empty($redirectUri)) {
                    throw new Exception('Discord Redirect URI is required when Discord OAuth is enabled');
                }
                if (!filter_var($redirectUri, FILTER_VALIDATE_URL)) {
                    throw new Exception('Discord Redirect URI must be a valid URL');
                }
            }

            $configs = [
                'discord_enabled' => $enabled,
                'discord_client_id' => $clientId,
                'discord_client_secret' => $clientSecret,
                'discord_redirect_uri' => $redirectUri
            ];

            $this->db->beginTransaction();

            foreach ($configs as $key => $value) {
                $stmt = $this->db->prepare('
                    INSERT INTO config (key_name, key_value, updated_at) 
                    VALUES (?, ?, NOW()) 
                    ON DUPLICATE KEY UPDATE key_value = ?, updated_at = NOW()
                ');
                $stmt->execute([$key, $value, $value]);
            }

            $this->db->commit();

            $statusMessage = $enabled === '1' ? 'Discord OAuth has been enabled successfully!' : 'Discord OAuth has been disabled successfully!';
            $_SESSION['discord_success'] = $statusMessage;
            
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $_SESSION['discord_error'] = $e->getMessage();
        }

        header('Location: ?page=discord-config');
    }
}