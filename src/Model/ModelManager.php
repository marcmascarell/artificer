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
        $this->models = $modelObtainer->getModels();
        $this->current = $this->getCurrentModel();
    }

    /**
     * @return null|string
     */
    private function getCurrentModel()
    {
        if (! Str::startsWith(Route::currentRouteName(), 'admin.model.')) {
            return;
        }

        return collect($this->models)->first(function ($values, $modelName) {
            return $this->isCurrent($modelName);
        });
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

        $slug = Route::current()->parameter('slug');

        return isset($this->models[$modelName]['route']) && $this->models[$modelName]['route'] == $slug;
    }

    /**
     * @param $modelName
     * @return ModelSettings
     */
    public function get($modelName)
    {
        $model = $this->models[$modelName];
        $eloquent = new $model['class'];

        return new ModelSettings($eloquent, $model);
    }

    /**
     * @return ModelSettings
     */
    public function current()
    {
        return $this->get($this->current['name']);
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
        $models = [];

        foreach (array_keys($this->models) as $modelName) {
            $models[$modelName] = $this->get($modelName);
        }

        return $models;
    }
}
