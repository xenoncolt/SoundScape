<?php

namespace App\Controllers;

use Exception;
use PDO;
use PDOException;

class SetupController {
    private ?PDO $pdo = null;
    private array $config = [];
    public function processSetup(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?page=setup');
            return;
        }
        
        try {
            $formData = $this->validateAndSanitizeInput();
            $this->testDatabaseConnection($formData['database']);
            $this->createDatabaseIfNeeded($formData['database']);
            $this->createDatabaseTables();
            $this->createAdminAccount($formData['admin']);
            $this->saveConfiguration($formData);
            $this->createDirectories();
            $this->generateEnvFile($formData);
            $this->markSetupCompleted();
            $this->showSuccess($formData);
        } catch (Exception $e) {
            $this->showError($e->getMessage());
        }
    }
    


    private function validateAndSanitizeInput(): array {
        $errors = [];

        $database = [
            'host' => trim($_POST['db_host'] ?? ''),
            'port' => intval($_POST['db_port'] ?? 3306),
            'name' => trim($_POST['db_name'] ?? ''),
            'username' => trim($_POST['db_user'] ?? ''),
            'password' => $_POST['db_password'] ?? ''
        ];
        
        if (empty($database['host'])) {
            $errors[] = "Database Host must be provided.";
        }
        
        if (empty($database['name'])) {
            $errors[] = "Without database name u cant go further.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $database['name'])) {
            $errors[] = "Database name can only contain letters, numbers, and underscores. Not more than that. Make it simple.";
        }
        
        if (empty($database['username'])) {
            $errors[] = "Ahhh. Username is missing.";
        }
        
        if ($database['port'] < 1 || $database['port'] > 65535) {
            $errors[] = "Database port must be between 1 and 65535. Or default is 3306.";
        }
        
        $admin = [
            'username' => trim($_POST['admin_username'] ?? ''),
            'email' => trim($_POST['admin_email'] ?? ''),
            'password' => $_POST['admin_password'] ?? '',
            'password_confirm' => $_POST['admin_password_confirm'] ?? ''
        ];
        
        if (empty($admin['username'])) {
            $errors[] = "Admin username is required";
        } elseif (strlen($admin['username']) < 3) {
            $errors[] = "Admin username must be at least 3 characters long";
        } elseif (strlen($admin['username']) > 50) {
            $errors[] = "Dont make it complicated man. Keep it simple. Less than 50 characters.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $admin['username'])) {
            $errors[] = "Admin username can only contain letters, numbers, and underscores";
        }
        
        if (empty($admin['email'])) {
            $errors[] = "Admin email is required";
        } elseif (!filter_var($admin['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid admin email address";
        }
        
        if (empty($admin['password'])) {
            $errors[] = "Admin password is required";
        } elseif (strlen($admin['password']) < 6) {
            $errors[] = "Admin password must be at least 6 characters long for security purpose";
        }
        
        if ($admin['password'] !== $admin['password_confirm']) {
            $errors[] = "Admin passwords do not match. Again type";
        }
        
        $server = [
            'name' => trim($_POST['server_name'] ?? 'SoundScape Server'),
            'max_upload_size' => intval($_POST['max_upload_size'] ?? 50),
            'allow_registration' => isset($_POST['allow_registration']),
            'require_approval' => isset($_POST['require_approval']),
            'require_artist_approval' => isset($_POST['require_artist_approval']),
            'allow_public_music' => isset($_POST['allow_public_music'])
        ];
        
        if (empty($server['name'])) {
            $errors[] = "How are you supposed to run server without a name?";
        }
        
        if ($server['max_upload_size'] < 1 || $server['max_upload_size'] > 1000) {
            $errors[] = "Max upload size must be between 1 and 1000 MB";
        }
        
        $api = [
            'discord_enabled' => isset($_POST['discord_enabled']),
            'discord_client_id' => trim($_POST['discord_client_id'] ?? ''),
            'discord_client_secret' => trim($_POST['discord_client_secret'] ?? ''),
            'smtp_enabled' => isset($_POST['smtp_enabled']),
            'smtp_host' => trim($_POST['smtp_host'] ?? ''),
            'smtp_port' => intval($_POST['smtp_port'] ?? 587),
            'smtp_username' => trim($_POST['smtp_username'] ?? ''),
            'smtp_password' => $_POST['smtp_password'] ?? '',
            'smtp_encryption' => $_POST['smtp_encryption'] ?? 'tls'
        ];
        
        if ($api['discord_enabled']) {
            if (empty($api['discord_client_id'])) {
                $errors[] = "Discord Client ID is required when Discord login is enabled";
            }
            if (empty($api['discord_client_secret'])) {
                $errors[] = "Discord Client Secret is required when Discord login is enabled";
            }
        }
        
        if ($api['smtp_enabled']) {
            if (empty($api['smtp_host'])) {
                $errors[] = "SMTP host is required when email is enabled";
            }
            if ($api['smtp_port'] < 1 || $api['smtp_port'] > 65535) {
                $errors[] = "Please check you SMTP port on google page. You are typing wrong port.";
            }
        }
        
        if (!empty($errors)) {
            throw new Exception("Validation failed:\n• " . implode("\n• ", $errors));
        }
        
        return [
            'database' => $database,
            'admin' => $admin,
            'server' => $server,
            'api' => $api
        ];
    }
    
    private function testDatabaseConnection(array $dbConfig): void {
        try {
            $dsn = sprintf(
                "mysql:host=%s;port=%d;charset=utf8mb4",
                $dbConfig['host'],
                $dbConfig['port']
            );
            
            $this->pdo = new PDO(
                $dsn,
                $dbConfig['username'],
                $dbConfig['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_TIMEOUT => 10 
                ]
            );
            
            $this->pdo->query('SELECT 1'); // for test
            
        } catch (PDOException $e) {
            $errorMessage = "Database connection failed: ";
            
            if (strpos($e->getMessage(), 'Connection refused') !== false) {
                $errorMessage .= "Something wrong with MySQL server. Please check it.";
            } elseif (strpos($e->getMessage(), 'Access denied') !== false) {
                $errorMessage .= "Must be something wrong with username or password.";
            } elseif (strpos($e->getMessage(), 'Unknown MySQL server host') !== false) {
                $errorMessage .= "Umm... Are you sure about your database host? Something wrong with it. I can smell it.";
            } else {
                $errorMessage .= $e->getMessage();
            }
            
            throw new Exception($errorMessage);
        }
    }


    private function createDatabaseIfNeeded(array $dbConfig): void {
        try {
            $this->pdo->exec("USE `{$dbConfig['name']}`");
            
        } catch (PDOException $e) {
            try {
                $sql = "CREATE DATABASE `{$dbConfig['name']}` 
                        CHARACTER SET utf8mb4 
                        COLLATE utf8mb4_unicode_ci";
                
                $this->pdo->exec($sql);
                
                $this->pdo->exec("USE `{$dbConfig['name']}`");
                
            } catch (PDOException $e) {
                throw new Exception("Failed to create database '{$dbConfig['name']}': " . $e->getMessage());
            }
        }
    }
    

    private function createDatabaseTables(): void {
        $tables = [
            'users' => $this->getUsersTableSQL(),
            'songs' => $this->getSongsTableSQL(),
            'playlists' => $this->getPlaylistsTableSQL(),
            'playlist_songs' => $this->getPlaylistSongsTableSQL(),
            'config' => $this->getConfigTableSQL(),
            'user_sessions' => $this->getUserSessionsTableSQL()
        ];
        
        foreach ($tables as $tableName => $sql) {
            try {
                $this->pdo->exec($sql);
            } catch (PDOException $e) {
                throw new Exception("Failed to create table '{$tableName}': " . $e->getMessage());
            }
        }
    }
    
    private function getUsersTableSQL(): string {
        return "
            CREATE TABLE IF NOT EXISTS users (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                email VARCHAR(100) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                user_type ENUM('admin', 'artist', 'general') DEFAULT 'general',
                status ENUM('pending', 'approved', 'banned', 'suspended') DEFAULT 'pending',
                display_name VARCHAR(100) NULL,
                profile_image VARCHAR(255) NULL,
                bio TEXT NULL,
                is_private BOOLEAN DEFAULT FALSE,
                email_verified BOOLEAN DEFAULT FALSE,
                discord_id VARCHAR(100) NULL UNIQUE,
                discord_username VARCHAR(100) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                last_login_at TIMESTAMP NULL,
                
                INDEX idx_username (username),
                INDEX idx_email (email),
                INDEX idx_user_type (user_type),
                INDEX idx_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
    }
    
    private function getSongsTableSQL(): string {
        return "
            CREATE TABLE IF NOT EXISTS songs (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                artist_id INT UNSIGNED NOT NULL,
                album VARCHAR(255) NULL,
                genre VARCHAR(100) NULL,
                year INT NULL,
                track_number INT NULL,
                disc_number INT DEFAULT 1,
                duration INT NOT NULL,
                file_path VARCHAR(500) NOT NULL,
                file_size BIGINT UNSIGNED NOT NULL,
                mime_type VARCHAR(100) NOT NULL,
                bitrate INT NULL,
                sample_rate INT NULL,
                cover_image VARCHAR(255) NULL,
                is_public BOOLEAN DEFAULT FALSE,
                is_featured BOOLEAN DEFAULT FALSE,
                allow_download BOOLEAN DEFAULT TRUE,
                play_count INT UNSIGNED DEFAULT 0,
                like_count INT UNSIGNED DEFAULT 0,
                upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                approved_by INT UNSIGNED NULL,
                approved_at TIMESTAMP NULL,
                
                FOREIGN KEY (artist_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
                
                INDEX idx_artist_id (artist_id),
                INDEX idx_title (title),
                INDEX idx_album (album),
                INDEX idx_genre (genre),
                INDEX idx_status (status),
                INDEX idx_is_public (is_public),
                FULLTEXT INDEX idx_search (title, album, genre)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
    }
    
    private function getPlaylistsTableSQL(): string {
        return "
            CREATE TABLE IF NOT EXISTS playlists (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT NULL,
                user_id INT UNSIGNED NOT NULL,
                is_public BOOLEAN DEFAULT FALSE,
                is_collaborative BOOLEAN DEFAULT FALSE,
                cover_image VARCHAR(255) NULL,
                play_count INT UNSIGNED DEFAULT 0,
                like_count INT UNSIGNED DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                
                INDEX idx_user_id (user_id),
                INDEX idx_name (name),
                INDEX idx_is_public (is_public)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
    }
    

    private function getPlaylistSongsTableSQL(): string {
        return "
            CREATE TABLE IF NOT EXISTS playlist_songs (
                playlist_id INT UNSIGNED,
                song_id INT UNSIGNED,
                position INT UNSIGNED DEFAULT 0,
                added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                added_by INT UNSIGNED NULL,
                
                PRIMARY KEY (playlist_id, song_id),
                
                FOREIGN KEY (playlist_id) REFERENCES playlists(id) ON DELETE CASCADE,
                FOREIGN KEY (song_id) REFERENCES songs(id) ON DELETE CASCADE,
                FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE SET NULL,
                
                INDEX idx_position (position)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
    }
    

    private function getConfigTableSQL(): string {
        return "
            CREATE TABLE IF NOT EXISTS config (
                key_name VARCHAR(100) PRIMARY KEY,
                key_value TEXT NULL,
                description VARCHAR(500) NULL,
                data_type ENUM('string', 'integer', 'boolean', 'json') DEFAULT 'string',
                is_editable BOOLEAN DEFAULT TRUE,
                category VARCHAR(50) DEFAULT 'general',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                INDEX idx_category (category)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
    }
    

    private function getUserSessionsTableSQL(): string {
        return "
            CREATE TABLE IF NOT EXISTS user_sessions (
                id VARCHAR(128) PRIMARY KEY,
                user_id INT UNSIGNED NOT NULL,
                ip_address VARCHAR(45) NOT NULL,
                user_agent TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                expires_at TIMESTAMP NOT NULL,
                is_active BOOLEAN DEFAULT TRUE,
                
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                
                INDEX idx_user_id (user_id),
                INDEX idx_expires_at (expires_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
    }
    
    private function createAdminAccount(array $adminData): void {
        try {
            $passwordHash = password_hash($adminData['password'], PASSWORD_DEFAULT);
            
            $sql = "
                INSERT INTO users (
                    username, 
                    email, 
                    password_hash, 
                    user_type, 
                    status,
                    display_name,
                    email_verified,
                    created_at
                ) VALUES (?, ?, ?, 'admin', 'approved', ?, 1, NOW())
            ";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $adminData['username'],
                $adminData['email'],
                $passwordHash,
                $adminData['username']
            ]);
            
        } catch (PDOException $e) {
            throw new Exception("Failed to create admin account: " . $e->getMessage());
        }
    }
    
    private function saveConfiguration(array $formData): void {
        $configs = [
            ['server_name', $formData['server']['name'], 'Server display name', 'string', 'server'],
            ['max_upload_size', $formData['server']['max_upload_size'], 'Maximum file upload size in MB', 'integer', 'uploads'],
            ['allow_registration', $formData['server']['allow_registration'] ? '1' : '0', 'Allow new user registration', 'boolean', 'users'],
            ['require_user_approval', $formData['server']['require_approval'] ? '1' : '0', 'Require admin approval for new users', 'boolean', 'users'],
            ['require_artist_approval', $formData['server']['require_artist_approval'] ? '1' : '0', 'Require admin approval for artist accounts', 'boolean', 'users'],
            ['allow_public_music', $formData['server']['allow_public_music'] ? '1' : '0', 'Allow public music sharing', 'boolean', 'music'],
            
            ['db_host', $formData['database']['host'], 'Database host', 'string', 'database'],
            ['db_port', $formData['database']['port'], 'Database port', 'integer', 'database'],
            ['db_name', $formData['database']['name'], 'Database name', 'string', 'database'],
            ['db_user', $formData['database']['username'], 'Database username', 'string', 'database'],
            
            ['discord_enabled', $formData['api']['discord_enabled'] ? '1' : '0', 'Enable Discord OAuth login', 'boolean', 'external'],
            ['discord_client_id', $formData['api']['discord_client_id'], 'Discord Client ID', 'string', 'external'],
            ['discord_client_secret', $formData['api']['discord_client_secret'], 'Discord Client Secret', 'string', 'external'],
            ['smtp_enabled', $formData['api']['smtp_enabled'] ? '1' : '0', 'Enable SMTP email sending', 'boolean', 'external'],
            ['smtp_host', $formData['api']['smtp_host'], 'SMTP server host', 'string', 'external'],
            ['smtp_port', $formData['api']['smtp_port'], 'SMTP server port', 'integer', 'external'],
            ['smtp_username', $formData['api']['smtp_username'], 'SMTP username', 'string', 'external'],
            ['smtp_password', $formData['api']['smtp_password'], 'SMTP password', 'string', 'external'],
            ['smtp_encryption', $formData['api']['smtp_encryption'], 'SMTP encryption method', 'string', 'external'],
            
            ['allowed_extensions', 'mp3,wav,flac,m4a,ogg,aac', 'Allowed audio file extensions', 'string', 'uploads'],
            ['default_music_visibility', 'private', 'Default music visibility for new uploads', 'string', 'music'],
            ['enable_streaming', '1', 'Enable music streaming', 'boolean', 'music'],
            ['streaming_quality', '192', 'Default streaming quality in kbps', 'integer', 'music'],
            ['session_lifetime', '7200', 'User session lifetime in seconds', 'integer', 'security'],
            ['min_password_length', '6', 'Minimum password length', 'integer', 'security'],
            ['setup_completed', '1', 'Whether initial setup is completed', 'boolean', 'system'],
            ['setup_date', date('Y-m-d H:i:s'), 'Date when setup was completed', 'string', 'system']
        ];
        
        $stmt = $this->pdo->prepare("
            INSERT INTO config (key_name, key_value, description, data_type, category) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        foreach ($configs as $config) {
            $stmt->execute($config);
        }
    }
    
    private function createDirectories(): void {
        $directories = [
            'public/assets/uploads',
            'public/assets/uploads/music',
            'public/assets/uploads/images',
            'public/assets/uploads/covers',
            'storage/logs',
            'storage/cache',
            'storage/temp',
            'mysql-dumps'
        ];
        
        foreach ($directories as $dir) {
            $fullPath = __DIR__ . '/../../' . $dir;
            
            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
                
                if (strpos($dir, 'uploads') !== false) {
                    $htaccessContent = [
                        '# Prevent direct access to uploaded files',
                        'Options -Indexes',
                        'Options -ExecCGI',
                        '<Files *.php>',
                        '    Deny from all',
                        '</Files>',
                        '<Files *.phtml>',
                        '    Deny from all',
                        '</Files>',
                        '<Files *.inc>',
                        '    Deny from all',
                        '</Files>'
                    ];
                    file_put_contents($fullPath . '/.htaccess', implode("\n", $htaccessContent));
                }
            }
        }
    }
    
    private function generateEnvFile(array $formData): void {
        $envContent = [
            '# Setup Generated Time: ' . date('Y-m-d H:i:s'),
            '',
            '# Application Configuration',
            'APP_NAME="' . addslashes($formData['server']['name']) . '"',
            'APP_ENV=production',
            'APP_DEBUG=false',
            'APP_URL=' . $this->getCurrentUrl(),
            '',
            '# Database Configuration',
            'DB_HOST=' . $formData['database']['host'],
            'DB_PORT=' . $formData['database']['port'],
            'DB_NAME=' . $formData['database']['name'],
            'DB_USER=' . $formData['database']['username'],
            'DB_PASSWORD="' . addslashes($formData['database']['password']) . '"',
            '',
            '# Server Configuration',
            'SERVER_NAME="' . addslashes($formData['server']['name']) . '"',
            'MAX_UPLOAD_SIZE=' . $formData['server']['max_upload_size'],
            'ALLOW_REGISTRATION=' . ($formData['server']['allow_registration'] ? 'true' : 'false'),
            'REQUIRE_USER_APPROVAL=' . ($formData['server']['require_approval'] ? 'true' : 'false'),
            'REQUIRE_ARTIST_APPROVAL=' . ($formData['server']['require_artist_approval'] ? 'true' : 'false'),
            'ALLOW_PUBLIC_MUSIC=' . ($formData['server']['allow_public_music'] ? 'true' : 'false'),
            '',
            '# Discord OAuth Configuration',
            'DISCORD_ENABLED=' . ($formData['api']['discord_enabled'] ? 'true' : 'false'),
            'DISCORD_CLIENT_ID="' . addslashes($formData['api']['discord_client_id']) . '"',
            'DISCORD_CLIENT_SECRET="' . addslashes($formData['api']['discord_client_secret']) . '"',
            '',
            '# SMTP Configuration',
            'SMTP_ENABLED=' . ($formData['api']['smtp_enabled'] ? 'true' : 'false'),
            'SMTP_HOST="' . addslashes($formData['api']['smtp_host']) . '"',
            'SMTP_PORT=' . $formData['api']['smtp_port'],
            'SMTP_USERNAME="' . addslashes($formData['api']['smtp_username']) . '"',
            'SMTP_PASSWORD="' . addslashes($formData['api']['smtp_password']) . '"',
            'SMTP_ENCRYPTION=' . $formData['api']['smtp_encryption'],
            '',
            '# Security',
            'SESSION_LIFETIME=7200', // Have to ask sir.. is this okay?
            'MIN_PASSWORD_LENGTH=6',
            '',
            '# Features',
            'ALLOWED_EXTENSIONS=mp3,wav,flac,m4a,ogg,aac',
            'DEFAULT_MUSIC_VISIBILITY=private',
            'ENABLE_STREAMING=true',
            'STREAMING_QUALITY=192'
        ];
        
        $envPath = __DIR__ . '/../../.env';
        file_put_contents($envPath, implode("\n", $envContent));
        
        chmod($envPath, 0600); // only owner can read/write ;/
    }
    
    private function getCurrentUrl(): string {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host;
    }
    
    private function markSetupCompleted(): void {
        $stmt = $this->pdo->prepare("
            UPDATE config 
            SET key_value = '1' 
            WHERE key_name = 'setup_completed'
        ");
        $stmt->execute();
    }
    
    private function showSuccess(array $formData): void {
        include __DIR__ . '/../UI/setup-complete.php';
    }
    
    private function showError(string $message): void {
        include __DIR__ . '/../UI/setup-error.php';
    }
    
    private function redirect(string $url): void {
        header('Location: ' . $url);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $setupController = new SetupController();
    $setupController->processSetup();
}