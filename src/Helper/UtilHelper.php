<?php

namespace App\Helper;

class UtilHelper
{
    public static function isEmpty($string)
    {
        if (is_null($string) or empty($string) or "" == $string) {
            return true;
        }

        return false;
    }
}