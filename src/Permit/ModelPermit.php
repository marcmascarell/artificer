<?php namespace Mascame\Artificer\Permit;

use App;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Options\AdminOption;

class ModelPermit extends Permit
{

    protected static $actions = array('create', 'update', 'delete', 'view');

    public static function access($model = null)
    {
        return true;

        if (! $model) $model = ModelManager::getCurrent()->name;

        $modelPermissions = Artificer::modelManager()->getOption('permissions', [], $model);

        return self::hasPermission($modelPermissions);
    }

    public static function to($action)
    {
        return true;
        $model = ModelManager::getCurrent()->name;

        $modelPermissions = Artificer::modelManager()->getOption('action_permissions.' . $action, [], $model);

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