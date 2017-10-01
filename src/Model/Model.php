<?php

namespace Mascame\Artificer\Model;

use Illuminate\Support\Collection;
use Mascame\Artificer\Fields\Field;
use Mascame\Artificer\Fields\Types\Relations\Relation;
use Mascame\Formality\Parser;
use Mascame\Artificer\Fields\Factory;

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
     * @var ModelSettings
     */
    private $settings;

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

        if (! class_exists($this->class)) {
            throw new \Exception('Model class '. $this->class .' not found.');
        }

        $this->model = new $this->class;

        $this->applyMassAssignmentRules();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model
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
     * Returns a Collection with Fields
     *
     * @return Collection
     */
    public function toFields()
    {
        $fields = $this->settings()->getOption('fields');
        $types = config('admin.fields.types');

        return (new Factory(
            new Parser($types),
            $types,
            config('admin.fields.classmap')
        ))->makeFields($fields);
    }

    /**
     * Transform Fields to a collection ready to populate a form
     *
     * @param $fields Collection
     * @return Collection
     */
    public function transformFields($fields = null) {
        if (! $fields) $fields = $this->toFields();

        return $fields->filter(function($field) {
            /**
             * @var $field Field
             */
            return $field->isVisible();
        })->transform(function($field) {
            /**
             * @var $field Field
             */
            $options = $field->getOptions();

            $transform = [
                'wiki' => $field->getWiki(),
                'title' => $field->getTitle(),
                'type' => $field->getType(),
                'hasFilter' => $field->hasFilter(),
                'isRelation' => $field->isRelation(),
                'default' => $field->getDefault(),
            ];

            if ($field->isRelation()) {
                /**
                 * @var $field Relation
                 */
                $transform['relation'] = $options['relationship'];
                $transform['relation']['options'] = $field->getRelationOptions();

                unset($options['relationship']);
            }

            return $transform;
        });
    }

    /**
     * @param $fields Collection|\Illuminate\Support\Collection
     * @param $values Collection|\Illuminate\Support\Collection
     * @return Collection|\Illuminate\Support\Collection
     */
    public function transformValues($values, $fields = null) {
        if (! $fields) $fields = $this->toFields();

        /**
         * @var $values Collection
         */
        return $values->transform(function($row) use ($fields) {
            /**
             * @var $row \Eloquent
             */
            $row->setHidden([]); // Allows us to get all values. TODO: should this be an option?

            $values = $row->toArray();

            foreach ($values as $name => &$value) {
                /**
                 * @var $field Field
                 */
                $field = $fields[$name];

                if (! $field->isVisible()) {
                    unset($values[$name]);
                    continue;
                }

                if ($field->isRelation()) {
                    /**
                     * @var $field Relation
                     */
                    $value = $field->transformToVisibleProperties(collect($value));
                } else {
                    $value = $field->transformValue($value);
                }
            }

            return $values;
        });
    }

    /**
     * Prepared data to be inserted on DB
     *
     * @return array
     */
    public function serialize()
    {
        $fields = $this->toFields();
        $values = request()->only($fields->keys()->toArray());

        $serialized = [
            'currentModel' => [],
            'relations' => [],
        ];

        /**
         * @var $field Field
         */
        foreach ($fields as $name => $field) {
            if ($field->isRelation()) {

                /**
                 * @var $field Relation
                 */
                $relationValues = [];

                if ($field->getType() === 'hasMany') {
                    $relationValues = $field->relatedModel->model->whereIn('id', $values[$name])->get();
                }

                $serialized['relations'][] = [
                    'name' => $name,
                    'type' => $field->getType(),
                    'model' => $field->relatedModel,
                    'values' => $relationValues
                ];
            } else {
                // Todo: without isset -> Undefined index: id.
                // Can this have unexpected consequences?
                if (isset($values[$name])) {
                    $serialized['currentModel'][$name] = $values[$name];
                }
            }
        }

        return $serialized;
    }

    /**
     * @param $name
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
