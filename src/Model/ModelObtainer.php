<?php namespace Mascame\Artificer\Model;

use File;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Permit\ModelPermit;

class ModelObtainer
{


    /**
     * @var array
     */
    public $models;

    /**
     *
     */
    public function __construct()
    {
        $this->models = $this->getModels();
    }

    /**
     * @param $directory
     * @return array
     */
    protected function scanModelsDirectory($directory, $namespace = null)
    {
        $models = array();

        foreach (File::allFiles($directory) as $modelPath) {
            $modelName = $this->getFromFileName($modelPath);

            if (!ModelPermit::access($modelName)) {
                continue;
            }

            $models[$modelName] = array(
                'name' => $modelName,
                'namespace' => $namespace,
                'route' => $this->makeModelRoute($modelName),
                'fake' => false
            );
        }

        return $models;
    }

    /**
     * @param $modelName
     * @return string
     */
    protected function makeModelRoute($modelName)
    {
        return strtolower($modelName);
    }

    /**
     * @param $models
     * @return array
     */
    private function mergeModelDirectories($models)
    {
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
        if (!empty($this->models)) {
            return $this->models;
        }

        $models = array();
        $model_directories = AdminOption::get('models.directories');

        foreach ($model_directories as $namespace => $directory) {
            if (! file_exists($directory)) {
                throw new \Exception("Artificer can't find your models directory: '{$directory}'. Ensure that path exists and is properly set in your models config");
            }

            $models[] = $this->scanModelsDirectory($directory, $namespace);
        }

        $models = array_merge($this->mergeModelDirectories($models), $this->getFakeModels());

        return $models;
    }

    public function getFakeModels()
    {
        $fakeModels = AdminOption::get('models.fake');
        $models = array();

        if (empty($fakeModels)) {
            return $models;
        }

        foreach ($fakeModels as $modelName => $modelData) {
            $models[$modelName] = array(
                'name' => $modelName,
                'route' => $this->makeModelRoute($modelName),
                'fake' => array_merge($modelData, array(
                    'model' => $modelName
                ))
            );
        }

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