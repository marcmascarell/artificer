<?php namespace Mascame\Artificer\Http\Controllers;

use App;
use Mascame\Artificer\Options\AdminOption;
use Redirect;

class PageController extends BaseController
{

    public function home()
    {
        $hiddenModels = AdminOption::get('model.hidden');

        $nonHiddenModels = array_diff(array_keys($this->modelObject->schema->models), $hiddenModels);

        $firstModel = head($nonHiddenModels);

        return Redirect::route('admin.model.all',
            array('slug' => $this->modelObject->schema->models[$firstModel]['route']));
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
            $this->shareMainViewData();

            $user = new \User();
            $user->role = 'admin';

            \Auth::login($user);

            return \View::make($this->getView('install'))
                ->with('plugins', App::make('ArtificerPluginManager')->getAll());
//			dd('install process... here we will scan models, maybe help with config and first user setup');
        }


    }

}