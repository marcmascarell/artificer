<?php

namespace Mascame\Artificer\Model;

use Str;
use View;
use Route;
use Schema;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\ModelOption;

// Todo: get column type http://stackoverflow.com/questions/18562684/how-to-get-database-field-type-in-laravel
class Model
{
    /**
     * @var ModelSchema
     */
    public $schema;

    /**
     * @var array
     */
    public $models;

    /**
     * @var array
     */
    public $columns;

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
    public $options = [];

    /**
     * @var array|mixed
     */
    public $relations = [];

    /**
     * @var
     */
    public static $current = null;

    /**
     * @param ModelSchema $schema
     */
    public function __construct(ModelSchema $schema)
    {
        $this->schema = $schema;
        $this->relations = new ModelRelation();

        $this->getCurrentModel();
        $this->share();
    }

    public function share()
    {
        View::share('tables', $this->schema->tables);
        View::share('models', $this->getCurrentModelsData());
        View::share('model', $this->getCurrentModelData());
    }

    private function getCurrentModelsData()
    {
        foreach ($this->schema->models as $modelName => $model) {
            $this->schema->models[$modelName]['options'] = $this->getOptions($modelName);
            $this->schema->models[$modelName]['hidden'] = $this->isHidden($modelName);
        }

        return $this->schema->models;
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
     * @return bool
     */
    public function hasGuarded()
    {
        return (isset($this->options['guarded']) && ! empty($this->options['guarded'])) ? true : false;
    }

    /**
     * @return bool
     */
    public function hasFillable()
    {
        return (isset($this->options['fillable']) && ! empty($this->options['fillable'])) ? true : false;
    }

    private function getCurrentModelName()
    {
        if ($this->name) {
            return $this->name;
        }

        foreach ($this->schema->models as $modelName => $model) {
            if ($this->isCurrent($modelName)) {
                $this->setCurrent($modelName);

                return $this->name = $modelName;
            }
        }
    }

    public function getCurrentModel()
    {
        if (Str::startsWith(Route::currentRouteName(), 'admin.model.')
        ) {
            $this->name = $this->getCurrentModelName();
            $this->class = $this->schema->getClass($this->name);
            $this->model = $this->schema->getInstance($this->name, true);
            $this->table = $this->model->getTable();
            $this->columns = $this->schema->getColumns($this->table);
            $this->fillable = $this->model->getFillable();
            $this->options = $this->getOptions();
        }
    }

    private function getCurrentModelData()
    {
        return [
            'class'    => $this->class,
            'name'     => $this->getCurrentModelName(),
            'route'    => $this->getRouteName(),
            'table'    => $this->table,
            'columns'  => $this->schema->columns,
            'fillable' => $this->fillable,
            'hidden'   => $this->isHidden($this->name),
        ];
    }

    /**
     * @param $model
     * @return bool
     */
    protected function isCurrent($modelName)
    {
        $slug = Route::current()->parameter('slug');

        return isset($this->schema->models[$modelName]['route']) && $this->schema->models[$modelName]['route'] == $slug;
    }

    /**
     * @return null
     */
    public static function getCurrent()
    {
        return (isset(self::$current)) ? self::$current : null;
    }

    /**
     * @return string
     */
    public static function getCurrentClass()
    {
        return '\\'.self::$current;
    }

    /**
     * @param null $model
     * @return null
     */
    public function getRouteName($model = null)
    {
        if ($model) {
            return $this->schema->models[$model]['route'];
        }

        return (isset($this->schema->models[self::$current]['route'])) ? $this->schema->models[self::$current]['route'] : null;
    }

    /**
     * @param $modelName
     */
    protected function setCurrent($modelName)
    {
        self::$current = $modelName;
        ModelOption::set('current', $modelName);
    }

    /**
     * @param null $model
     * @return mixed
     */
    public function getOptions($model = null)
    {
        if (! $model) {
            $model = $this->name;
        }

        return ModelOption::model($model);
    }

    /**
     * @return array|mixed
     */
    public function getRelations()
    {
        return $this->relations->get();
    }
}
