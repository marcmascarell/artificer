<?php namespace Mascame\Artificer\Model;

use Illuminate\Contracts\Database\ModelIdentifier;
use View;
use Route;
use \Illuminate\Support\Str as Str;
use Mascame\Artificer\Options\AdminOption;

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
     * @var \Illuminate\Database\Eloquent\Model
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
    protected $options = [];
    protected $defaultOptions = null;

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

        if (Str::startsWith(Route::currentRouteName(), 'admin.model.')) {
            $this->prepareCurrentModel();
        }

        $this->share();
    }


    public function share()
    {
        View::share('tables', $this->schema->tables);
        View::share('models', $this->models = $this->getModelsData());
        View::share('model', $this->getCurrentModelData());
    }

    /**
     * @return array
     */
    private function getModelsData()
    {
        foreach ($this->schema->models as $modelName => $model) {
            $this->schema->models[$modelName]['options'] = $this->getOptions($modelName);
            $this->schema->models[$modelName]['hidden'] = $this->isHidden($modelName);

            $title = null;
            if (isset($this->schema->models[$modelName]['title'])) {
                $title = $this->schema->models[$modelName]['title'];
            } else {
                $title = Str::title(
                  str_replace('_', ' ', $this->schema->models[$modelName]['table'])
                );
            }

            $this->schema->models[$modelName]['title'] = $title;
        }

        return $this->schema->models;
    }

    /**
     * @param $modelName
     * @return bool
     */
    public function isHidden($modelName)
    {
        return in_array($modelName, config('admin.model.hidden'));
    }

    /**
     * @return bool
     */
    public function hasGuarded()
    {
        return ! empty($this->getOption('guarded', []));
    }

    /**
     * @return bool
     */
    public function hasFillable()
    {
        return ! empty($this->getOption('fillable', []));
    }

    /**
     * @return int|null|string
     */
    private function getCurrentModelName()
    {
        if ($this->name) return $this->name;

        foreach ($this->schema->models as $modelName => $model) {
            if ($this->isCurrent($modelName)) {
                $this->setCurrent($modelName);

                return $this->name = $modelName;
            }
        }

        return null;
    }

    protected function prepareCurrentModel()
    {
        $this->name = $this->getCurrentModelName();
        $this->class = $this->schema->getClass($this->name);
        $this->model = $this->schema->getInstance($this->name);
        $this->table = $this->model->getTable();
        $this->columns = $this->schema->getColumns($this->table);
        $this->fillable = $this->model->getFillable();

        $this->addFieldOptions($this->columns);
    }

    /**
     * Fills all fields in config if they are not declared and applies default attributes
     *
     * @param $columns
     * @param null $model
     * @return mixed
     */
    protected function addFieldOptions($columns, $model = null) {
        $model = ($model) ? $model : $this->name;

        $this->getOptions($model);

        foreach ($columns as $column) {
            if (! isset($this->options[$model]['fields'][$column])) {
                $this->options[$model]['fields'][$column] = [];
            }

            if (! isset($this->options[$model]['fields'][$column]['attributes'])) {
                $this->options[$model]['fields'][$column]['attributes'] = $this->options[$model]['attributes'];
            }
        }
    }

    /**
     * @return array
     */
    private function getCurrentModelData()
    {
        return array(
            'class' => $this->class,
            'name' => $this->getCurrentModelName(),
            'route' => $this->getRouteName(),
            'table' => $this->table,
            'columns' => $this->schema->columns,
            'fillable' => $this->fillable,
            'hidden' => $this->isHidden($this->name),
        );
    }

    /**
     * @param $model
     * @return bool
     */
    protected function isCurrent($modelName)
    {
        if ( ! Route::current()) return null;
        
        $slug = Route::current()->parameter('slug');

        return (isset($this->schema->models[$modelName]['route']) && $this->schema->models[$modelName]['route'] == $slug);
    }

    /**
     * @return Model
     */
    public static function getCurrent()
    {
        return (isset(self::$current)) ? self::$current : null;
    }

    /**
     * @param null $model
     * @return null
     */
    public function getRouteName($model = null)
    {
        if ($model) return $this->schema->models[$model]['route'];

        return (isset($this->schema->models[self::$current]['route'])) ? $this->schema->models[self::$current]['route'] : null;
    }

    /**
     * @param $modelName
     */
    protected function setCurrent($modelName)
    {
        self::$current = $modelName;
//        ModelOption::set('current', $modelName);
    }

    /**
     * @param null $model
     * @return mixed
     */
    public function getOptions($model = null)
    {
        $model = ($model) ? $model : $this->name;

        if (isset($this->options[$model])) return $this->options[$model];

        return $this->options[$model] = array_merge(
            $this->getDefaultOptions(),
            config('admin.models.' . $model, [])
        );
    }

    public function getDefaultOptions()
    {
        if ($this->defaultOptions) return $this->defaultOptions;

        return $this->defaultOptions = config('admin.model.default');
    }

    /**
     * @param $key
     * @param null $model
     * @return mixed
     */
    public function getOption($key, $default = null, $model = null)
    {
        $model = ($model) ? $model : $this->name;
        $options = $this->getOptions($model);

        return (isset($options[$key])) ? $options[$key] : $default;
    }

    /**
     * @return array|mixed
     */
    public function getRelations()
    {
        return $this->relations->get();
    }

}