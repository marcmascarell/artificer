<?php namespace Mascame\Artificer\Permit;

use Auth;

abstract class Permit extends Auth {

	protected static $role = null;

	public static function access($to) {

	}

	public static function to($action)
	{

	}

	public static function getRole($role_column = 'role')
	{
		if (!static::$role) static::$role = Auth::user()->$role_column;

		return static::$role;
	}

} 