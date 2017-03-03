<?php

namespace Mascame\Artificer\Model;

use File;
use Mascame\Artificer\Permit\ModelPermit;
use Mascame\Artificer\Options\AdminOption;

class ModelObtainer
{
    /**
     * @var array
     */
    public $models;

    public function __construct()
    {
        $this->models = $this->getModels();
    }

    /**
     * @param $directory
     * @return array
     */
    protected function scanModelsDirectory($directory)
    {
        $models = [];

        foreach (File::allFiles($directory) as $modelPath) {
            $modelName = $this->getFromFileName($modelPath);

            if (! ModelPermit::access($modelName)) {
                continue;
            }

            $models[$modelName] = ['name'  => $modelName,
                                        'route' => strtolower($modelName), ];
        }

        return $models;
    }

    /**
     * @param $models
     * @return array
     */
    private function mergeModelDirectories($models)
    {
        $merged_models = [];

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
        if (! empty($this->models)) {
            return $this->models;
        }

        $models = [];
        $model_directories = AdminOption::get('models.directories');

        foreach ($model_directories as $directory) {
            $models[] = $this->scanModelsDirectory($directory);
        }

        $models = $this->mergeModelDirectories($models);

        return $models;
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
}
