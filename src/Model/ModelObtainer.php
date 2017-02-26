<?php

namespace Mascame\Artificer\Model;

use File;
use Mascame\Artificer\Options\AdminOption;

class ModelObtainer
{
    /**
     * @var array
     */
    private $models = [];

    /**
     * @param $directory
     * @return array
     */
    private function scanModelsDirectory($directory, $namespace = null)
    {
        $models = [];

        foreach (File::allFiles($directory) as $modelPath) {
            $modelName = $this->getFromFileName($modelPath);

            $models[$modelName] = $this->getModelBasics($modelName, $namespace);
        }

        return $models;
    }

    /**
     * @param $modelName
     * @param null $namespace
     * @return array
     */
    private function getModelBasics($modelName, $namespace = null)
    {
        return [
            'name' => $modelName,
            'namespace' => $namespace,
            'route' => $this->makeModelRoute($modelName),
            'fake' => false,
            'class' => $namespace.'\\'.$modelName,
        ];
    }

    /**
     * @param $modelName
     * @return string
     */
    private function makeModelRoute($modelName)
    {
        return strtolower($modelName);
    }

    /**
     * @param $models
     * @return array
     */
    private function mergeModelDirectories($models)
    {
        $mergedModels = [];

        foreach ($models as $key => $model) {
            foreach ($model as $name => $values) {
                $mergedModels[$name] = $values;
            }
        }

        return $mergedModels;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getModels()
    {
        if (! empty($this->models)) {
            return $this->models;
        }

        $models = [];
        $configModels = config('admin.model.models', []);

        foreach ($configModels as $model) {
            $pieces = explode('\\', $model);
            $modelName = end($pieces);

            $basicInfo = $this->getModelBasics($modelName, preg_replace('/\\\\'.$modelName.'$/', '', $model));

            $models[] = [$modelName => $basicInfo];
        }

        $modelDirectories = config('admin.model.directories', []);

        foreach ($modelDirectories as $namespace => $directory) {
            if (! file_exists($directory)) {
                throw new \Exception("Artificer can't find your models directory: '{$directory}'. Ensure that path exists and is properly set in your models config");
            }

            $models[] = $this->scanModelsDirectory($directory, $namespace);
        }

        $models = array_merge($this->mergeModelDirectories($models), $this->getFakeModels());

        return $models;
    }

    /**
     * @return array
     */
    private function getFakeModels()
    {
        $fakeModels = AdminOption::get('model.fake');
        $models = [];

        if (empty($fakeModels)) {
            return $models;
        }

        foreach ($fakeModels as $modelName => $modelData) {
            $models[$modelName] = [
                'name' => $modelName,
                'route' => $this->makeModelRoute($modelName),
                'fake' => array_merge($modelData, [
                    'model' => $modelName,
                ]),
            ];
        }

        return $models;
    }

    /**
     * @param $model
     * @return mixed
     */
    private function getFromFileName($model)
    {
        $pieces = explode('/', $model);

        return str_replace('.php', '', end($pieces));
    }
}
