<?php

namespace Mascame\Artificer\Options;

use Config;

class Option
{
    public $options;

    public static $config_path = 'artificer::';

    public static $subkey = null;

    public static function get($key = '')
    {
        return Config::get(self::$config_path.$key);
    }

    public static function has($key = '')
    {
        return Config::has(self::$config_path.$key);
    }

    public static function all($key = null)
    {
        return self::get(self::$config_path.$key);
    }

    /**
     * @param string $key
     */
    public static function set($key, $value)
    {
        Config::set(self::$config_path.$key, $value);
    }
}
