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
use FilesystemIterator;
use InvalidArgumentException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class Filehelper
{
    public static function size($dir)
    {
        $size = 0;
        $flags = FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS;
        $dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, $flags));

        foreach ($dirIterator as $splFileInfo) {
            if ($splFileInfo->isFile()) {
                $size += $splFileInfo->getSize();
            }
        }
        return (int)$size;
    }

    public static function ls($dir)
    {
        if (!is_dir($dir)) {
            throw new InvalidArgumentException("The dir argument must be a directory: $dir");
        }
        $flags = FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS;
        $dirIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, $flags));
        $contents    = [];

        foreach ($dirIterator as $splFileInfo) {
            $contents[] = $splFileInfo->getPathname();
        }

        if ($contents) {
            natsort($contents);
        }
        return $contents;
    }

    public static function format($bytes, $decimals = 2)
    {
        $exp     = 0;
        $value   = 0;
        $symbols = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $bytes   = (float)$bytes;
        if ($bytes > 0) {
            $exp   = (int)floor(log($bytes) / log(1024));
            $value = ($bytes / (1024 ** floor($exp)));
        }
        if ($symbols[$exp] === 'B') {
            $decimals = 0;
        }
        return number_format($value, $decimals, '.', '') . ' ' . $symbols[$exp];
    }

    public static function ext($path)
    {
        if (!$path) {
            return '';
        }
        if (strpos($path, '?') !== false) {
            $path = (string)preg_replace('#\?(.*)#', '', $path);
        }
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        return strtolower($ext);
    }

    public static function base($path)
    {
        return pathinfo((string)$path, PATHINFO_BASENAME);
    }

    public static function filename($path)
    {
        return pathinfo((string)$path, PATHINFO_FILENAME);
    }

    public static function dirName($path)
    {
        return pathinfo((string)$path, PATHINFO_DIRNAME);
    }

    public static function real($path)
    {
        if (!$path) {
            return null;
        }
        $result = realpath($path);
        return $result ?: null;
    }

    public static function isFile($filename)
    {
        return file_exists($filename) && is_file($filename);
    }

    public static function normalizePath($path)
    {
        $parts = $path === '' ? [] : preg_split('~[/\\\\]+~', $path);
        $res   = [];
        foreach ($parts as $part) {
            if ($part === '..' && $res && end($res) !== '..' && end($res) !== '') {
                array_pop($res);
            } elseif ($part !== '.') {
                $res[] = $part;
            }
        }
        return $res === [''] ? DIRECTORY_SEPARATOR : implode(DIRECTORY_SEPARATOR, $res);
    }

    public static function createDir($dir, $mode = 0777)
    {
        if (!is_dir($dir) && !@mkdir($dir, $mode, true) && !is_dir($dir)) {

            throw new \ErrorException(sprintf(
                "Unable to create directory '%s' with mode %s.",
                static::normalizePath($dir),
                decoct($mode)
            ));
        }
    }

    public static function copy($origin, $target, $overwrite = true)
    {
        if (stream_is_local($origin) && !file_exists($origin)) {
            throw new \ErrorException(sprintf("File or directory '%s' not found.", static::normalizePath($origin)));
        } elseif (!$overwrite && file_exists($target)) {
            throw new \ErrorException(sprintf("File or directory '%s' already exists.", static::normalizePath($target)));
        } elseif (is_dir($origin)) {
            static::createDir($target);
            foreach (new \FilesystemIterator($target) as $item) {
                static::delete($item->getPathname());
            }

            foreach ($iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($origin, RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $item) {
                if ($item->isDir()) {
                    static::createDir($target . '/' . $item->getSubPathName());
                } else {
                    static::copy($item->getPathname(), $target . '/' . $item->getSubPathName());
                }
            }
        } else {
            static::createDir(dirname($target));
            if (($s = @fopen($origin, 'rb')) && ($d = @fopen($target, 'wb')) && @stream_copy_to_stream($s, $d) === false) {
                throw new \ErrorException(sprintf("Unable to copy file '%s' to '%s'. ", static::normalizePath($origin), static::normalizePath($target)));
            }
        }
    }

    public static function delete($path)
    {
        if (is_file($path) || is_link($path)) {
            $func = DIRECTORY_SEPARATOR === '\\' && is_dir($path) ? 'rmdir' : 'unlink';
            if (!@$func($path)) {
                throw new \ErrorException(sprintf(
                    "Unable to delete '%s'.",
                    static::normalizePath($path)
                ));
            }
        } elseif (is_dir($path)) {
            foreach (new \FilesystemIterator($path) as $item) {
                static::delete($item->getPathname());
            }

            if (!@rmdir($path)) {
                throw new \ErrorException(sprintf("Unable to delete directory '%s'.", static::normalizePath($path)));
            }
        }
    }

    public static function rename($origin, $target, $overwrite = true)
    {
        if (!$overwrite && file_exists($target)) {
            throw new \ErrorException(sprintf("File or directory '%s' already exists.", static::normalizePath($target)));
        } elseif (!file_exists($origin)) {
            // return false;
            throw new \ErrorException(sprintf("File or directory '%s' not found.", static::normalizePath($origin)));
        } else {
            static::createDir(dirname($target));
            if (realpath($origin) !== realpath($target)) {
                static::delete($target);
            }
            if (!@rename($origin, $target)) {
                throw new \ErrorException(sprintf("Unable to rename file or directory '%s' to '%s'.", static::normalizePath($origin), static::normalizePath($target)));
            }
        }
    }

    public static function read($file)
    {
        $content = @file_get_contents($file);
        if ($content === false) {
            throw new \ErrorException(sprintf("Unable to read file '%s'. ", static::normalizePath($file)));
        }
        return $content;
    }

    public static function write($file, $content, $mode = 0666)
    {
        static::createDir(dirname($file));
        if (@file_put_contents($file, $content) === false) {
            throw new \ErrorException(sprintf("Unable to write file '%s'.", static::normalizePath($file)));
        }

        if ($mode !== null && !@chmod($file, $mode)) {
            throw new \ErrorException(sprintf("Unable to chmod file '%s' to mode %s.", static::normalizePath($file), decoct($mode)));
        }
    }

    public static function unlink($path)
    {
        try {
            return is_file($path) && unlink($path);
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function rmdir($dirname, $unlink = true)
    {
        if (!is_dir($dirname)) {
            return false;
        }
        $items = new FilesystemIterator($dirname);
        foreach ($items as $item) {
            if ($item->isDir() && !$item->isLink()) {
                static::rmdir($item->getPathname());
            } else {
                $unlink && static::unlink($item->getPathname());
            }
        }
        @rmdir($dirname);
        return true;
    }
}
