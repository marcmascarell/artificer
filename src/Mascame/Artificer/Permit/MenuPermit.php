<?php namespace Mascame\Artificer\Permit;

use Mascame\Artificer\Options\AdminOption;

class MenuPermit extends Permit {

	public static function access($menu) {
        $menu_permissions = AdminOption::get('menu.'.$menu.'.permissions');

        if (is_array($menu_permissions) && !empty($menu_permissions)) {
            if ($menu_permissions[0] == '*') {
                return true;
            }

            if (in_array(self::getRole(), $menu_permissions)) {
                return true;
            }
        }

		return false;
	}

	public static function to($action)
	{

	}

} 