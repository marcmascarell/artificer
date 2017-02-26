<?php

namespace Mascame\Artificer\Controllers;

use View;
use Request;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Options\AdminOption;

class BaseController
{
    /**
     * @var
     */
    protected $fields;

    /**
     * @var
     */
    protected $data;

    /**
     * @var string
     */
    protected $theme;

    /**
     * @var bool
     */
    protected $standalone;

    /**
     * @var array
     */
    protected $menu = [];

    /**
     * @var null|string
     */
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
    protected function isStandAlone()
    {
        return Request::ajax() || Request::has('_standalone');
    }

    /**
     * @return array
     */
    private function getMenu()
    {
        return AdminOption::get('menu');
    }

    /**
     * @param string $view
     */
    protected function getView($view)
    {
        return $this->theme.$view;
    }
}
