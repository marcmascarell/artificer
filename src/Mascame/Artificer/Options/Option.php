<?php namespace Mascame\Artificer\Options;

use Config;

class Option
{

    /**
     * @var
     */
    public $options;

    /**
     * @var string
     */
    public static $config_path = 'artificer::';

    /**
     * @var null
     */
    public static $subkey = null;

    /**
     * @param string $key
     * @return mixed
     */
    public static function get($key = '')
    {
        return Config::get(self::$config_path . $key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function has($key = '')
    {
        return Config::has(self::$config_path . $key);
    }

    /**
     * @param null $key
     * @return mixed
     */
    public static function all($key = null)
    {
        return Option::get(self::$config_path . $key);
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        Config::set(self::$config_path . $key, $value);
    }
}