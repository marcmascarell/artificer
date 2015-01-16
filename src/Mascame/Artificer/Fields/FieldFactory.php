<?php namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Options\FieldOption;
use Str;
use Mascame\Artificer\Options\AdminOption;

class FieldFactory {

	public $fieldClass;
	public $fields;
	public $related_fields = null;

    /**
     * @var Model
     */
	public $modelObject;
	public $data;

	public $namespace = '\Mascame\Artificer\Fields\Types\\';

	public $classMap = array();

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
    public function make($type, $field, $value) {
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
		} else if (class_exists($this->namespace . Str::studly($type))) {
			return $this->namespace . Str::studly($type);
		}

		throw new \Exception("No supported Field type [{$type}]");
	}

	public function makeFields($data)
	{
		$this->data = $data;

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
		if ($this->related_fields != null) return $this->related_fields;

		if (null == $fields = FieldOption::all()) {
			return $this->related_fields = array();
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

		if (!empty($related)) {
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
	 * @param $field
	 * @return null
	 */
	public function fieldValue($field) {
		return (isset($this->data->$field)) ? $this->data->$field : null;
	}

}