<?php namespace Mascame\Artificer\Permit;

use Auth;

abstract class Permit extends Auth {

	protected static $role = null;

    /**
     * @param $to
     */
	public static function access($to)
    {
        return false;
	}

    /**
     * @param $action
     */
	public static function to($action)
	{
        return false;
	}

    /**
     * @param string $role_column
     * @return null
     */
	public static function getRole($role_column = 'role')
	{
		if (!static::$role) static::$role = Auth::user()->$role_column;

		return static::$role;
	}

    /**
     * @param null $permissions
     * @return bool
     */
    public static function hasPermission($permissions = null)
    {
        if (!$permissions) return true;

        if (self::userHasPermission($permissions)) {
            return true;
        }

        return false;
    }

    /**
     * @param $permissions
     * @return bool
     */
    private static function userHasPermission($permissions)
    {
       return (is_array($permissions) && !empty($permissions) && self::hasAcceptableRole($permissions));
    }

    /**
     * @param $permissions
     * @return bool
     */
    private static function hasAcceptableRole($permissions)
    {
        return (in_array(self::getRole(), $permissions) || $permissions[0] == '*');
    }
} 