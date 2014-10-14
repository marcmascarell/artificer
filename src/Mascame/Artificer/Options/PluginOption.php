<?php namespace Mascame\Artificer\Options;

class PluginOption extends Option {

	public static $key = 'plugins/';

	/**
	 * @param string $plugin
	 * @param string $key
	 */
	public static function get($key = null, $plugin = null)
	{
		return Option::get(self::$key . $plugin . '.' . $key);
	}

	/**
	 * @param string $plugin
	 */
	public static function has($key = '', $plugin = null)
	{
		return Option::has(self::$key . $plugin. '.' . $key);
	}

	/**
	 * @param string $plugin
	 */
	public static function set($key, $value, $plugin = null)
	{
		Option::set(self::$key . $plugin . '.' . $key, $value);
	}

	/**
	 * @param string $key
	 */
	public static function all($key = null)
	{
		return Option::get(self::$key . $key);
	}
}