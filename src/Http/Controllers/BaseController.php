<?php namespace Mascame\Artificer\Http\Controllers;

use App;
use Auth;
use Input;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Fields\FieldWrapper;
use Mascame\Artificer\Model\Model;
use View;
use Illuminate\Routing\Controller as Controller;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Permit;

// Todo: Make some models forbidden for some users

class BaseController extends Controller
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
     * @var Model
     */
    public $modelObject = null;


    public function __construct()
    {
        $this->theme = AdminOption::get('theme') . '::';
        $this->master_layout = 'base';

        // Todo: Do Sth with this
        if (UserController::check() || true) {
            $this->options = AdminOption::all();

            $this->modelObject = Artificer::getModel();

            if ($this->isStandAlone()) {
                $this->master_layout = 'standalone';
                $this->standalone = true;
            }

            $this->shareMainViewData();
        }
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
        return (\Request::ajax() || Input::has('_standalone'));
    }

    /**
     * @return array
     */
    public function getMenu()
    {
        if ( ! empty($this->menu)) return $this->menu;

        $menu = AdminOption::get('menu');

        foreach ($menu as $key => $menuItem) {
            // Todo: Permit is absolete or not?
            if (Permit\MenuPermit::access($key) || true) {
                $this->menu[] = $menuItem;
            }
        }

        return $this->menu;
    }

    /**
     * @param string $view
     */
    public function getView($view)
    {
        return $this->theme . $view;
    }

    /**
     * @return string
     */
    public static function assets()
    {
        $widgets = '';

        foreach (FieldWrapper::$widgets as $widget) {
            $widgets .= $widget->output();
        }

        return $widgets;
    }


}