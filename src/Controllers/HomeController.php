<?php

namespace Mascame\Artificer\Controllers;

use Mascame\Artificer\Options\AdminOption;
use Redirect;

class HomeController extends BaseController
{
    public function home()
    {
        $model = collect($this->modelManager->all())->filter(function ($model) {
            return ! in_array($model->name, AdminOption::get('model.hidden'));
        })->first();

        return Redirect::route('admin.model.all', ['slug' => $model->route]);
    }
}
