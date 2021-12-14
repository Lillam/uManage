<?php

namespace App\Printers\Html;

class HtmlPrinter
{
    /**
    * @param $asset
    * @return string
    */
    public static function getAsset($asset): string
    {
        $asset .= '?' . env('APP_VERSION');
        if (mb_strpos($asset, '.css') !== false) {
            return "<link href='$asset' rel='stylesheet' type='text/css' />";
        } else {
            return "<script src='$asset'></script>";
        }
    }
}