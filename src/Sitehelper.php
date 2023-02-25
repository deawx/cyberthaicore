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
class Sitehelper
{

    public function get_token($custom_token_name = "")
    {
        $token = empty($custom_token_name) ? \Volnix\CSRF\CSRF::getHiddenInputString() : \Volnix\CSRF\CSRF::getHiddenInputString($custom_token_name);
        echo $token;
    }

    public function check_token($POST, $custom_token_name = "")
    {
        $chktoken = empty($custom_token_name) ? \Volnix\CSRF\CSRF::validate($_POST) : \Volnix\CSRF\CSRF::validate($_POST, $custom_token_name);
        return $chktoken;
    }

    public function inputhidden($array)
    {
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $keys => $vals) {
                echo "<input type='hidden' name='" . trim($keys) . "' value='" . trim($vals) . "'>";
            }
        }

        return false;
    }

    public function dd($var, $pretty = false)
    {
        $backtrace = debug_backtrace();
        echo "\n<pre>\n";
        echo str_repeat("-", 50) . "\n";
        if (isset($backtrace[0]['file'])) {
            echo $backtrace[0]['file'] . "\n";
        }
        echo str_repeat("-", 50) . "\n";
        echo "Type: " . gettype($var) . "\n";
        echo "Time: " . date('c') . "\n";
        echo str_repeat("-", 50) . "\n\n";
        ($pretty) ? print_r($var) : var_dump($var);
        echo "</pre>\n";
        exit();
    }

    public function pre()
    {
        array_map(function ($x) {
            echo "<pre>";
            print_r($x);
            echo "</pre>";
        }, func_get_args());
    }
}
