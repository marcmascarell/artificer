<?php

namespace Mascame\Artificer\Model;

use Schema;

// Todo: get column type http://stackoverflow.com/questions/18562684/how-to-get-database-field-type-in-laravel
class ModelSchema
{
    /**
     * @var \Eloquent
     */
    private $model;

    /**
     * @var string
     */
    private $modelName;

    /**
     * @var array
     */
    private $columns;

    /**
     * ModelSchema constructor.
     * @param $model
     * @param $modelName
     */
    public function __construct(\Illuminate\Database\Eloquent\Model $model, $modelName)
    {
        $this->model = $model;
        $this->modelName = $modelName;
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
     * @return string
     */
    public function getTable()
    {
        return $this->model->getTable();
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return Schema::getColumnListing($this->getTable());
    }
}
