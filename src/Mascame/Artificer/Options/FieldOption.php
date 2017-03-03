<?php

namespace Mascame\Artificer\Options;

use Mascame\Artificer\Model\Model;

class FieldOption extends ModelOption
{
    public static $subkey = 'fields';
    public static $field;

    public static function get($key = '', $field = null)
    {
        return Option::get(self::$key.Model::getCurrent().'.'.self::$subkey.'.'.$field.'.'.$key);
    }

    public static function all($field = null)
    {
        return Option::get(self::$key.Model::getCurrent().'.'.self::$subkey);
    }

    public static function field($field = null)
    {
        return Option::get(self::$key.Model::getCurrent().'.'.self::$subkey.'.'.$field);
    }

    public static function has($key = '', $field = null)
    {
        return Option::has(self::$key.Model::getCurrent().'.'.self::$subkey.'.'.$field.'.'.$key);
    }

    public static function set($key, $value, $field = null)
    {
        Option::set(self::$key.Model::getCurrent().'.'.self::$subkey.'.'.$field.'.'.$key, $value);
    }
}
