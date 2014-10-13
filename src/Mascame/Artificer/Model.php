<?php namespace Mascame\Artificer;

use Mascame\Artificer\Permit\ModelPermit;
use View;
use Schema;
use File;
use Route;
use Config;
use Str;
use Mascame\Artificer\Options\ModelOption;
use Mascame\Artificer\Options\AdminOption;

// Todo: get column type http://stackoverflow.com/questions/18562684/how-to-get-database-field-type-in-laravel
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

		$this->getCurrentModel();

		View::share('tables', $this->tables);
		View::share('models', $this->models);
	}

	private function getCurrentModel() {
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
				'admin.field',
			))
		) {
			$this->name = self::getCurrent();
			$this->class = $this->getClass($this->name);
			$this->columns = $this->getColumns($this->getTable());
			$this->model = $this->getInstance();
			$this->table = $this->model->getTable();
			$this->fillable = $this->model->getFillable();
			$this->options = $this->getOptions();

			$this->share();
		}
	}

	public function hasColumn($column) {
		return (is_array($column) && in_array($column, $this->columns)) ? true : false;
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

	protected function isCurrent($modelName) {
		$slug = Route::current()->parameter('slug');

		return (!self::$current && $slug == $modelName || $slug == strtolower($modelName));
	}

	protected function setCurrent($modelName) {
		self::$current = $modelName;
		ModelOption::set('current', $modelName);
	}

	/**
	 * @param $directory
	 * @return array
	 */
	protected function scanModelsDirectory($directory)
	{
		$models = array();

		foreach (File::allFiles($directory) as $modelPath) {
			$modelName = $this->getFromFileName($modelPath);

			if ($this->isCurrent($modelName)) {
				$this->setCurrent($modelName);
			}

			if (!ModelPermit::access($modelName)) continue;

			$models[$modelName] = array(
				'name' => $modelName,
				'route' => strtolower($modelName),
				'options' => $this->getOptions($modelName),
				'hidden' => $this->isHidden($modelName)
			);
		}

		return $models;
	}

	/**
	 * @param $models
	 * @return array
	 */
	private function mergeModelDirectories($models) {
		$merged_models = array();

		foreach ($models as $key => $model) {
			foreach ($model as $name => $values) {
				$merged_models[$name] = $values;
			}
		}

		return $merged_models;
	}

	/**
	 * @return array
	 */
	public function getModels()
	{
		if (!empty($this->models)) return $this->models;

		$models = array();
		$model_directories = AdminOption::get('models.directories');

		foreach ($model_directories as $directory) {
			$models[] = $this->scanModelsDirectory($directory);
		}

		$models = $this->mergeModelDirectories($models);

		return $models;
	}

	public function instantiate($modelName)
	{
		$modelClass = $this->getClass($modelName);

		return $this->models[$modelName]['instance'] = new $modelClass;
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
			$this->relations[] = $this->getFieldsWithRelations($fields);
		}

		return $this->relations;
	}

	/**
	 * @param $field
	 * @return bool
	 */
	private function hasRelation($field) {
		return isset($field['relationship']) && isset($field['relationship']['method']);
	}

	/**
	 * @param $fields
	 * @return array
	 */
	private function getFieldsWithRelations($fields) {
		$relations = array();

		foreach ($fields as $field) {
			if ($this->hasRelation($field)) {
				$relations = $field['relationship']['method'];
			}
		}

		return $relations;
	}

	/**
	 * @param $modelName
	 * @return bool
	 */
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

	/**
	 * @return bool
	 */
	public function isGuarded() {
		return (isset($this->options['guarded']) && !empty($this->options['guarded'])) ? true : false;
	}

	/**
	 * @return bool
	 */
	public function isFillable() {
		return (isset($this->options['fillable']) && !empty($this->options['fillable'])) ? true : false;
	}
}