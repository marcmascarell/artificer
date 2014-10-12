<?php namespace Mascame\Artificer;

use Input;
use Auth;
use Mascame\Artificer\Permit\ModelPermit;
use View;
use Mascame\Artificer\Fields\Factory as FieldFactory;
use App;
use Session;
use File;
use Str;
use Redirect;
use Validator;


class BaseModelController extends BaseController {

    public $model = null;


    public function __construct()
    {
        parent::__construct();

        if (!Auth::check() || !ModelPermit::access()) App::abort('403');

        $this->model = $this->modelObject->model;
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

    protected function filterInputData() {
        if ($this->modelObject->isGuarded()) {
            return $this->except($this->modelObject->options['guarded'], Input::only($this->modelObject->columns));
        }

        return Input::except('id');
    }

    protected function except($keys, $values)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        $results = $values;

        array_forget($results, $keys);

        return $results;
    }

    /**
     * @param $data
     * @return array
     */
    protected function handleFiles($data)
    {
        $new_data = array();

        foreach ($this->getFields($data) as $field) {

            if ($field->type == 'file' || $field->type == 'image') {

                if (Input::hasFile($field->name)) {
                    $new_data[$field->name] = $this->uploadFile($field->name);
                } else {
                    unset($data[$field->name]);
                }
            }
        }

        return array_merge($data, $new_data);
    }

    /**
     * This is used for simple upload (no plugins)
     *
     * @param $fieldname
     * @param null $path
     * @return string
     */
    protected function uploadFile($fieldname, $path = null)
    {
        if (!$path) {
            $path = public_path() . '/uploads/';
        }

        $file = Input::file($fieldname);

        if (!file_exists($path)) {
            File::makeDirectory($path);
        }

        $name = uniqid() . '-' . Str::slug($file->getClientOriginalName());

        $file->move($path, $name);

        return $name;
    }

    protected function getRelatedFieldOutput($modelName, $id, $field)
    {
        if ($id != 0) {
            $this->handleData($this->model->with($this->modelObject->getRelations())->findOrFail($id));
        } else {
            if (Session::has('_set_relation_on_create_'.$this->modelObject->name)) {
                $relateds = Session::get('_set_relation_on_create_'.$this->modelObject->name);
                $related_ids = array();
                foreach ($relateds as $related) {
                    $related_ids[] = $related['id'];
                }

                $data = $relateds[0]['modelClass']::whereIn('id', $related_ids)->get();

                $this->handleData($data);
            } else {
                return null;
            }
        }

        return $this->fields[$field]->output();
    }

    protected function redirect($validator, $route) {
        if (Input::has('_standalone')) {
            return Redirect::route($route, array('slug' => Input::get('_standalone')))
                ->withErrors($validator)
                ->withInput();
        }

        return Redirect::back()->withErrors($validator)->withInput();
    }

    protected function validator($data) {
        return Validator::make($data, $this->getRules());
    }
}