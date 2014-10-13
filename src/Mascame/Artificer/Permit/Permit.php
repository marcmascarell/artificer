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

    public static function hasPermission($permissions) {
        if (is_array($permissions) && !empty($permissions)) {

            if (self::hasRole($permissions)) {
                return true;
            }

        } else if (!is_array($permissions) || !$permissions) {
            return true;
        }

        return false;
    }

    private static function hasRole($permissions) {
        return (in_array(self::getRole(), $permissions) || $permissions[0] == '*');
    }
} 