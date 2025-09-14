<?php

namespace App\Database;

use PDO;
use Exception;
use PDOException;

class Migration {
    private PDO $pdo;
    
    public function __construct()
    {
        $this->pdo = Connection::getInstance()->getConnection();
    }
    
    public function runMigrations(): void {
        echo "Starting database setup...\n";
        
        $this->createUsersTable();
        $this->createSongsTable();
        $this->createPlaylistsTable();
        $this->createPlaylistSongsTable();
        $this->createConfigTable();
        $this->createUserSessionsTable();
        
        echo "All database tables created successfully!\n";
    }
    
    private function createUsersTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                -- Primary Key: Unique identifier for each user
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                
                -- Basic user information
                username VARCHAR(50) NOT NULL COMMENT 'User login name',
                email VARCHAR(100) NOT NULL COMMENT 'User email address',
                password_hash VARCHAR(255) NOT NULL COMMENT 'Encrypted password',
                
                -- User permissions and status
                user_type ENUM('admin', 'artist', 'general') DEFAULT 'general' COMMENT 'User role/permissions',
                status ENUM('pending', 'approved', 'banned', 'suspended') DEFAULT 'pending' COMMENT 'Account status',
                
                -- Optional profile information
                display_name VARCHAR(100) NULL COMMENT 'Display name (can be different from username)',
                profile_image VARCHAR(255) NULL COMMENT 'Path to profile picture',
                bio TEXT NULL COMMENT 'User biography/description',
                
                -- Privacy settings
                is_private BOOLEAN DEFAULT FALSE COMMENT 'Private profile (not visible to others)',
                email_verified BOOLEAN DEFAULT FALSE COMMENT 'Email verification status',
                
                -- Discord integration (optional)
                discord_id VARCHAR(100) NULL COMMENT 'Discord user ID for OAuth login',
                discord_username VARCHAR(100) NULL COMMENT 'Discord username',
                
                -- Timestamps (automatically managed)
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When account was created',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last profile update',
                last_login_at TIMESTAMP NULL COMMENT 'Last successful login',
                
                -- Constraints: Ensure data integrity
                UNIQUE KEY unique_username (username) COMMENT 'Username must be unique',
                UNIQUE KEY unique_email (email) COMMENT 'Email must be unique',
                UNIQUE KEY unique_discord_id (discord_id) COMMENT 'Discord ID must be unique',
                
                -- Indexes: Speed up database searches
                INDEX idx_user_type (user_type) COMMENT 'Fast lookup by user type',
                INDEX idx_status (status) COMMENT 'Fast lookup by status',
                INDEX idx_created_at (created_at) COMMENT 'Fast lookup by creation date'
                
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User accounts table'
        ";
        
        $this->pdo->exec($sql);
        echo "Users table created\n";
    }
    
    private function createSongsTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS songs (
                -- Primary Key
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                
                -- Song Information
                title VARCHAR(255) NOT NULL COMMENT 'Song title',
                artist_id INT UNSIGNED NOT NULL COMMENT 'Who uploaded this song (links to users table)',
                album VARCHAR(255) NULL COMMENT 'Album name (optional)',
                genre VARCHAR(100) NULL COMMENT 'Music genre (rock, pop, etc.)',
                year INT NULL COMMENT 'Release year',
                track_number INT NULL COMMENT 'Track number in album',
                disc_number INT DEFAULT 1 COMMENT 'Disc number (for multi-disc albums)',
                
                -- File Information
                duration INT NOT NULL COMMENT 'Song length in seconds',
                file_path VARCHAR(500) NOT NULL COMMENT 'Path to actual music file',
                file_size BIGINT UNSIGNED NOT NULL COMMENT 'File size in bytes',
                mime_type VARCHAR(100) NOT NULL COMMENT 'File type (audio/mpeg, etc.)',
                bitrate INT NULL COMMENT 'Audio quality (128kbps, 320kbps, etc.)',
                sample_rate INT NULL COMMENT 'Audio sample rate (44100Hz, etc.)',
                
                -- Visual Elements
                cover_image VARCHAR(255) NULL COMMENT 'Path to album cover image',
                
                -- Permissions and Status
                is_public BOOLEAN DEFAULT FALSE COMMENT 'Can other users see this song?',
                is_featured BOOLEAN DEFAULT FALSE COMMENT 'Show on homepage?',
                allow_download BOOLEAN DEFAULT TRUE COMMENT 'Allow users to download this song?',
                
                -- Statistics (how popular is this song?)
                play_count INT UNSIGNED DEFAULT 0 COMMENT 'How many times played',
                like_count INT UNSIGNED DEFAULT 0 COMMENT 'How many users liked this',
                
                -- Upload and Approval Process
                upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When song was uploaded',
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' COMMENT 'Moderation status',
                approved_by INT UNSIGNED NULL COMMENT 'Which admin approved this song',
                approved_at TIMESTAMP NULL COMMENT 'When song was approved',
                
                -- Foreign Key Constraints (links between tables)
                FOREIGN KEY (artist_id) REFERENCES users(id) ON DELETE CASCADE COMMENT 'Link to user who uploaded',
                FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL COMMENT 'Link to approving admin',
                
                -- Indexes for Fast Searches
                INDEX idx_artist_id (artist_id) COMMENT 'Find songs by artist',
                INDEX idx_title (title) COMMENT 'Search songs by title',
                INDEX idx_album (album) COMMENT 'Find songs by album',
                INDEX idx_genre (genre) COMMENT 'Browse by genre',
                INDEX idx_year (year) COMMENT 'Browse by year',
                INDEX idx_status (status) COMMENT 'Filter by approval status',
                INDEX idx_is_public (is_public) COMMENT 'Show only public songs',
                INDEX idx_play_count (play_count) COMMENT 'Sort by popularity',
                INDEX idx_upload_date (upload_date) COMMENT 'Sort by upload date',
                
                -- Full-text search index (for searching song titles, albums, genres)
                FULLTEXT INDEX idx_search (title, album, genre) COMMENT 'Text search across song info'
                
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Music files and metadata'
        ";
        
        $this->pdo->exec($sql);
        echo "Songs table created\n";
    }
    
    private function createPlaylistsTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS playlists (
                -- Primary Key
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                
                -- Playlist Information
                name VARCHAR(255) NOT NULL COMMENT 'Playlist name (My Favorites, Road Trip Songs, etc.)',
                description TEXT NULL COMMENT 'Playlist description (optional)',
                user_id INT UNSIGNED NOT NULL COMMENT 'Who created this playlist',
                
                -- Privacy and Collaboration
                is_public BOOLEAN DEFAULT FALSE COMMENT 'Can other users see this playlist?',
                is_collaborative BOOLEAN DEFAULT FALSE COMMENT 'Can others add songs to this playlist?',
                
                -- Visual Elements  
                cover_image VARCHAR(255) NULL COMMENT 'Custom playlist cover image',
                
                -- Statistics
                play_count INT UNSIGNED DEFAULT 0 COMMENT 'How many times this playlist was played',
                like_count INT UNSIGNED DEFAULT 0 COMMENT 'How many users liked this playlist',
                
                -- Timestamps
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When playlist was created',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last modification',
                
                -- Foreign Key Constraint
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE COMMENT 'Link to playlist owner',
                
                -- Indexes
                INDEX idx_user_id (user_id) COMMENT 'Find playlists by user',
                INDEX idx_name (name) COMMENT 'Search playlists by name',
                INDEX idx_is_public (is_public) COMMENT 'Show only public playlists',
                INDEX idx_created_at (created_at) COMMENT 'Sort by creation date'
                
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User playlists'
        ";
        
        $this->pdo->exec($sql);
        echo "Playlists table created\n";
    }
    
    private function createPlaylistSongsTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS playlist_songs (
                -- Composite Primary Key (combination must be unique)
                playlist_id INT UNSIGNED,
                song_id INT UNSIGNED,
                
                -- Song order in playlist (1st, 2nd, 3rd, etc.)
                position INT UNSIGNED DEFAULT 0 COMMENT 'Order of song in playlist (0=first, 1=second, etc.)',
                
                -- When was this song added to playlist?
                added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When song was added to playlist',
                added_by INT UNSIGNED NULL COMMENT 'Who added this song (for collaborative playlists)',
                
                -- Primary Key: playlist_id + song_id must be unique
                -- This prevents adding the same song twice to the same playlist
                PRIMARY KEY (playlist_id, song_id),
                
                -- Foreign Key Constraints
                FOREIGN KEY (playlist_id) REFERENCES playlists(id) ON DELETE CASCADE COMMENT 'Link to playlist',
                FOREIGN KEY (song_id) REFERENCES songs(id) ON DELETE CASCADE COMMENT 'Link to song',
                FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE SET NULL COMMENT 'Who added the song',
                
                -- Indexes
                INDEX idx_position (position) COMMENT 'Sort songs by playlist position',
                INDEX idx_added_at (added_at) COMMENT 'Sort by when song was added'
                
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Songs in playlists (many-to-many relationship)'
        ";
        
        $this->pdo->exec($sql);
        echo "🔗 Playlist-Songs relationship table created\n";
    }

    private function createConfigTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS config (
                key_name VARCHAR(100) PRIMARY KEY COMMENT 'Setting name (unique)',
                key_value TEXT NULL COMMENT 'Setting value (can be long text)',
                description VARCHAR(500) NULL COMMENT 'What this setting does',
                data_type ENUM('string', 'integer', 'boolean', 'json') DEFAULT 'string' COMMENT 'Type of data stored',
                is_editable BOOLEAN DEFAULT TRUE COMMENT 'Can admin edit this setting?',
                category VARCHAR(50) DEFAULT 'general' COMMENT 'Setting category (server, users, security, etc.)',
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update time',
                
                -- Index for category filtering
                INDEX idx_category (category) COMMENT 'Group settings by category'
                
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='System configuration settings'
        ";
        
        $this->pdo->exec($sql);
        
        $this->insertDefaultConfig();
        
        echo "Configuration table created with default settings\n";
    }
    
    private function createUserSessionsTable(): void {
        $sql = "
            CREATE TABLE IF NOT EXISTS user_sessions (
                id VARCHAR(128) PRIMARY KEY COMMENT 'Session ID (random string)',
                user_id INT UNSIGNED NOT NULL COMMENT 'Which user owns this session',
                ip_address VARCHAR(45) NOT NULL COMMENT 'User IP address (supports IPv6)',
                user_agent TEXT NULL COMMENT 'Browser information',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When session started',
                expires_at TIMESTAMP NOT NULL COMMENT 'When session expires',
                is_active BOOLEAN DEFAULT TRUE COMMENT 'Is session still valid?',
                
                -- Foreign Key
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE COMMENT 'Link to user',
                
                -- Indexes
                INDEX idx_user_id (user_id) COMMENT 'Find sessions by user',
                INDEX idx_expires_at (expires_at) COMMENT 'Find expired sessions',
                INDEX idx_ip_address (ip_address) COMMENT 'Track sessions by IP'
                
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Active user sessions'
        ";
        
        $this->pdo->exec($sql);
        echo "User sessions table created\n";
    }
    
    private function insertDefaultConfig(): void {
        $configs = [
            ['server_name', 'SoundScape Server', 'Server display name shown in UI', 'string', 1, 'server'],
            ['server_description', 'A self-hosted music streaming server', 'Server description', 'string', 1, 'server'],
            ['setup_completed', '0', 'Whether initial setup wizard is completed', 'boolean', 0, 'system'],
            ['allow_registration', '1', 'Allow new users to register accounts', 'boolean', 1, 'users'],
            ['require_email_verification', '0', 'Require email verification for new accounts', 'boolean', 1, 'users'],
            ['default_user_type', 'general', 'Default user type for new registrations', 'string', 1, 'users'],
            ['require_user_approval', '1', 'New users need admin approval before accessing system', 'boolean', 1, 'users'],
            ['require_artist_approval', '1', 'Artist accounts need admin approval before uploading', 'boolean', 1, 'users'],
            ['max_upload_size', '50', 'Maximum file upload size in MB', 'integer', 1, 'uploads'],
            ['allowed_extensions', 'mp3,wav,flac,m4a,ogg,aac', 'Allowed audio file extensions (comma separated)', 'string', 1, 'uploads'],
            ['max_files_per_user', '100', 'Maximum files per user (0 = unlimited)', 'integer', 1, 'uploads'],
            ['allow_public_music', '1', 'Allow users to make music publicly visible', 'boolean', 1, 'music'],
            ['allow_playlist_sharing', '1', 'Allow users to share playlists publicly', 'boolean', 1, 'music'],
            ['default_music_visibility', 'private', 'Default visibility for new uploads (public/private)', 'string', 1, 'music'],
            ['enable_streaming', '1', 'Enable music streaming (vs download only)', 'boolean', 1, 'music'],
            ['streaming_quality', '192', 'Default streaming quality in kbps', 'integer', 1, 'music'], 
            ['session_lifetime', '7200', 'User session lifetime in seconds (2 hours)', 'integer', 1, 'security'],
            ['min_password_length', '6', 'Minimum password length for new accounts', 'integer', 1, 'security'],
            ['require_strong_password', '0', 'Require strong passwords (uppercase, numbers, symbols)', 'boolean', 1, 'security'],
            ['rate_limit_login', '5', 'Maximum login attempts per minute per IP', 'integer', 1, 'security'],
            ['rate_limit_upload', '10', 'Maximum uploads per minute per user', 'integer', 1, 'security'],
            ['discord_enabled', '0', 'Enable Discord OAuth login', 'boolean', 1, 'external'],
            ['smtp_enabled', '0', 'Enable SMTP email sending', 'boolean', 1, 'external'],
            ['enable_comments', '1', 'Allow comments on songs and playlists', 'boolean', 1, 'features'],
            ['enable_likes', '1', 'Allow users to like songs and playlists', 'boolean', 1, 'features'],
            ['enable_following', '1', 'Allow users to follow other users', 'boolean', 1, 'features'],
        ];
        

        $stmt = $this->pdo->prepare("
            INSERT IGNORE INTO config (key_name, key_value, description, data_type, is_editable, category) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        

        foreach ($configs as $config) {
            $stmt->execute($config);
        }
    }

    public function isSetupCompleted(): bool {
        try {
            $stmt = $this->pdo->prepare("SELECT key_value FROM config WHERE key_name = 'setup_completed'");
            $stmt->execute();
            $result = $stmt->fetch();
            
            return $result && $result['key_value'] === '1';
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function markSetupCompleted(): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO config (key_name, key_value, description, data_type, category) 
            VALUES ('setup_completed', '1', 'Setup wizard completed', 'boolean', 'system') 
            ON DUPLICATE KEY UPDATE key_value = '1'
        ");
        $stmt->execute();
    }
    
    public function getConfig(string $key, $default = null){
        try {
            $stmt = $this->pdo->prepare("SELECT key_value, data_type FROM config WHERE key_name = ?");
            $stmt->execute([$key]);
            $result = $stmt->fetch();
            
            if (!$result) {
                return $default;
            }
            
            switch ($result['data_type']) {
                case 'boolean':
                    return (bool) $result['key_value'];
                case 'integer':
                    return (int) $result['key_value'];
                case 'json':
                    return json_decode($result['key_value'], true);
                default:
                    return $result['key_value'];
            }
        } catch (Exception $e) {
            return $default;
        }
    }
    
    public function setConfig(string $key, $value): void {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
        } elseif (is_array($value)) {
            $value = json_encode($value);
        }
        
        $stmt = $this->pdo->prepare("
            UPDATE config 
            SET key_value = ?, updated_at = CURRENT_TIMESTAMP
            WHERE key_name = ?
        ");
        
        $stmt->execute([$value, $key]);
    }
}