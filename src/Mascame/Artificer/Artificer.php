<?php namespace Mascame\Artificer;

use Config;
use Input;
use File;
use Mascame\Artificer\Fields\Field;
use View;
use Mascame\Artificer\Fields\Factory;
use Controller;
use Session;
use Event;
use App;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\Option;

class Artificer extends Controller {

	public $model = null;
	public $modelObject = null;
	public $fields = null;
	public $data;
	public $options;
	public $plugins = null;
	public $theme;
	public static $routes;

	/**
	 * @param Model $model
	 */
	public function __construct()
	{
        $model = App::make('artificer-model');
		$this->modelObject = $model;
		$this->model = $model->model;
		$this->options = AdminOption::all();
		$this->plugins = $this->bootPlugins();
		$this->theme = AdminOption::get('theme');

		View::share('main_title', AdminOption::get('title'));
		View::share('menu', AdminOption::get('menu'));
		View::share('theme', $this->theme);
		View::share('fields', array());
	}

	/**
	 * We just make Field objects WITH DATA when one record is passed
	 * Else we are in a "list" view
	 *
	 * @param $data
	 */
	public function handleData($data)
	{
		$this->data = $data;

//		dd($data->getRelations());
//		foreach ($data as $d) {
//			foreach ($d->book_attributes as $attribute) {
//				dd($attribute->name);
//			}
//		}
//		foreach ($data as $d) {
//
//			dd($d->attributes);
//		}

		/*
		 * We determine if it is the all view
		 */
		$data = ($this->isCollection($data)) ? null : $this->data;

		$this->getFields($data);
	}

	public function isCollection($object) {
		return is_a($object, 'Illuminate\Database\Eloquent\Collection');
	}

	/**
	 * @param $data
	 * @return null
	 */
	public function getFields($data)
	{
		if ($this->fields == null) {
			$this->fields = with(new Factory($this->modelObject, $data))->fields;
			View::share('fields', $this->fields);
		}

		return $this->fields;
	}

	/**
	 * @param $data
	 * @return array
	 */
	public function handleFiles($data)
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

	// This is used for simple upload (no plugins)
	public function uploadFile($fieldname, $path = null)
	{
		if (!$path) {
			$path = public_path() . '/uploads/';
		}

		$file = Input::file($fieldname);

		if (!file_exists($path)) {
			File::makeDirectory($path);
		}

		$name = time() . $file->getClientOriginalName();

		$file->move($path, $name);

		return $name;
	}

	public function getSort()
	{
		$sort = array();

		if (Input::has('sort_by')) {
			$sort['column'] = Input::get('sort_by');
			$sort['direction'] = Input::get('direction');
		} else {
			$sort['column'] = 'sort_id';
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

	public function getPlugins()
	{
		return ($this->plugins) ? $this->plugins : null;
	}

	public function bootPlugins()
	{
		$plugins = AdminOption::get('plugins');

        $all_plugins = array_merge($plugins['installed'], $plugins['uninstalled']);

		foreach ($all_plugins as $pluginNamespace) {
			$pluginName = explode('/', $pluginNamespace);
			$pluginName = end($pluginName);

			$plugin = Option::get('plugins/' . $pluginNamespace . '/' . $pluginName);
			$plugin = $plugin['plugin'];

            if (in_array($pluginNamespace, $plugins['installed'])) {
                $this->plugins['installed'][$pluginNamespace] = new $plugin($pluginNamespace);
                $this->plugins['installed'][$pluginNamespace]->boot();
            } else {
                $this->plugins['uninstalled'][$pluginNamespace] = new $plugin($pluginNamespace);
            }
		}

		return $this->plugins;
	}

	public function getPlugin($key)
    {
        if (array_key_exists($key, $this->plugins['installed'])) {
            return $this->plugins['installed'][$key];
        }

        return $this->plugins['uninstalled'][$key];
	}

	public function getView($view)
	{
		return $this->theme . '.' . $view;
	}

	public static function assets()
	{
		$widgets = '';

		foreach (Field::$widgets as $widget) {
			$widgets .= $widget->output();
		}

		return $widgets;
	}


}