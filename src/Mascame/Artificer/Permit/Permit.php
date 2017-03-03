<?php

namespace Mascame\Artificer\Permit;

use Auth;

abstract class Permit extends Auth
{
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
        if (! static::$role && isset(Auth::user()->$role_column)) {
            return static::$role = Auth::user()->$role_column;
        }
    }

    /**
     * @param null $permissions
     * @return bool
     */
    public static function hasPermission($permissions = null)
    {
        if (! $permissions) {
            return true;
        }

        if (is_array($permissions) && self::hasNeededRole($permissions)) {
            return true;
        }

        return false;
    }

    /**
     * @param $permissions
     * @return bool
     */
    private static function hasNeededRole($permissions)
    {
        return in_array(self::getRole(), $permissions) || $permissions[0] == '*';
    }
}
