<?php

namespace Mascame\Artificer\Permit;

use Mascame\Artificer\Options\AdminOption;

class MenuPermit extends Permit
{
    public static function access($menu)
    {
        $menu_permissions = AdminOption::get('menu.'.$menu.'.permissions');

        return self::hasPermission($menu_permissions);
    }

    public static function to($action)
    {
    }
}
