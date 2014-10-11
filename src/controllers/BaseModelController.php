<?php namespace Mascame\Artificer;

use Input;
use Auth;
use View;
use Mascame\Artificer\Fields\Factory as FieldFactory;
use App;
use Mascame\Artificer\BaseController;


// Todo: Make some models forbidden for some users

class BaseModelController extends BaseController {

    public $model = null;
    public $modelObject = null;

    public function __construct()
    {
        parent::__construct();

        if (Auth::check()) {
            $model = App::make('artificer-model');

            $this->modelObject = $model;
            $this->model = $model->model;
        }
    }


    /**
     * @param $data
     */
    public function handleData($data)
    {
        $this->data = $data;

        $this->getFields($data);
    }

    /**
     * @param $data
     * @return null
     */
    public function getFields($data)
    {
        if ($this->fields != null) return $this->fields;

        $fieldfactory = new FieldFactory($this->modelObject);
        $this->fields = $fieldfactory->parseFields($data);

        View::share('fields', $this->fields);

        return $this->fields;
    }

    public function getSort()
    {
        $sort = array();

        if (Input::has('sort_by')) {
            $sort['column'] = Input::get('sort_by');
            $sort['direction'] = Input::get('direction');
        } else {
            if ($this->modelObject->hasColumn('sort_id')) {
                $sort['column'] = 'sort_id';
            } else {
                $sort['column'] = 'id';
            }
            $sort['direction'] = 'asc';
        }

        return $sort;
    }

    public function getRules()
    {
        if (isset($this->options['rules'])) {
            return $this->options['rules'];
        } else if (isset($this->model->rules)) {
            return $this->model->rules;
        }

        return array();
    }

    public static function getCurrentModelId($items)
    {
        if (isset($items->id)) {
            return $items->id;
        }

        return null;
    }
}