<?php namespace Mascame\Artificer\Options;

use Config;
use Mascame\Artificer\Model;

class ModelOption extends Option {

	public static $key = 'models';

	public static function get($key = '', $model = null)
	{
		return Option::get(self::$key . '/' . self::getModel($model) . '.' . $key);
	}

	public static function all($model = null)
	{
		return Option::get(self::$key . '/' . self::getModel($model));
	}

	public static function has($key = '', $model = null)
	{
		return Option::has(self::$key . '/' . self::getModel($model) . '.' . $key);
	}

	public static function model($model = null)
	{
		return Option::has(self::$key . '/' . self::getModel($model));
	}

	public static function getModel($model) {
		return ($model) ? $model : Model::getCurrent();
	}
}