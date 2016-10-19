<?php

namespace Mascame\Artificer\Controllers;

use Illuminate\Support\Facades\Input;
use Mascame\Artificer\Fields\FieldFactory;
use Mascame\Formality\Parser\Parser;
use View;

class BaseModelController extends BaseController
{
    /**
     * The Eloquent model instance.
     * @var \Eloquent
     */
    protected $currentModel;
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

        $this->getFields($data);

        View::share('data', $this->data);
    }

    /**
     * @param $data
     * @return null
     */
    protected function getFields($data)
    {
        if ($this->fields) {
            return $this->fields;
        }

        /*
         * @var $data Collection
         */
        $modelFields = $this->modelSettings->getOption('fields');
        $types = config('admin.fields.types');
        $fields = [];

        foreach ($this->modelSettings->columns as $column) {
            $options = [];

            if (isset($modelFields[$column])) {
                $options = $modelFields[$column];
            }

            $fields[$column] = $options;
        }

        $fieldFactory = new FieldFactory(new Parser($types), $types, $fields, config('admin.fields.classmap'));
        $this->fields = $fieldFactory->makeFields();

        View::share('fields', $this->fields);

        return $this->fields;
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
