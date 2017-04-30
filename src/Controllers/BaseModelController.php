<?php

namespace Mascame\Artificer\Controllers;

use View;
use Illuminate\Support\Facades\Input;

class BaseModelController extends BaseController
{
    /**
     * The Eloquent model instance.
     * @var \Eloquent
     */
    protected $currentModel;

    /**
     * @var \Mascame\Artificer\Model\ModelSettings
     */
    protected $modelSettings;

    public function __construct()
    {
        parent::__construct();

        $model = $this->modelManager->current();

        $this->modelSettings = $model->settings();
        $this->currentModel = $model->model();

        View::share('model', $model);
    }

    /**
     * @param $data
     */
    protected function handleData($data)
    {
        $this->data = $data;

        View::share('data', $this->data);
        View::share('fields', $this->modelManager->current()->toForm());
    }

    /**
     * @return array
     */
    protected function getSort()
    {
        return [
            'column' =>  Input::get('sort_by', 'id'),
            'direction' =>  Input::get('direction', 'asc'),
        ];
    }

    /**
     * @param $items
     * @return null
     */
    public static function getCurrentModelId($items)
    {
        return (isset($items->id)) ? $items->id : null;
    }

    /**
     * @param $modelName
     * @param null $data
     * @param $sort
     */
    protected function all($modelName, $data, $sort)
    {
        $this->handleData($data);

        return View::make($this->getView('all'))
            ->with('items', $this->data)
            ->with('sort', $sort);
    }
}
