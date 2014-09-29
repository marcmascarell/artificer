<?php namespace Mascame\Artificer;

use Input;
use File;
use Mascame\Artificer\Fields\Field;
use View;
use Mascame\Artificer\Fields\Factory as FieldFactory;
use Controller;
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
	public $menu = array();

	/**
	 * @param Model $model
	 */
	public function __construct()
	{
		$this->theme = AdminOption::get('theme');

		if (\Auth::check()) {
			$model = App::make('artificer-model');
			$this->modelObject = $model;
			$this->model = $model->model;
			$this->options = AdminOption::all();
			$this->plugins = $this->bootPlugins();

			View::share('main_title', AdminOption::get('title'));
			View::share('menu', $this->getMenu());
			View::share('theme', $this->theme);
			View::share('fields', array());
		}
	}

	public function getMenu() {
		if (!empty($this->menu)) return $this->menu;
		$user = \Auth::getUser();
		$menu = AdminOption::get('menu');

		foreach ($menu as $menu_item) {
			if ($menu_item['user_access'] == '*'
				|| $menu_item['user_access'] == $user->role
				|| (is_array($menu_item['user_access']) && isset($menu_item['user_access'][0]) && $menu_item['user_access'][0] == '*')
				|| is_array($menu_item['user_access']) && in_array($user->role, $menu_item['user_access'])) {
				$this->menu[] = $menu_item;
			}
		}

		return $this->menu;
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
			$plugin = Option::get('plugins/' . $pluginNamespace . '/' . $this->getPluginName($pluginNamespace));
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

	public function getPluginName($pluginNamespace) {
		$pluginName = explode('/', $pluginNamespace);

		return end($pluginName);
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