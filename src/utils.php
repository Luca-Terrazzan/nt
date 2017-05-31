<?php
namespace Assignment\Core;

/**
 * Class for utilities.
 * @author     Luca Terrazzan <luca.terraz@gmail.com>
 */
class Utils
{
    public static function pprint($text)
    {
        echo '<pre>';
        print_r($text);
        echo '</pre>';
    }
}
