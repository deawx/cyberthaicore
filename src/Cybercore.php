<?php

declare(strict_types=1);
#       ╔═══════════════════════════════════════════╗
#          Cyberthai.php Core System 2023
#          Author : (Deawx) Tirapong Chaiyakun
#          Tel.089-0499359
#          EMail: msdos43@gmail.com
#          Website: https://www.cyberthai.net
#          PHP THAILAND CODING. MADE EASY AND FUN.
#       ╚═══════════════════════════════════════════╝
namespace Cyberthai;

class Cybercore
{
    private $db;
    public  function __construct($db)
    {
        $this->db = $db;
    }

    // Load Modules
    public function Getmodules(string $modulename = "", string $file = "")
    {
        $modulename = empty($modulename) ? "index" : trim($modulename);
        $files = empty($file) ? "index" : trim($file);
        $modpathfile = "modules/" . $modulename . "/" . $files . ".php";
        $fileinclude = file_exists($modpathfile) ? $modpathfile : "modules/error/404.php";
        return $fileinclude;
    }

    // Check Is Login
    public function CheckAdmin(string $username = "", string $pwd = "")
    {
        if (empty($username) || empty($pwd)) {
            echo "<script>window.location='login.html';</script>";
            exit();
        } else {
            if (!$this->ChkhasAdminInDB($username, $pwd)) {
                echo "<script>window.location='login.html';</script>";
                exit();
            }
        }
    }

    private function ChkhasAdminInDB(string $username = "", string $pwd = "")
    {
        if (!empty($username) || !empty($pwd)) {
            $hasindb = $this->db->has("web_admin", [
                "AND" => [
                    "username" => $username,
                    "password" => $pwd,
                ]
            ]);
            return $hasindb;
        }
        return false;
    }

    #สำหรับเชคว่ามีสิทธิ์ไหมถ้าไม่มีไปหน้า 404
    public function CheckLevel(string $Action = "")
    {
        $checklevel = $this->db->get("web_groups", $Action, [
            "id" => $_SESSION['admin_level'],
        ]);

        if ($checklevel == 1) {
            return true;
        } else {
            echo "<script> loadpage('error/404.html')</script>";
            exit();
        }
    }

    #สำหรับเชคว่ามีสิทธิ์ไหมถ้าไม่มีไม่ต้องแสดง
    public function checkOP($Action = "")
    {
        $checkop = $this->db->get("web_groups", $Action, [
            "id" => $_SESSION['admin_level'],
        ]);
        if ($checkop == 1) {
            return true;
        }
    }

    #Get GROUP name
    public function getgroupuser($levelid = "")
    {
        if ($levelid) {
            $groupname = $this->db->get("web_groups", "groupname", [
                "id" => $_SESSION['admin_level'],
            ]);
            return $groupname;
        }
    }

    #Get Name User insert Picture
    public function getuname($userid = "")
    {
        if ($userid) {
            $u = $this->db->get("user", ["pname", "sname"], [
                "id" => $userid,
            ]);
            return $u['pname'] . " " . $u['sname'];
        }
    }

    public static function getip()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        return $realip;
    }

    public function savelog($detail, $memid = "", $ip = "")
    {
        if (!empty($detail)) {
            $this->db->insert("web_history", [
                "memid" => $_SESSION['admin_id'],
                "ipaddress" => $this->getip(),
                "detail" => trim($detail),
            ]);
        }
    }

    public function getBaseUrl($atRoot = false, $atCore = false, $parse = false)
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $isSecure = false;
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                $isSecure = true;
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
                $isSecure = true;
            }
            $http = $isSecure ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), 0, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];
            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf($tmplt, $http, $hostname, $end);
        }

        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) {
                if ($base_url['path'] == '/') {
                    $base_url['path'] = '';
                }
            }
        }
        return $base_url;
    }
}
