<?php namespace Mascame\Artificer\Http\Controllers;

use App;
use Mascame\Arrayer\Builder;
use Mascame\Artificer\Options\AdminOption;
use Redirect;
use View;

class PluginController extends BaseController
{

    public function plugins()
    {
        return View::make($this->getView('plugins'))
            ->with('plugins', App::make('ArtificerPluginManager')->getAll());
    }

    public function install($plugin)
    {
        $plugin = $this->getPluginSlug($plugin);

        App::make('ArtificerPluginManager')->installer()->install($plugin);

        return \Redirect::route('admin.plugins');
    }

    public function uninstall($plugin)
    {
        $plugin = $this->getPluginSlug($plugin);

        App::make('ArtificerPluginManager')->installer()->uninstall($plugin);

        return \Redirect::route('admin.plugins');
    }

    protected function getPluginSlug($plugin) {
        return \App::make('ArtificerPluginManager')->getFromSlug($plugin)->namespace;
    }

}