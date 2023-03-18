<?php
#       ╔══════════════════════════════════╗
#             Cyberthai.php Core System 2022
#             Author : (Deawx) Tirapong Chaiyakun
#             Tel.089-0499359
#             EMail: msdos43@gmail.com
#             Website: https://www.cyberthai.net
#             THAILAND PHP CODING. MADE EASY AND FUN.
#       ╚══════════════════════════════════╝
namespace Cyberthai\helper;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;

class Qrhelper
{
    public function genqr($str, $size = 30)
    {
        $result =   Builder::create()
            ->writer(new PngWriter())
            ->data($str)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size($size)
            ->margin(0)
            ->build();
        $resulturi = $result->getDataUri();
        return $resulturi;
    }
}
