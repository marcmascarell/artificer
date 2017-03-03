<?php

namespace Mascame\Artificer\Options;

use Mascame\Artificer\Model\Model;

class ModelOption extends Option
{
    public static $key = 'models/';
    public static $default_model = 'models.default_model';

    public static function get($key = '', $model = null)
    {
        return Option::get(self::$key.self::getModel($model).'.'.$key);
    }

    public static function all($model = null)
    {
        return Option::get(self::$key.self::getModel($model));
    }

    public static function has($key = '', $model = null)
    {
        return Option::has(self::$key.self::getModel($model).'.'.$key);
    }

    public static function model($model = null)
    {
        return Option::get(self::$key.self::getModel($model));
    }

    public static function getModel($model)
    {
        return ($model) ? $model : Model::getCurrent();
    }

    public static function getDefault($key = '')
    {
        $key = (isset($key) && ! empty($key)) ? '.'.$key : null;

        return Option::get(self::$default_model.$key);
    }
}
