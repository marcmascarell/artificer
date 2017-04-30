<?php

namespace Mascame\Artificer\Model;

use Illuminate\Support\Collection;
use Mascame\Formality\Parser\Parser;
use Mascame\Artificer\Fields\FieldFactory;

/**
 * @property $name
 * @property $route
 * @property $table
 * @property $class
 * @property $model
 */
class Model
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    private $model;

    /**
     * @var string
     */
    private $class;

    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $route;

    /**
     * @var
     */
    private $values = null;

    /**
     * @var ModelSettings
     */
    private $settings;

    /**
     * @var Collection
     */
    private $fields;

    /**
     * For commodity (to avoid making a bunch of getters).
     *
     * @var array
     */
    private $visibleProperties = [
        'name',
        'route',
        'class',
        'model',
        'relations',
    ];

    /**
     * Model constructor.
     * @param $modelProperties
     */
    public function __construct($modelProperties)
    {
        foreach ($modelProperties as $property => $value) {
            $this->{$property} = $value;
        }

        $this->model = new $this->class;

        $this->applyMassAssignmentRules();
    }

    /**
     * @return mixed
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * @return ModelSettings
     */
    public function settings()
    {
        if ($this->settings) {
            return $this->settings;
        }

        return $this->settings = new ModelSettings($this->model, $this->name);
    }

    private function applyMassAssignmentRules()
    {
        $this->model()->guard($this->settings()->getGuarded());
        $this->model()->fillable($this->settings()->getFillable());
    }

    /**
     * @param \Eloquent|null $values
     * @return mixed
     */
    public function toForm($values = null)
    {
        $values = $values ?? $this->values;

        $modelFields = $this->settings()->getOption('fields');
        $types = config('admin.fields.types');
        $fields = [];

        foreach ($this->settings()->columns as $column) {
            $options = [];

            if (isset($modelFields[$column])) {
                $options = $modelFields[$column];
            }

            // Get eloquent value
            if (is_object($values)) {
                $options['value'] = $values->$column;
            } elseif (is_array($values)) {
                $options['value'] = $values[$column] ?? null;
            }

            $fields[$column] = $options;
        }

        $fieldFactory = new FieldFactory(new Parser($types), $types, $fields, config('admin.fields.classmap'));

        return collect($fieldFactory->makeFields());
    }

    public function serialize()
    {
        $fields = $this->toForm();
        $serialized = [];

        foreach ($fields as $name => $field) {
            $serialized[$name] = $field->getValue();
        }

        return $serialized;
    }

    /**
     * @param $values
     * @return $this
     */
    public function withValues($values)
    {
        $this->setValues($values);

        return $this;
    }

    /**
     * @param $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     * @param $name+
     * @return mixed
     */
    public function __get($name)
    {
        if (! in_array($name, $this->visibleProperties)) {
            throw new \InvalidArgumentException();
        }

        return $this->$name;
    }
}
