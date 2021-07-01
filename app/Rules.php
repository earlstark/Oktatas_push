<?php

namespace app;

use app\Utility;

class Rules
{
    private static $data = [];

    public static function get(string $index) {
        if (count(static::$data) == 0) {
            static::$data = json_decode(file_get_contents(Utility::getBase() . "data/rules.json"), true);
        }
        return static::$data[$index];
    }
}