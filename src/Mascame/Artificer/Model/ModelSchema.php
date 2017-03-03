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
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $columns;

    /**
     * @var array|mixed
     */
    public $options = [];

    /**
     * @var
     */
    public static $current = null;

    public function __construct(ModelObtainer $modelObtainer)
    {
        $this->models = $modelObtainer->models;
        $this->tables = $this->getTables($this->models);
    }

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
    public function getTable($modelName = null)
    {
        if (! $modelName) {
            $modelName = $this->name;
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

        return $this->models[$modelName]['instance'] = new $modelClass;
    }

    /**
     * @param $modelName
     * @return bool
     */
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
     * @param $modelName
     * @return string
     */
    public function getClass($modelName)
    {
        if (false !== $key = array_search($modelName, array_keys($this->models))) {
            $modelName = array_keys($this->models);
            $modelName = $modelName[$key];
        }

        return '\\'.$modelName;
    }
}
