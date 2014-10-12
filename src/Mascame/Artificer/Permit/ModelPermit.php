<?php namespace Mascame\Artificer\Permit;

use Auth;
use Mascame\Artificer\Model;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\ModelOption;

class ModelPermit extends Permit {

	public static function access($model = null) {
        if (!$model) $model = Model::getCurrent();

        $model_permissions = AdminOption::get('models.permissions.'.self::getRole());

        if (is_array($model_permissions) && !empty($model_permissions)) {
            if ($model_permissions[0] == '*') {
                return true;
            }

            if (in_array($model, $model_permissions)) {
                return true;
            }
        }

		return false;
	}

	public static function to($action)
	{

	}

} 