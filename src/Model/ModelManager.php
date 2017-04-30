<?php

namespace Mascame\Artificer\Model;

use Route;
use Illuminate\Support\Str as Str;

class ModelManager
{
    /**
     * @var null|string
     */
    private $current;

    /**
     * @var array
     */
    private $models;

    /**
     * ModelManager constructor.
     * @param ModelObtainer $modelObtainer
     */
    public function __construct(ModelObtainer $modelObtainer)
    {
        $this->models = $this->makeModels(
            $modelObtainer->getModels()
        );

        $this->current = $this->getCurrentModel();
        $params = \Request::all();

        if ($this->current && ! empty($params)) {
            $this->current()->setValues($params);
        }
    }

    /**
     * @param $models
     * @return mixed
     */
    private function makeModels($properties)
    {
        $models = [];

        foreach ($properties as $modelProperties) {
            $name = $modelProperties['name'];
            $models[$name] = new Model($modelProperties);
        }

        return $models;
    }

    /**
     * @return null|string
     */
    private function getCurrentModel()
    {
        if (! Str::startsWith(Route::currentRouteName(), 'admin.model.')) {
            return;
        }

        $model = collect($this->models)->first(function ($model, $modelName) {
            return $this->isCurrent($model->name);
        });

        return $model->name;
    }

    /**
     * @param $modelName
     * @return bool
     */
    private function isCurrent($modelName)
    {
        if (! Route::current()) {
            return false;
        }

        return $this->models[$modelName]->route == Route::current()->parameter('slug');
    }

    /**
     * @param $modelName
     * @return Model
     * @throws \Exception
     */
    public function get($modelName)
    {
        if (isset($this->models[$modelName])) {
            return $this->models[$modelName];
        }

        // Try to find it by class name
        $model = array_first($this->models, function ($value, $key) use ($modelName) {
            return $value->class == $modelName;
        });

        if (! $model) {
            throw new \Exception('Model '.$modelName.' not found.');
        }

        return $model;
    }

    /**
     * @return Model
     */
    public function current()
    {
        return $this->get($this->current);
    }

    /**
     * @param $modelName
     * @return bool
     */
    public function has($modelName)
    {
        return isset($this->models[$modelName]);
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->models;
    }
}
