<?php

namespace Mascame\Artificer\Permit;

use App;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\ModelOption;

class ModelPermit extends Permit
{
    protected static $actions = ['create', 'update', 'delete', 'view'];

    public static function access($model = null)
    {
        if (! $model) {
            $model = Model::getCurrent();
        }

        $model_permissions = ModelOption::get('permissions', $model);

        return self::hasPermission($model_permissions);
    }

    public static function to($action)
    {
        $model = Model::getCurrent();

        $model_permissions = ModelOption::get('action_permissions.'.$action, $model);

        return self::hasPermission($model_permissions, self::getRole());
    }

    public static function routeAction($route)
    {
        $route_permission = AdminOption::get('models.route_permission');

        if (in_array($route, array_keys($route_permission))) {
            if (! self::to($route_permission[$route])) {
                App::abort('403', 'Insufficient privileges');
            }
        }
    }
}
