<?php

namespace Mascame\Artificer\Fields;

use Str;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\FieldOption;

class FieldFactory
{
    public $fieldClass;
    public $fields;
    public $related_fields = null;
    public $custom_fields = null;

    /**
     * @var FieldParser
     */
    public $parser;

    /**
     * @var Model
     */
    public $modelObject;
    public $data;

    public $namespace = '\Mascame\Artificer\Fields\Types\\';

    public $classMap = [];

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->classMap = AdminOption::get('classmap');
        $this->modelObject = $model;
        $this->parser = new FieldParser();
    }

    /**
     * @param $type
     * @param $field
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function make($type, $field, $value)
    {
        $fieldClass = $this->getFieldTypeClass($type);

        return new $fieldClass($field, $value, $this->modelObject->name, $this->isRelation($field));
    }

    /**
     * @param $type
     * @throws \Exception
     */
    public function getFieldTypeClass($type)
    {
        if (isset($this->classMap[$type])) {
            return $this->classMap[$type];
        } elseif (class_exists($this->namespace.Str::studly($type))) {
            return $this->namespace.Str::studly($type);
        }

        throw new \Exception("No supported Field type [{$type}]");
    }

    /**
     * @param $data
     * @return mixed
     */
    public function makeFields($data)
    {
        $this->data = $data;

        $this->withCustomFields();

        foreach ($this->withRelated() as $field) {
            $this->fields[$field] = $this->make($this->parser->fieldType($field), $field, $this->fieldValue($field));
        }

        return $this->fields;
    }

    /**
     * @param $name
     * @return bool
     */
    protected function isRelation($name)
    {
        return in_array($name, $this->related_fields);
    }

    /**
     * @return array|null
     */
    public function getRelated()
    {
        if ($this->related_fields != null) {
            return $this->related_fields;
        }

        if (null == $fields = FieldOption::all()) {
            return $this->related_fields = [];
        }

        /*
         * We compare columns with config array to determine if there are new fields
         */
        $this->related_fields = array_diff(array_keys($fields), $this->modelObject->columns);

        return $this->related_fields;
    }

    /**
     * @return array
     */
    protected function addRelated()
    {
        $related = $this->getRelated();

        if (! empty($related)) {
            foreach ($related as $field) {
                $this->modelObject->columns[] = $field;
            }
        }

        return $this->modelObject->columns;
    }

    /**
     * @return array
     */
    protected function withRelated()
    {
        return $this->addRelated();
    }

    /**
     * @return array
     */
    protected function addCostumFields()
    {
        if (isset($this->modelObject->options['fields'])) {
            foreach ($this->modelObject->options['fields'] as $name => $data) {
                if (! in_array($name, $this->modelObject->columns)) {
                    $this->modelObject->columns[] = $name;
                }
            }
        }

        return $this->modelObject->columns;
    }

    /**
     * @return array
     */
    protected function withCustomFields()
    {
        return $this->addCostumFields();
    }

    /**
     * @param $field
     * @return null
     */
    public function fieldValue($field)
    {
        return (isset($this->data->$field)) ? $this->data->$field : null;
    }
}
