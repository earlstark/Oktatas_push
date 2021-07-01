<?php

namespace app;

class Utility
{
    private static $base = "";

    public static function getBase(): string {
        return static::$base;
    }

    public static function setBase(string $base) {
        static::$base = $base;
    }
}