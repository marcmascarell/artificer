<?php namespace Mascame\Artificer\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Request;
use Auth;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Model\ModelManager;
use View;
use Illuminate\Routing\Controller as Controller;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Permit;

// Todo: Make some models forbidden for some users

class BaseController extends Controller
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

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

        if (! Auth::guard('admin')->guest()) {
            $this->options = AdminOption::all();

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
        return (Request::ajax() || Request::has('_standalone'));
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

}