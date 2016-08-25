<?php namespace Mascame\Artificer\Controllers;

use Request;
use View;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Options\AdminOption;

// Todo: Make some models forbidden for some users

class BaseController
{

    public $fields;
    public $data;
    public $options;

    public static $routes;

    public $theme;
    public $standalone;
    public $menu = array();
    protected $master_layout = null;

    /**
     * @var ModelManager
     */
    public $modelObject = null;


    public function __construct()
    {
        $this->theme = AdminOption::get('theme') . '::';
        $this->master_layout = 'base';
        $this->modelObject = Artificer::modelManager();

        $this->options = AdminOption::all();

        if ($this->isStandAlone()) {
            $this->master_layout = 'standalone';
            $this->standalone = true;
        }

        $this->shareMainViewData();
    }

    protected function shareMainViewData()
    {
        View::share('main_title', AdminOption::get('title'));
        View::share('menu', $this->getMenu());
        View::share('theme', $this->theme);
        View::share('layout', $this->theme . '.' . $this->master_layout);
        View::share('fields', array());
        View::share('standalone', $this->standalone);
        View::share('icon', AdminOption::get('icons'));
    }

    /**
     * @return bool
     */
    public function isStandAlone()
    {
        return (Request::ajax() || Request::has('_standalone'));
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
        return $this->theme . $view;
    }

}