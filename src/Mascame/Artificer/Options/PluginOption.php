<?php namespace Mascame\Artificer\Options;

class PluginOption extends Option {

	public static $key = 'plugins/';

	public static function get($key = null, $plugin = null)
	{
		return Option::get(self::$key . $plugin . '.' . $key);
	}

	public static function has($key = '', $plugin = null)
	{
		return Option::has(self::$key . '.' . $key);
	}

	public static function set($key = '', $value, $plugin = null)
	{
		Option::set(self::$key . $plugin . '.' . $key, $value);
	}

	public static function all($key = null)
	{
		return Option::get(self::$key . $key);
	}
}