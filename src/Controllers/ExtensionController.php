<?php

namespace Mascame\Artificer\Controllers;

use View;
use Illuminate\Support\Str;
use Mascame\Artificer\Artificer;

class ExtensionController extends BaseController
{
    const ACTION_INSTALL = 'install';
    const ACTION_UNINSTALL = 'uninstall';

    const TYPE_PLUGINS = 'plugins';
    const TYPE_WIDGETS = 'widgets';

    /**
     * @var
     */
    protected $type;

    /**
     * @return mixed
     */
    protected function getManager()
    {
        if ($this->getType() == 'plugins') {
            return Artificer::pluginManager();
        }

        return Artificer::widgetManager();
    }

    /**
     * @return string
     */
    protected function getType()
    {
        if ($this->type) {
            return $this->type;
        }

        return Str::startsWith(\Route::currentRouteName(), 'admin.plugins') ? self::TYPE_PLUGINS : self::TYPE_WIDGETS;
    }

    public function extensions()
    {
        return View::make($this->getView('extensions'))
            // Both plugins and widgets use the same manager
            ->with('packages', Artificer::pluginManager()->getPackages()
        );
    }

    /**
     * @param $extension
     * @return \Illuminate\Http\RedirectResponse
     */
    public function install($extension)
    {
        return $this->doAction($extension, self::ACTION_INSTALL);
    }

    /**
     * @param $extension
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uninstall($extension)
    {
        return $this->doAction($extension, self::ACTION_UNINSTALL);
    }

    /**
     * @param $extension
     * @param $action
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    protected function doAction($extension, $action)
    {
        $extension = $this->getExtensionSlug($extension);

        if ($action == self::ACTION_UNINSTALL && Artificer::isCoreExtension($extension->namespace)) {
            throw new \Exception('Core extensions can not be uninstalled');
        }

        $this->getManager()->installer()->$action($extension->namespace);

        return \Redirect::back();
    }

    /**
     * @param $plugin
     * @return mixed
     */
    protected function getExtensionSlug($plugin)
    {
        return $this->getManager()->getFromSlug($plugin);
    }
}
