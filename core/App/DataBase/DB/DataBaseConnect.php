<?php

namespace Kernel\Application\DataBase\DB;

use Kernel\Application\Configuration\Config;
use PDO;
use PDOException;

class DataBaseConnect
{
    private string $host;

    private string $user;

    private string $password;

    private string $database;

    private string $driver;

    public function __construct(Config $config)
    {
        $dbConfig = $config->get('app.database');

        if (is_null($dbConfig)) {
            throw new PDOException('Database configuration is missing.');
        }

        $this->driver = $dbConfig['driver'] ?? 'mysql';
        $this->database = $dbConfig[$this->driver]['database'] ?? '';

        $this->setDriverParams($dbConfig);
    }

    private function setDriverParams(array $dbConfig): void
    {
        $dbPath = $dbConfig[$this->driver]['sqlite_path'];

        if ($this->driver === 'mysql' || $this->driver === 'pgsql') {
            $this->host = $dbConfig[$this->driver]['host'] ?? '';
            $this->user = $dbConfig[$this->driver]['username'] ?? '';
            $this->password = $dbConfig[$this->driver]['password'] ?? '';

            if (empty($this->host) || empty($this->user) || empty($this->password)) {
                throw new PDOException("For $this->driver, host, user, and password are required.");
            }
        } elseif ($this->driver === 'sqlite') {
            $this->host = '';
            $this->user = '';
            $this->password = '';
            $this->database = $this->getDataBasePath($dbPath) ?? $this->database;
            if (! file_exists($this->database)) {
                throw new PDOException('SQLite database file not found at: '.$this->database);
            }

            if (empty($this->database)) {
                throw new PDOException('SQLite database path is required.');
            }
        } else {
            throw new PDOException("Unsupported database driver: $this->driver");
        }
    }

    public function connect(): PDO
    {
        $dsn = match ($this->driver) {
            'mysql' => "mysql:host=$this->host;dbname=$this->database;charset=utf8",
            'pgsql' => "pgsql:host=$this->host;dbname=$this->database",
            'sqlite' => "sqlite:$this->database",
            default => throw new PDOException("Unsupported database driver: $this->driver"),
        };

        try {
            $pdo = new PDO($dsn, $this->user, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            throw new PDOException('Connection failed: '.$e->getMessage());
        }
    }

    private function getDataBasePath(string $path): string
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', APP_ROOT.'/'.$path);
    }
}
