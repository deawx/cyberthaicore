<?php
#       ╔═══════════════════════════════════════════╗
#          Cyberthai.php Core System 2023
#          Author : (Deawx) Tirapong Chaiyakun
#          Tel.089-0499359
#          EMail: msdos43@gmail.com
#          Website: https://www.cyberthai.net
#          PHP THAILAND CODING. MADE EASY AND FUN.
#       ╚═══════════════════════════════════════════╝
namespace Cyberthai\helper;

class Sitehelper
{
    protected static $months = array(
        'thaimonth' => array(
            'มกราคม',
            'กุมภาพันธ์',
            'มีนาคม',
            'เมษายน',
            'พฤษภาคม',
            'มิถุนายน',
            'กรกฎาคม',
            'สิงหาคม',
            'กันยายน',
            'ตุลาคม',
            'พฤศจิกายน',
            'ธันวาคม',
        )
    );

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
