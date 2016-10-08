<?php

namespace Mascame\Artificer\Model;

use Schema;

// Todo: get column type http://stackoverflow.com/questions/18562684/how-to-get-database-field-type-in-laravel
class ModelSchema
{
    /**
     * @var array
     */
    public $tables;

    /**
     * @var array
     */
    public $models;

    /**
     * @var string
     */
    public $class;


    /**
     * @var array
     */
    public $columns;

    /**
     * @param ModelObtainer $modelObtainer
     */
    public function __construct(ModelObtainer $modelObtainer)
    {
        $this->models = $modelObtainer->models;
        $this->tables = $this->getTables($this->models);
    }

    /**
     * @param $column
     * @return bool
     */
    public function hasColumn($column)
    {
        return (is_array($column) && in_array($column, $this->columns)) ? true : false;
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
     * @return mixed
     */
    public function getTable($modelName)
    {
        if (! $modelName) {
            $modelName = ModelManager::getCurrent()->name;
        }

        if (isset($this->models[$modelName]['table'])) {
            return $this->models[$modelName]['table'];
        }

        return $this->getInstance($modelName)->getTable();
    }

    /**
     * @param $models
     * @return array
     */
    public function getTables($models)
    {
        $tables = [];

        foreach ($models as $model) {
            $table = $this->getTable($model['name']);
            $this->models[$model['name']]['table'] = $table;
            $tables[] = $table;
        }

        return $tables;
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
     * @return mixed
     */
    public function instantiate($modelName)
    {
        $modelClass = $this->getClass($modelName);

        if ($this->isFake($modelName)) {
            $instance = (new FakeModel())->setup($this->models[$modelName]['fake']);
        } else {
            $instance = new $modelClass;
        }

        return $this->models[$modelName]['instance'] = $instance;
    }

    public function isFake($modelName)
    {
        return isset($this->models[$modelName]['fake']) && $this->models[$modelName]['fake'] !== false;
    }

    /**
     * @param $modelName
     * @return bool
     */
    public function hasInstance($modelName)
    {
        return isset($this->models[$modelName]['instance']);
    }

    /**
     * @param null $modelName
     * @return mixed
     */
    public function getInstance($modelName = null)
    {
        ($modelName) ?: $modelName = ModelManager::getCurrent();

        if ($this->hasInstance($modelName)) {
            return $this->models[$modelName]['instance'];
        }

        return $this->instantiate($modelName);
    }

    /**
     * @param $modelName
     * @return string
     */
    public function getClass($modelName)
    {
        if (! in_array($modelName, array_keys($this->models))) {
            return;
        }

        $model = $this->models[$modelName];

        return $model['namespace'].'\\'.$model['name'];
    }
}
