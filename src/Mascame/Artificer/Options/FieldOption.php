<?php namespace Mascame\Artificer\Options;

use Mascame\Artificer\Model\Model;

class FieldOption extends ModelOption {

	/**
	 * @var string
	 */
	public static $subkey = 'fields';

	/**
	 * @var
	 */
	public static $field;

	/**
	 * @param string $key
	 * @param null $field
	 * @return mixed
	 */
	public static function get($key = '', $field = null)
	{
			return Option::get(self::getPrefix() . '.' . $field . '.' . $key);
	}

	/**
	 * @param null $field
	 * @return mixed
	 */
	public static function all($field = null)
	{
		return Option::get(self::getPrefix());
	}

	/**
	 * @param null $field
	 * @return mixed
	 */
	public static function field($field = null)
	{
		return Option::get(self::getPrefix() . '.' . $field);
	}

	/**
	 * @param string $key
	 * @param null $field
	 * @return bool
	 */
	public static function has($key = '', $field = null)
	{
		return Option::has(self::getPrefix() . '.' . $field . '.' . $key);
	}

	/**
	 * @param $key
	 * @param $value
	 * @param null $field
	 */
	public static function set($key, $value, $field = null)
	{
		Option::set(self::getPrefix() . '.' . $field . '.' . $key, $value);
	}

	/**
	 * @param null $model
	 * @return string
	 */
	protected static function getPrefix($model = null) {
		$model = ($model) ?: Model::getCurrent();

		return self::$key . $model . '.' . self::$subkey;
	}
}