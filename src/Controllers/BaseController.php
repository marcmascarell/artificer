<?php

namespace Mascame\Artificer\Controllers;

use View;
use Request;
use Mascame\Artificer\Artificer;
use Illuminate\Routing\Controller;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Support\JavaScript;
use Mascame\Artificer\Options\AdminOption;

class BaseController extends Controller
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
    protected $masterLayout = 'base';

    /**
     * @var ModelManager
     */
    protected $modelManager = null;

    public function __construct()
    {
        $this->modelManager = Artificer::modelManager();

        $this->theme = AdminOption::get('theme').'::';

        $this->shareMainViewData();
    }

    protected function shareMainViewData()
    {
        View::share('appTitle', AdminOption::get('title'));
        View::share('menu', $this->getMenu());
        View::share('theme', $this->theme);
        View::share('layout', $this->theme.'.'.$this->masterLayout);
        View::share('icon', AdminOption::get('icons'));

        // Send routes to JS
        config([
            'ziggy' => [
                'whitelist' => [
                    'admin.*',
                ],
            ],
        ]);

        JavaScript::add(['icons' => AdminOption::get('icons')]);

        // Models need the current user, we have to wait until session is available
        $this->whenSessionLoaded(function () {
            View::share('models', $this->modelManager->all()->filter(function ($model) {
                return ! $model->settings()->hidden;
            })->transform(function ($model) {
                return [
                    'slug' => $model->route,
                    'title' => $model->settings()->title,
                ];
            }));
        });
    }

    protected function whenSessionLoaded($callback)
    {
        $this->middleware(function ($request, $next) use ($callback) {
            $callback();

            return $next($request);
        });
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

    public function delegateToVue()
    {
        return View::make($this->getView('base'));
    }
}
