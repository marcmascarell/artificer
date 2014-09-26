<?php namespace Mascame\Artificer;

use View;
use Schema;
use File;
use Route;
use Config;
use Str;
use Mascame\Artificer\Options\ModelOption;
use Mascame\Artificer\Options\AdminOption;


class Model {

	/**
	 * @var array
	 */
	public $tables;

	/**
	 * @var array
	 */
	public $models;

	/**
	 * @var mixed
	 */
	public $model;

	/**
	 * @var string
	 */
	public $class;

	/**
	 * @var
	 */
	public $name;

	/**
	 * @var string
	 */
	public $keyname;

	/**
	 * @var array
	 */
	public $columns;

	/**
	 * @var
	 */
	public $table;

	/**
	 * @var
	 */
	public $fillable;

	/**
	 * @var array|mixed
	 */
	public $options = array();

	/**
	 * @var array|mixed
	 */
	public $relations = array();

	/**
	 * @var
	 */
	public static $current = null;

	/**
	 *
	 */
	public function __construct()
	{
		$this->models = $this->getModels();
		$this->tables = $this->getTables($this->models);

		// Todo: improve this condition-....
		if (in_array(Route::currentRouteName(),
			array(
				'admin.all',
				'admin.show',
				'admin.edit',
				'admin.update',
				'admin.create',
				'admin.store',
				'admin.destroy',
				'admin.sort',
				'admin.upload',
				'admin.pagination',
			))
		) {
			$this->name = self::getCurrent();
			$this->class = $this->getClass($this->name);
			$this->columns = $this->getColumns($this->getTable());

			// todo: aviodable?
//			$this->keyname = $this->getRouteName();
			$this->model = $this->getInstance();
			$this->table = $this->model->getTable();
			$this->fillable = $this->model->getFillable();

			$this->options = $this->getOptions();

			$this->share();
		}

		View::share('tables', $this->tables);
		View::share('models', $this->models);
	}

	public static function getCurrent()
	{
		return self::$current;
	}

	public static function getCurrentClass()
	{
		return '\\' . self::$current;
	}

	public function getRouteName($model = null)
	{
		if ($model) {
			return $this->models[$model]['route'];
		}

		return (isset($this->models[self::$current]['route'])) ? $this->models[self::$current]['route'] : null;
	}

	/**
	 * @return array
	 */
	public function getModels()
	{
		if (!empty($this->models)) return $this->models;

		$slug = Route::current()->parameter('slug');
		$models = array();
		$model_directories = AdminOption::get('models.directories');

		foreach ($model_directories as $directory) {
			foreach (File::allFiles($directory) as $modelPath) {
				$modelName = $this->getFromFileName($modelPath);

				if (!self::$current && $slug == $modelName || $slug == strtolower($modelName)) {
					self::$current = $modelName;
					ModelOption::set('current', $modelName);
				}

				$models[$modelName]['name'] = $modelName;
				$models[$modelName]['route'] = strtolower($modelName);
				$models[$modelName]['options'] = $this->getOptions($modelName);
				$models[$modelName]['hidden'] = $this->isHidden($modelName);

//				$models[$modelName]['instance'] = $this->instantiate($modelName);
			}
		}

		return $models;
	}

	public function instantiate($modelName)
	{
		$modelClass = $this->getClass($modelName);

		return new $modelClass;
	}

	public function hasInstance($modelName)
	{
		return (isset($this->models[$modelName]['instance'])) ? true : false;
	}

	/**
	 * @param null $modelName
	 * @return mixed
	 */
	public function getInstance($modelName = null)
	{
		($modelName) ?: $modelName = $this->name;


		if ($this->hasInstance($modelName)) {
			return $this->models[$modelName]['instance'];
		}

		return $this->instantiate($modelName);
	}

	/**
	 * @param null $model
	 * @return mixed
	 */
	public function getOptions($model = null)
	{
		if (!$model) {
			$model = $this->name;
		}

		return ModelOption::model($model);
	}

	/**
	 * @param $model
	 * @return mixed
	 */
	public function getFromFileName($model)
	{
		$piece = explode('/', $model);

		return str_replace('.php', '', end($piece));
	}

	/**
	 * @param $models
	 * @return array
	 */
	public function getTables($models)
	{
		$tables = array();

		foreach ($models as $model) {
			$table = $this->getTable($model['name']);
			$this->models[$model['name']]['table'] = $table;
			$tables[] = $table;
		}

		return $tables;
	}

	/**
	 * @param bool $modelClass
	 * @return mixed
	 */
	public function getTable($modelName = null)
	{
		if (!$modelName) {
			$modelName = $this->name;
		}

		if (isset($this->models[$modelName]['table'])) {
			return $this->models[$modelName]['table'];
		}

		return $this->getInstance($modelName)->getTable();
	}

	/**
	 * @param $table
	 * @return bool
	 */
	public function hasTable($table)
	{
		return Schema::hasTable($table);
	}

	/**
	 * @param $table
	 * @return array
	 */
	public function getColumns($table)
	{
		return Schema::getColumnListing($table);
	}

	/**
	 * @param $modelName
	 * @return string
	 */
	public function getClass($modelName)
	{
		if (false !== $key = array_search($modelName, array_keys($this->models))) {
			$modelName = array_keys($this->models);
			$modelName = $modelName[$key];
		}

		return '\\' . $modelName;
	}

	/**
	 * @return array|mixed
	 */
	public function getRelations()
	{
		if (!empty($this->relations)) return $this->relations;

		$fields = ModelOption::get('fields');

		if (!empty($fields)) {
			foreach ($fields as $field) {
				if (isset($field['relationship']) && isset($field['relationship']['method'])) {
					$this->relations = $field['relationship']['method'];
				}
			}
		} else {
			$this->relations = array();
		}

		return $this->relations;
	}

	public function isHidden($modelName)
	{
		return (in_array($modelName, AdminOption::get('models.hidden'))) ? true : false;
	}

	/**
	 *
	 */
	public function share()
	{
		$model = array(
			'class'    => $this->class,
			'name'     => $this->name,
			'route'    => $this->getRouteName(),
			'table'    => $this->table,
			'columns'  => $this->columns,
			'fillable' => $this->fillable,
			'hidden'   => $this->isHidden($this->name),
		);

		View::share('model', $model);
	}
}