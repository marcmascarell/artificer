<?php namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Model\Model;
use \Illuminate\Support\Str as Str;
use Mascame\Artificer\Fields\Field as FieldWrapper;
use Mascame\Formality\Field\Field;

class FieldFactory extends \Mascame\Formality\Factory\Factory
{

    public $fieldClass;
    public $fields;
    public $relatedFields = null;
    public $custom_fields = null;

    /**
     * @var Model
     */
    public $modelObject;
    public $data;

    public $namespace = '\Mascame\Artificer\Fields\Types\\';

    /**
     * @param $data
     * @return mixed
     */
    public function makeFields()
    {
        $fields = parent::makeFields();

        foreach($fields as $key => $field) {
            /**
             * @var $field Field
             */
            $field->setOptions([
                'attributes' => [
                    'class' => 'form-control'
                ]
            ]);

            $fields[$key] = new FieldWrapper($field);
        }

        return $fields;
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