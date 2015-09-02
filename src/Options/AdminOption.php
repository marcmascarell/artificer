<?php namespace Mascame\Artificer\Options;

class AdminOption extends Option
{

    /**
     * @var string
     */
    public static $key = 'admin';

    /**
     * @param null $key
     * @return mixed
     */
    public static function get($key = null)
    {
        return Option::get(self::$key . '.' . $key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function has($key = '')
    {
        return Option::has(self::$key . '.' . $key);
    }

    /**
     * @param null $key
     * @return mixed
     */
    public static function all($key = null)
    {
        if (!$key) {
            $key = self::$key;
        }

        return Option::get($key);
    }

    /**
     * @param string $key
     * @param $value
     */
    public static function set($key, $value)
    {
        Option::set(self::$key . '.' . $key, $value);
    }
}