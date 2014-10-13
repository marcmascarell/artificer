<?php namespace Mascame\Artificer\Permit;

use App;
use Mascame\Artificer\Model;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\ModelOption;

class ModelPermit extends Permit {

    protected static $actions = array('create', 'update', 'delete', 'view');

	public static function access($model = null) {
        if (!$model) $model = Model::getCurrent();

        $model_permissions = ModelOption::get('permissions', $model);

        return self::hasPermission($model_permissions);
	}

	public static function to($action)
	{
        $model = Model::getCurrent();

        $model_permissions = ModelOption::get('action_permissions.'.$action, $model);

        return self::hasPermission($model_permissions, self::getRole());
	}

    public static function hasPermission($permissions) {
        if (is_array($permissions) && !empty($permissions)) {

            if ($permissions[0] == '*') {
                return true;
            }

            if (in_array(self::getRole(), $permissions)) {
                return true;
            }
        } if (!is_array($permissions) || !$permissions) {
            return true;
        }

        return false;
    }

	public static function routeAction($route) {
		$route_permission = AdminOption::get('models.route_permission');

		if (in_array($route, array_keys($route_permission))) {
			if (!ModelPermit::to($route_permission[$route])) {
				App::abort('403', 'Insufficient privileges');
			}
		}
	}

} 