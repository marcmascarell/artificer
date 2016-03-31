<?php namespace Mascame\Artificer\Http\Controllers;

use App;
use Auth;
use File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Router;
use Input;
use Mascame\Artificer\Fields\Field;
use Mascame\Artificer\Fields\FieldFactory;
use Mascame\Formality\Factory\Factory;
use Mascame\Formality\Parser\Parser;
use Mascame\Artificer\Permit\ModelPermit;
use Mascame\Formality\Manager\Manager;
use Redirect;
use Route;
use Session;
use Validator;
use View;


class BaseModelController extends BaseController
{

    /**
     * The Eloquent model instance
     * @var \Eloquent
     */
    protected $model;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        // Todo: Do Sth with this
        if (false) {
            if (! Auth::check() || ! ModelPermit::access()) {
                App::abort('403', 'Forbidden access');
            }
        }

        $this->model = $this->modelObject->model;

        $this->checkPermissions();
    }

    /**
     *
     */
    protected function checkPermissions()
    {
        $permit = array(
            'read' => ModelPermit::to('read'),
            'create' => ModelPermit::to('create'),
            'update' => ModelPermit::to('update'),
            'delete' => ModelPermit::to('delete'),
        );

        ModelPermit::routeAction(Route::currentRouteName());

        View::share('permit', $permit);
    }

    /**
     * @param $data
     */
    protected function handleData($data)
    {
        $this->data = $data;

        $this->getFields($data);
    }

    /**
     * @param $data
     * @return null
     */
    protected function getFields($data)
    {
        if ($this->fields) return $this->fields;

        /**
         * @var $data Collection
         */
//        $fieldManager = new Manager(new Parser(config('admin.fields.types')), Field::class);

//        $data = $data->makeVisible($this->modelObject->columns)->toArray();

        // Todo: try to avoid parsing all columns each time...


        $fieldFactory = new FieldFactory(new Parser(config('admin.fields.types')), $this->modelObject->columns, config('admin.fields.classmap'));
        $this->fields = $fieldFactory->makeFields();
//dd($this->fields);
//        dd($data);
        // Fulfill data
//        foreach ($data as $items) {
//            $itemData = $items->makeVisible($this->modelObject->columns)->toArray();
//
////            foreach
//        }

        View::share('fields', $this->fields);

        return $this->fields;
    }

    // Prepares fields for factory
//    protected function prepareFields($data) {
//        $fields = [];
//
//        foreach ($data as $key => $item) {
//            foreach ($this->modelObject->columns as $column) {
//                $fields[$key][$column] = $item->$column;
//            }
//        }
//
//        return $fields;
//    }

    /**
     * @return array
     */
    protected function getSort()
    {
        $sort = array();

        if (Input::has('sort_by')) {
            $sort['column'] = Input::get('sort_by');
            $sort['direction'] = Input::get('direction');
        } else {

            if ($this->modelObject->schema->hasColumn('sort_id')) {
                $sort['column'] = 'sort_id';
            } else {
                $sort['column'] = 'id';
            }

            $sort['direction'] = 'asc';
        }

        return $sort;
    }

    /**
     * @return array
     */
    protected function getRules()
    {
        if (isset($this->options['rules'])) {
            return $this->options['rules'];
        } else {
            if (isset($this->model->rules)) {
                return $this->model->rules;
            }
        }

        return array();
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
     * @return array|mixed
     */
    protected function filterInputData()
    {
        if ($this->modelObject->hasGuarded()) {
            $input = Input::all();
            $filtered_input = array();

            foreach ($input as $key => $value) {
                if (in_array($key, $this->modelObject->columns)) {
                    $filtered_input[$key] = $value;
                }
            }

            return $this->except($this->modelObject->options['guarded'], $filtered_input);
        }

        return Input::except('id');
    }

    /**
     * @param $keys
     * @param $values
     * @return mixed
     */
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
        $fields = $this->getFields($data);

        if (!is_null($fields)) {
            foreach ($fields as $field) {
                if ($this->isFileInput($field->type)) {
                    if (Input::hasFile($field->name)) {
                        $new_data[$field->name] = $this->uploadFile($field->name);
                    } else {
                        unset($data[$field->name]);
                    }
                }
            }
        }

        return array_merge($data, $new_data);
    }

    /**
     * @param $type
     * @return bool
     */
    protected function isFileInput($type)
    {
        return ($type == 'file' || $type == 'image');
    }

    /**
     * This is used for simple upload (no plugins)
     *
     * @param $fieldName
     * @param null $path
     * @return string
     */
    protected function uploadFile($fieldName, $path = null)
    {
        if (!$path) {
            $path = public_path() . '/uploads/';
        }

        $file = Input::file($fieldName);

        if (!file_exists($path)) {
            File::makeDirectory($path);
        }

        $name = uniqid() . '-' . Str::slug($file->getFilename()) . '.' . $file->guessExtension();

        $file->move($path, $name);

        return $name;
    }

    /**
     * @param $modelName
     * @param $id
     * @param $field
     * @return null
     */
    protected function getRelatedFieldOutput($modelName, $id, $field)
    {
        if ($id != 0) {
            $this->handleData($this->model->with($this->modelObject->getRelations())->findOrFail($id));
        } else {
            if (Session::has('_set_relation_on_create_' . $this->modelObject->name)) {
                $relateds = Session::get('_set_relation_on_create_' . $this->modelObject->name);
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

    /**
     * @param $validator
     * @param string $route
     * @return $this
     */
    protected function redirect($validator, $route, $id = null)
    {
        if (Input::has('_standalone')) {
            $routeParams = array('slug' => Input::get('_standalone'));

            if ($id) {
                $routeParams['id'] = $id;
            }

            return Redirect::route($route, $routeParams)
                ->withErrors($validator)
                ->withInput();
        }

        return Redirect::back()->withErrors($validator)->withInput();
    }

    /**
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    protected function validator($data)
    {
        return Validator::make($data, $this->getRules());
    }

    /**
     * @param $modelName
     * @param null $data
     * @param $sort
     * @return $this
     */
    protected function all($modelName, $data = null, $sort)
    {
        $this->handleData($data);

        return View::make($this->getView('all'))
            ->with('items', $this->data)
            ->with('sort', $sort);
    }
}