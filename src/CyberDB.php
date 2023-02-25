<?php

namespace Cyberthai;
#       ╔══════════════════════════════════╗->╗
#       ║  Cyberthai.php Core System 2022                             ║->║
#       ║  Author : (Deawx) Tirapong Chaiyakun                      ║
#       ║  Tel.089-0499359                                                    ║->║
#       ║  EMail: msdos43@gmail.com                                    ║->║
#       ║  Website: https://www.cyberthai.net                         ║
#       ║  THAILAND PHP CODING. MADE EASY AND FUN.       ║->║
#       ╚══════════════════════════════════╝->╝
use Medoo\Medoo;

class CyberDB
{
    public function connect()
    {
        try {
            $db = new medoo([
                "type" => DB_TYPE,
                "database" => DB_NAME,
                "host" => DB_HOST,
                "username" => DB_USERNAME,
                "password" => DB_PASSWORD,
                "port" => DB_PORT,
                "charset" => DB_CHARSET,
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
