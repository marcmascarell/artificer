<?php namespace Mascame\Artificer;

use Auth;
use Mascame\Artificer\Options\AdminOption;

class Permit extends Auth {

	private static $role = null;

	public function __construct() {

	}

	public static function access($to, $type) {
		return true;
	}

	public static function to($action)
	{

	}

	public static function getRole($role_column = 'role')
	{
		if (!static::$role) static::$role = Auth::user()->$role_column;

		return static::$role;
	}

	protected static function isModelAccessible() {

	}

} 