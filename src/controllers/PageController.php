<?php

namespace Mascame\Artificer;

use App;
use Redirect;
use Mascame\Artificer\Options\AdminOption;

class PageController extends BaseController
{
    public function home()
    {
        $hidden_models = AdminOption::get('models.hidden');
        $non_hidden_models = array_diff(array_keys($this->modelObject->schema->models), $hidden_models);

        $first_model = head($non_hidden_models);

        return Redirect::route('admin.model.all', ['slug' => $this->modelObject->schema->models[$first_model]['route']]);
    }

    public function install()
    {
        $this->modelObject = App::make('artificer-model');

        try {
            $user = \User::firstOrFail();

            // We check if there are users
            if ($user) {
                App::abort(404);
            }
        } catch (\Exception $e) {
            $this->share();

            $user = new \User();
            $user->role = 'admin';

            \Auth::login($user);

            return \View::make($this->getView('install'))
                ->with('plugins', App::make('artificer-plugin-manager')->getAll());
//			dd('install process... here we will scan models, maybe help with config and first user setup');
        }
    }
}
