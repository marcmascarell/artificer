<?php

namespace Mascame\Artificer\Controllers;

use Mascame\Artificer\Options\AdminOption;
use Redirect;

class HomeController extends BaseController
{
    public function home()
    {
        $hiddenModels = AdminOption::get('model.hidden');

        $nonHiddenModels = array_diff(array_keys($this->modelObject->schema->models), $hiddenModels);

        $firstModel = head($nonHiddenModels);

        return Redirect::route('admin.model.all',
            ['slug' => $this->modelObject->schema->models[$firstModel]['route']]);
    }
}
