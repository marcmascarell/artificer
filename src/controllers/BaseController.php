<?php namespace Mascame\Artificer;

use Input;
use Auth;
use Mascame\Artificer\Fields\Field;
use View;
use Controller;
use App;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Permit;

// Todo: Make some models forbidden for some users

class BaseController extends Controller {

    public $fields;
    public $data;
    public $options;

    public static $routes;

    public $theme;
    public $standalone;
    public $menu = array();
    protected $master_layout = null;

    public $modelObject = null;

    /**
     */
    public function __construct()
    {
        $this->theme = AdminOption::get('theme');

        if (Auth::check()) {
            $this->options = AdminOption::all();

            App::make('artificer-plugin-manager')->boot();
            $this->modelObject = App::make('artificer-model');

            if (\Request::ajax() || Input::has('_standalone')) {
                $this->master_layout = 'standalone';
                $this->standalone = true;
            } else {
                $this->master_layout = 'base';
            }

            View::share('main_title', AdminOption::get('title'));
            View::share('menu', $this->getMenu());
            View::share('theme', $this->theme);
            View::share('layout', $this->theme . '.' . $this->master_layout);
            View::share('fields', array());
            View::share('standalone', $this->standalone);
        }
    }

    public function getMenu()
    {
        if (!empty($this->menu)) return $this->menu;
        $menu = AdminOption::get('menu');

        foreach ($menu as $menu_key => $menu_item) {
            if (Permit\MenuPermit::access($menu_key)) {
                $this->menu[] = $menu_item;
            }
        }

        return $this->menu;
    }

    /**
     * @param string $view
     */
    public function getView($view)
    {
        return $this->theme . '.' . $view;
    }

    public static function assets()
    {
        $widgets = '';

        foreach (Field::$widgets as $widget) {
            $widgets .= $widget->output();
        }

        return $widgets;
    }


}