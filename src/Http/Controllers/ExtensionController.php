<?php namespace Mascame\Artificer\Http\Controllers;

use App;
use Illuminate\Support\Str;
use Mascame\Artificer\Artificer;
use View;

class ExtensionController extends BaseController
{
    protected $type;
    
    protected function getManager() {
        if ($this->getType() == 'plugins') {
            return App::make('ArtificerPluginManager');
        }

        return App::make('ArtificerWidgetManager');
    }
    
    protected function getType() {
        if ($this->type) return $this->type;
        
        return Str::startsWith(\Route::currentRouteName(), 'admin.plugins') ? 'plugins' : 'widgets';   
    }
    
    public function extensions()
    {
        return View::make(
            $this->getView('extensions'))
            ->with('packages', Artificer::pluginManager()->getPackages()
        );
    }

    public function install($plugin)
    {
        $plugin = $this->getExtensionSlug($plugin);

        $this->getManager()->installer()->install($plugin);

        return \Redirect::back();
    }

    public function uninstall($plugin)
    {
        $plugin = $this->getExtensionSlug($plugin);

        $this->getManager()->installer()->uninstall($plugin);

        return \Redirect::back();
    }

    protected function getExtensionSlug($plugin) {
        return $this->getManager()->getFromSlug($plugin)->namespace;
    }

}