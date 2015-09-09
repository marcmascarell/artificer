<?php namespace Mascame\Artificer\Options;

use Mascame\Artificer\Model\Model;

class ModelOption extends Option
{

    public static $key = 'models/';
    public static $default_model = 'models.default_model';

    /**
     * @param string $key
     * @param null $model
     * @return mixed
     */
    public static function get($key = '', $model = null)
    {
        return AdminOption::get(self::getPrefix($model) . '.' . $key);
    }

    /**
     * @param null $model
     * @return mixed
     */
    public static function all($model = null)
    {
        return AdminOption::get(self::getPrefix($model));
    }

    /**
     * @param string $key
     * @param null $model
     * @return bool
     */
    public static function has($key = '', $model = null)
    {
        return AdminOption::has(self::getPrefix($model) . '.' . $key);
    }

    /**
     * @param null $model
     * @return mixed
     */
    public static function model($model = null)
    {
        return AdminOption::get(self::getPrefix($model));
    }

    /**
     * @param $model
     * @return null
     */
    public static function getModel($model)
    {
        return ($model) ? $model : Model::getCurrent();
    }

    /**
     * @param string $key
     * @return mixed
     */
    public static function getDefault($key = '')
    {
        $key = (isset($key) && !empty($key)) ? '.' . $key : null;

        return AdminOption::get(self::$default_model . $key);
    }

    /**
     * @param null $model
     * @return string
     */
    protected static function getPrefix($model = null)
    {
        return self::$key . self::getModel($model);
    }
}