<?php namespace Mascame\Artificer\Controllers;

use App;

class InstallController extends BaseController
{
    /**
     * Todo
     * @return $this
     */
    public function install()
    {
        dd('To do');
//        $this->modelObject = App::make('ArtificerModel');
//
//        try {
//            $user = \User::firstOrFail();
//
//            // We check if there are users
//            if ($user) {
//                App::abort(404);
//            }
//
//        } catch (\Exception $e) {
//            $this->shareMainViewData();
//
//            $user = new \User();
//            $user->role = 'admin';
//
//            \Auth::login($user);
//
//            return \View::make($this->getView('install'))
//                ->with('plugins', App::make('ArtificerPluginManager')->getAll());
////			dd('install process... here we will scan models, maybe help with config and first user setup');
//        }
    }

}