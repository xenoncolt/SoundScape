<?php
namespace App\Database;

use PDO;
use PDOException;
use Exception;
use PHPUnit\Event\Runtime\PHP;

class Connection {
    private static ?self $instance = null;
    private ?PDO $db = null;

    private readonly string $host;
    private readonly int $port;
    private readonly string $dbname;
    private readonly string $username;
    private readonly string $password;
    private readonly string $charset;

    private function __construct() {
        $this->loadConfig();
        $this->connect();
    }

    public static function getInstance(): self {
        return self::$instance ??= new self();
    }

    private function loadConfig() {
        if ($this->tryLoadFromDB()) {
            return;
        }
        
        $this->loadFromEnv();  // 1st try dbconfig then env then default
    }

    private function tryLoadFromDB() {
        try {
            if (!$this->isSetupMode()) {
                return false;
            }

            return false;
        } catch (Exception) {
            return false;
        }
    }

    private function isSetupMode()  {
        return !file_exists(__DIR__ . '/../../.env') || !isset($_ENV['SETUP_COMPLETE']) || $_ENV['SETUP_COMPLETE'] !== 'true';
    }

    private function loadEnvFile() {
        $envFile = __DIR__ . '/../../.env';

        if (!is_readable($envFile)) {
            return;
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) {  // for comments
                continue;
            }

            if (str_contains($line, '=')) { // this is divided KEY=VALUE
                [$key, $value] = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value);
                
                $_ENV[$key] = $value;
            }
        }
    }

    private function loadFromEnv() {
        $this->loadEnvFile();
        $this -> host = $_ENV['DB_HOST'] ?? 'localhost';
        $this -> port = $_ENV['DB_PORT'] ?? 3306;
        $this -> dbname = $_ENV['DB_NAME'] ?? 'soundscape';
        $this -> password = $_ENV['DB_PASSWORD'] ?? '';
        $this -> username = $_ENV['DB_USERNAME'] ?? 'root';
        $this -> charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
    }

    private function connect() {
        try {
            $dbs = sprintf(
                'mysql:host=%s;port=%d;charset=%s',

                $this -> host,
                $this -> port,                
                $this -> charset
            );

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_TIMEOUT => 10
            ];

            $tempDB = new PDO($dbs, $this->username, $this->password, $options);

            try {
                $tempDB->exec("CREATE DATABASE IF NOT EXISTS `{$this->dbname}` CHARACTER SET {$this->charset} COLLATE {$this->charset}_unicode_ci");
                $tempDB->exec("USE `{$this->dbname}`");
            } catch (PDOException $e) {
                error_log("Database creation/selection failed: " . $e->getMessage());
            }

            $dbs = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $this->host,
                $this->port,
                $this->dbname,
                $this->charset
            );


            $this  -> db = new PDO($dbs, $this -> username, $this-> password, $options);
            $this ->db ->exec("SET NAMES {$this->charset} COLLATE {$this->charset}_unicode_ci");
            $this-> db->exec("SET sql_mode=(SELECT CONCAT(@@sql_mode,',ONLY_FULL_GROUP_BY'))"); // Better SQL mode for strictness.. got this on PDO docs
        } catch (PDOException $e) {
            $this -> handleConnectionErr($e);
            // echo $e->getMessage(); i hate u not usefull.. waste my time :(((((((
        }
    }

    private function handleConnectionErr(PDOException $e) {
        $errMsg = match(true) {
            str_contains($e->getMessage(), 'Unknown database') => $this -> tryCreateDB(),
            str_contains($e->getMessage(), 'Connection refused') => 'Something wrong with MySQL server. Maybe not running or wrong host/port',
            str_contains($e->getMessage(), 'Access denied') => 'Wrong username/password or invalid credentials',
            str_contains($e->getMessage(), 'Unknown MySQL server host') => 'Wrong host name or unreachable host',
            default => 'Database connection failed: ' . $e->getMessage(),
        };

        if (is_string($errMsg)) {
            throw new Exception($errMsg);
        }
    }

    private function tryCreateDB() {
        try {
            $dbs = sprintf(
                'mysql:host=%s;port=%d;charset=%s',
                $this -> host,
                $this-> port,
                $this -> charset
            );
            $db = new PDO($dbs, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            $db->exec(sprintf(
                'CREATE DATABASE `%s` CHARACTER SET %s COLLATE %s_unicode_ci',
                $this -> dbname,
                $this -> charset,
                $this -> charset
            ));

            $this->connect();
            return null;  // all ok
        } catch (PDOException $e) {
            return 'Database create failed: ' . $e->getMessage();
        }
    }

    public function getConnection() {
        if ($this->db === null) {
            throw new Exception('Database connection not successful');
        }
        return $this->db;
    }

    public function isConnected() {
        if ($this->db === null) {
            throw new Exception('DB connection error.. something wrong when connecting to db');
        }
        return $this->db;
    }

    public function isDBOk() {
        try {
            $this->db->query('SELECT 1'); // for test
            return true;
        } catch (PDOException) {
            return false;
        }
    }

    public function __clone() {}
    public function __wakeup() {
        throw new Exception("database connection cannot be restored");
    }
}
?>