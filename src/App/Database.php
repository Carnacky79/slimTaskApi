<?php
declare(strict_types=1);

namespace App;
use PDO;
class Database
{
    public function __construct(private string $host,
                                private string $db,
                                private string $user,
                                private string $password)
    {

    }
    public function getConnection(): PDO
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db";
        $pdo = new PDO($dsn, $this->user, $this->password);

        return $pdo;
    }
}
