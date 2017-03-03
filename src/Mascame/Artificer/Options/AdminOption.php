<?php

namespace Mascame\Artificer\Options;

class AdminOption extends Option
{
    public static $key = 'admin';

    /**
     * @param string $key
     */
    public static function get($key = null)
    {
        return Option::get(self::$key.'.'.$key);
    }

    public static function has($key = '')
    {
        return Option::has(self::$key.'.'.$key);
    }

    public static function all($key = null)
    {
        if (! $key) {
            $key = self::$key;
        }

        return Option::get($key);
    }

    /**
     * @param string $key
     */
    public static function set($key, $value)
    {
        Option::set(self::$key.'.'.$key, $value);
    }
}
