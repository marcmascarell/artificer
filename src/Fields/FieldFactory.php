<?php namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Options\FieldOption;
use \Illuminate\Support\Str as Str;

class FieldFactory
{

    public $fieldClass;
    public $fields;
    public $relatedFields = null;
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

    public $classMap = array();

    /**
     * @param $type
     * @param $field
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function make($type, $field, $value)
    {
        $fieldClass = $this->fieldClass = $this->getFieldTypeClass($type);

        return new $fieldClass($field, $value, $this->isRelation($field));
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

            $type = $this->getTypeFromConfig($field);

            if ( ! $type) $type = $this->parser->parse($field);

            $this->fields[$field] = $this->make(
                $type,
                $field,
                $this->fieldValue($field)
            );
        }

        return $this->fields;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    protected function getTypeFromConfig($name) {
        if (FieldOption::has('type', $name) || FieldOption::has('relationship.type', $name)) {
            return (FieldOption::has('type', $name)) ?
                FieldOption::get('type', $name) :
                FieldOption::get('relationship.type', $name);
        }

        return false;
    }

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
    public function getRelated()
    {
        if ($this->relatedFields) return $this->relatedFields;

        if (null == $fields = FieldOption::all()) {
            return $this->relatedFields = [];
        }

        /*
         * We compare columns with config array to determine if there are new fields
         */
        $this->relatedFields = array_diff(array_keys($fields), $this->modelObject->columns);

        return $this->relatedFields;
    }

    /**
     * @return array
     */
    protected function withRelated()
    {
        $related = $this->getRelated();

        if ( ! empty($related)) {
            foreach ($related as $field) {
                $this->modelObject->columns[] = $field;
            }
        }

        return $this->modelObject->columns;
    }

    /**
     * @return array
     */
    protected function withCustomFields()
    {
        if (isset($this->modelObject->options['fields'])) {

            foreach ($this->modelObject->options['fields'] as $name => $data) {
                if ( ! in_array($name, $this->modelObject->columns)) {
                    $this->modelObject->columns[] = $name;
                }
            }

        }

        return $this->modelObject->columns;
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