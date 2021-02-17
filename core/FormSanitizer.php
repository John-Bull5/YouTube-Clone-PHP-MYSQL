<?php

class FormSanitizer
{
    public static function santizeFormString($string)
    {
        $string = strip_tags($string);
        $string = str_replace(" ", "", $string);
        $string = strtolower($string);
        $string = ucfirst($string);
        return $string;
    }
    public static function santizeFormUserName($string)
    {
        $string = strip_tags($string);
        $string = str_replace(" ", "", $string);

        return $string;
    }
    public static function santizeFormEmail($string)
    {
        $string = strip_tags($string);

        return $string;
    }
    public static function santizeFormPassword($string)
    {
        $string = strip_tags($string);

        return $string;
    }
}
