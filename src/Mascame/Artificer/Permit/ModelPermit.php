<?php namespace Mascame\Artificer\Permit;

use Auth;
use Mascame\Artificer\Model;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\ModelOption;

class ModelPermit extends Permit {

    protected static $actions = array('create', 'update', 'delete', 'view');

	public static function access($model = null) {
        if (!$model) $model = Model::getCurrent();

        $model_permissions = ModelOption::get('permissions', $model);

        return self::hasPermission($model_permissions, $model);
	}

	public static function to($action)
	{
        $model = Model::getCurrent();

        $model_permissions = ModelOption::get('action_permissions.'.$action, $model);

        return self::hasPermission($model_permissions, $model);
	}

    public static function hasPermission($permissions, $model = null) {
        if (is_array($permissions) && !empty($permissions)) {

            if ($permissions[0] == '*') {
                return true;
            }

            if (in_array($model, $permissions)) {
                return true;
            }
        } if (!is_array($permissions) || !$permissions) {
            return true;
        }

        return false;
    }

} 