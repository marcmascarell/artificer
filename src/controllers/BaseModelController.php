<?php namespace Mascame\Artificer;

use Illuminate\Routing\Redirector;
use Illuminate\Routing\Router;
use Input;
use Auth;
use Mascame\Artificer\Permit\ModelPermit;
use View;
use Mascame\Artificer\Fields\FieldFactory;
use App;
use Session;
use File;
use Str;
use Redirect;
use Validator;
use Route;


class BaseModelController extends BaseController {

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

		if (!Auth::check() || !ModelPermit::access()) App::abort('403');

		$this->model = $this->modelObject->model;

		$this->checkPermissions();
	}

	/**
	 *
	 */
	protected function checkPermissions()
	{
		$permit = array(
			'view'   => ModelPermit::to('view'),
			'update' => ModelPermit::to('update'),
			'create' => ModelPermit::to('create'),
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
		if ($this->fields != null) return $this->fields;

		$fieldfactory = new FieldFactory($this->modelObject);
		$this->fields = $fieldfactory->parseFields($data);

		View::share('fields', $this->fields);

		return $this->fields;
	}

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
		} else if (isset($this->model->rules)) {
			return $this->model->rules;
		}

		return array();
	}

	/**
	 * @param $items
	 * @return null
	 */
	public static function getCurrentModelId($items)
	{
		if (isset($items->id)) {
			return $items->id;
		}

		return null;
	}

	/**
	 * @return array|mixed
	 */
	protected function filterInputData()
	{
		if ($this->modelObject->isGuarded()) {
			return $this->except($this->modelObject->options['guarded'], Input::only($this->modelObject->columns));
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

		foreach ($this->getFields($data) as $field) {
			if ($this->isFileInput($field->type)) {
				if (Input::hasFile($field->name)) {
					$new_data[$field->name] = $this->uploadFile($field->name);
				} else {
					unset($data[$field->name]);
				}
			}
		}

		return array_merge($data, $new_data);
	}

    protected function isFileInput($type) {
        return ($type == 'file' || $type == 'image');
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
		if (!$path) $path = public_path() . '/uploads/';

		$file = Input::file($fieldname);

		if (!file_exists($path)) File::makeDirectory($path);

		$name = uniqid() . '-' . Str::slug($file->getClientOriginalName());

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
	protected function redirect($validator, $route)
	{
		if (Input::has('_standalone')) {
			return Redirect::route($route, array('slug' => Input::get('_standalone')))
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
	protected function all($modelName, $data = null, $sort) {
		$this->handleData($data);

		return View::make($this->getView('all'))
			->with('items', $this->data)
			->with('sort', $sort);
	}
}