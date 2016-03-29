<?php namespace Mascame\Artificer\Permit;

use App;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\ModelOption;

class ModelPermit extends Permit
{

    protected static $actions = array('create', 'update', 'delete', 'view');

    public static function access($model = null)
    {
        if (! $model) {
            $model = Model::getCurrent();
        }

        $modelPermissions = ModelOption::get('permissions', $model);

        return self::hasPermission($modelPermissions);
    }

    public static function to($action)
    {
        $model = Model::getCurrent();

        $modelPermissions = ModelOption::get('action_permissions.' . $action, $model);

        return self::hasPermission($modelPermissions);
    }

    public static function routeAction($route)
    {
        $route_permission = AdminOption::get('model.route_permission');

        if (in_array($route, array_keys($route_permission))) {
            if (! ModelPermit::to($route_permission[$route])) {
                App::abort('403', 'Insufficient privileges');
            }
        }
    }

} 