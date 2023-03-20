<?php
#       ╔═══════════════════════════════════════════╗
#          Cyberthai.php Core System 2023
#          Author : (Deawx) Tirapong Chaiyakun
#          Tel.089-0499359
#          EMail: msdos43@gmail.com
#          Website: https://www.cyberthai.net
#          PHP THAILAND CODING. MADE EASY AND FUN.
#       ╚═══════════════════════════════════════════╝
namespace Cyberthai;

use Medoo\Medoo;
use Dotenv\Dotenv;

class CyberDB
{
    public function connect()
    {
        try {
            $_ENV = Dotenv::createMutable('.')->load();
            $db = new medoo([
                "type" => $_ENV['DB_TYPE'],
                "database" => $_ENV['DB_NAME'],
                "host" => $_ENV['DB_HOST'],
                "username" => $_ENV['DB_USERNAME'],
                "password" => $_ENV['DB_PASSWORD'],
                "port" => $_ENV['DB_PORT'],
                "charset" => $_ENV['DB_CHARSET'],
                "error" => \PDO::ERRMODE_SILENT,
                "option" => [
                    \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                ],
                "command" => [
                    "SET SQL_MODE=ANSI_QUOTES",
                ],
            ]);
            return $db;
        } catch (\PDOException $e) {
            echo "<hr><h6 class='text-danger'>" . $e->getMessage() . "</h6><hr>";
            exit();
        }
    }
}
