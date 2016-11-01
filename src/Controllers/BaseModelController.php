<?php

namespace Mascame\Artificer\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Input;
use View;

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

        $this->modelSettings = $this->modelManager->current();
        $this->currentModel = $this->modelSettings->model;

        View::share('models', $this->modelManager->all());
        View::share('model', $this->modelSettings);
    }

    /**
     * @param $data
     */
    protected function handleData($data)
    {
        $this->data = $data;
        $modelValues = $this->data;

        // If it is not an Eloquent instance just ignore modelValues
        if (is_a($modelValues, Collection::class)) {
            $modelValues = null;
        }

        View::share('fields', $this->modelSettings->toForm($modelValues));

        View::share('data', $this->data);
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
