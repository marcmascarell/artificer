<?php

namespace Mascame\Artificer\Controllers;

use Request;
use View;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Options\AdminOption;

// Todo: Make some models forbidden for some users

class BaseController
{
    protected $fields;
    protected $data;
    protected $options;

    public static $routes;

    protected $theme;
    protected $standalone;
    protected $menu = [];
    protected $masterLayout = null;

    /**
     * @var ModelManager
     */
    protected $modelManager = null;

    public function __construct()
    {
        $this->theme = AdminOption::get('theme').'::';
        $this->masterLayout = 'base';
        $this->modelManager = Artificer::modelManager();

        $this->options = AdminOption::all();

        if ($this->isStandAlone()) {
            $this->masterLayout = 'standalone';
            $this->standalone = true;
        }

        $this->shareMainViewData();
    }

    protected function shareMainViewData()
    {
        View::share('appTitle', AdminOption::get('title'));
        View::share('menu', $this->getMenu());
        View::share('theme', $this->theme);
        View::share('layout', $this->theme.'.'.$this->masterLayout);
        View::share('standalone', $this->standalone);
        View::share('icon', AdminOption::get('icons'));
        View::share('models', $this->modelManager->all());
    }

    /**
     * @return bool
     */
    public function isStandAlone()
    {
        return Request::ajax() || Request::has('_standalone');
    }

    /**
     * @return array
     */
    public function getMenu()
    {
        return AdminOption::get('menu');
    }

    /**
     * @param string $view
     */
    public function getView($view)
    {
        return $this->theme.$view;
    }
}
