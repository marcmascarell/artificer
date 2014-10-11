<?php namespace Mascame\Artificer;

use Input;
use Auth;
use File;
use Mascame\Artificer\Fields\Field;
use View;
use Mascame\Artificer\Fields\Factory as FieldFactory;
use Controller;
use App;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\Option;
use Mascame\Artificer\Permit;

// Todo: Make some models forbidden for some users

class BaseController extends Controller {

    public $fields = null;
    public $data;
    public $options;

    public static $routes;

    public $theme;
    public $standalone;
    public $menu = array();
    protected $master_layout = null;

    /**
     * @param Model $model
     */
    public function __construct()
    {
        $this->theme = AdminOption::get('theme');

        if (Auth::check()) {
            $this->options = AdminOption::all();

            App::make('artificer-plugin-manager')->boot();

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
        $user = \Auth::getUser();
        $menu = AdminOption::get('menu');

        foreach ($menu as $menu_item) {
            if ($menu_item['user_access'] == '*'
                || $menu_item['user_access'] == $user->role
                || (is_array($menu_item['user_access'])
                    && isset($menu_item['user_access'][0])
                    && $menu_item['user_access'][0] == '*')
                || (is_array($menu_item['user_access'])
                    && in_array($user->role, $menu_item['user_access']))
            ) {
                $this->menu[] = $menu_item;
            }
        }

        return $this->menu;
    }

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