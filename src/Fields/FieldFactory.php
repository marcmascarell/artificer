<?php

namespace Mascame\Artificer\Fields;

use Illuminate\Support\Str as Str;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Fields\Types\Relations\Relation;

class FieldFactory extends \Mascame\Formality\Factory\Factory
{
    public $fieldClass;
    public $fields;
    public $relatedFields = null;
    public $custom_fields = null;

    /**
     * @var ModelManager
     */
    public $modelObject;
    public $defaultOptions;
    public $data;

    public $artificerFieldsNamespace = '\Mascame\Artificer\Fields\Types\\';

    /**
     * @param $data
     * @return mixed
     */
    public function makeFields()
    {
        $fields = parent::makeFields();

        foreach ($fields as $key => $field) {
            if (is_a($field, \Mascame\Artificer\Fields\Types\Relations\Relation::class)) {
                $field = $this->completeRelation($field);
            }

//            $fields[$key] = new FieldWrapper($field);
        }

        return $fields;
    }

    protected function getFieldTypeClass($type, $namespace)
    {
        $typeClass = parent::getFieldTypeClass($type, $this->artificerFieldsNamespace);

        if (! $typeClass) {
            $typeClass = parent::getFieldTypeClass($type, $namespace);
        }

        return $typeClass;
    }

    /**
     * @param $field Relation
     * @return mixed
     */
    public function completeRelation($field)
    {
        $relationship = $field->getOption('relationship', []);

        $completedRelation = [
            'method' => $field->guessRelatedMethod(),
            'type' => $field->getType(),
            'model' => $field->guessModel(),
            'show' => function ($value) {
                if (! is_array($value) && method_exists($value, 'toArray')) {

                    // Avoids cryptic errors
                    try {
                        $value = $value->toArray();
                    } catch (\Exception $e) {
                        var_dump($e->getMessage());
                    }
                }

                // Jump to next column avoiding 'id'
                return array_values(array_slice($value, 1, 1))[0];
            },
        ];

        // user config takes preference
        $field->setOptions(['relationship' => array_merge($completedRelation, $relationship)]);

        return $field;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
//    protected function getTypeFromConfig($name) {
//        if (FieldOption::has('type', $name) || FieldOption::has('relationship.type', $name)) {
//            return (FieldOption::has('type', $name)) ?
//                FieldOption::get('type', $name) :
//                FieldOption::get('relationship.type', $name);
//        }
//
//        return false;
//    }

    /**
     * @param $name
     * @return bool
     */
    protected function isRelation($name)
    {
        return Str::contains($this->fieldClass, '\\Relations\\') || in_array($name, $this->relatedFields);
    }

    /**
     * @return array|null
     */
//    public function getRelated()
//    {
//        if ($this->relatedFields) {
//            return $this->relatedFields;
//        }
//
//        if (null == $fields = FieldOption::all()) {
//            return $this->relatedFields = [];
//        }
//
//        /*
//         * We compare columns with config array to determine if there are new fields
//         */
//        $this->relatedFields = array_diff(array_keys($fields), $this->modelObject->columns);
//
//        return $this->relatedFields;
//    }

    /**
     * @return array
     */
//    protected function withRelated()
//    {
//        $related = $this->getRelated();
//
//        if (! empty($related)) {
//            foreach ($related as $field) {
//                $this->modelObject->columns[] = $field;
//            }
//        }
//
//        return $this->modelObject->columns;
//    }

    /**
     * @return array
     */
//    protected function withCustomFields()
//    {
//        if (isset($this->modelObject->options['fields'])) {
//
//            foreach ($this->modelObject->options['fields'] as $name => $data) {
//                if ( ! in_array($name, $this->modelObject->columns)) {
//                    $this->modelObject->columns[] = $name;
//                }
//            }
//
//        }
//
//        return $this->modelObject->columns;
//    }

    /**
     * @param $field
     * @return null
     */
    public function fieldValue($field)
    {
        return (isset($this->data->$field)) ? $this->data->$field : null;
    }
}
