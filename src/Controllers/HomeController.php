<?php

namespace Mascame\Artificer\Controllers;

use Mascame\Artificer\Options\AdminOption;
use Redirect;

class HomeController extends BaseController
{
    /**
     * Get the first available model.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function home()
    {
        $model = collect($this->modelManager->all())->first(function ($model) {
            return ! in_array($model->name, AdminOption::get('model.hidden'));
        });

        return Redirect::route('admin.model.all', ['slug' => $model->route]);
    }
}
