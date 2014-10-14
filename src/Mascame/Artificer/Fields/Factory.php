<?php namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Options\FieldOption;
use Mascame\Artificer\Options\ModelOption;
use Str;
use Mascame\Artificer\Options\AdminOption;

class Factory {

	public $fieldClass;
	public $types;
	public $type_reason;
	public $fields;
	public $related_fields = null;
	public $modelObject;
	public $data;

	public $namespace = '\Mascame\Artificer\Fields\Types\\';

	public $classMap = array();

	/**
	 * @param Model $model
	 * @param $data
	 */
	public function __construct(Model $model)
	{
		$this->types = AdminOption::get('types');

		$this->classMap = AdminOption::get('classmap');
		$this->modelObject = $model;
	}


    public function parseFieldType($field) {
        return $this->autodetectType($field);
    }

    public function parseFieldValue($field) {
        return (isset($this->data->$field)) ? $this->data->$field : null;
    }

	public function parseFields($data)
	{
        $this->data = $data;

		foreach ($this->withRelated() as $field) {
            $this->fields[$field] = $this->make($this->parseFieldType($field), $field, $this->parseFieldValue($field));
		}

        return $this->fields;
	}

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

        return false;
	}

	/**
	 * @param $name
	 * @param $types
	 * @return bool
	 */
	public function isTypeEqual($name, $types)
	{
		return (in_array($name, array_keys($types))) ? $name : false;
	}

	/**
	 * @param $name
	 * @param $types
	 * @return bool|mixed
	 */
	public function isTypeSimilar($name, $types)
	{
		$points = array();

		foreach ($types as $type => $fields) {
			$points[$type] = 0;

			if ($this->isSimilar($name, $type)) {
				// Gives more importance to similar TYPE than field
				$points[$type] = + 2;
			}

			foreach ($fields as $field) {
				if ($this->isSimilar($name, $field)) {
					$points[$type] ++;
				}
			}
		}

		if (max($points) > 0) {
			return array_search(max($points), $points);
		}

		return false;
	}

	/**
	 * @param $haystack
	 * @param $needle
	 * @return bool
	 */
	public function isSimilar($haystack, $needle)
	{
		return Str::startsWith($haystack, $needle)
		|| Str::endsWith($haystack, $needle)
		|| Str::contains($haystack, $needle) ? true : false;
	}

	public function isUserType($name, $types)
	{
		foreach ($types as $type => $fields) {
			if (in_array($name, $fields)) {
				return $type;
			}
		}

		return false;
	}

	/**
	 * @param $name
	 * @param $types
	 * @return bool|int|mixed|string
	 */
	public function autodetectType($name)
	{
		if (FieldOption::has('type', $name) || FieldOption::has('relationship.type', $name)) {
			$this->type_reason[$name] = 'set by user in {model}.fields';

			return (FieldOption::has('type', $name)) ? FieldOption::get('type', $name) : FieldOption::get('relationship.type', $name);
		}

		if ($this->isTypeEqual($name, $this->types)) {
			$this->type_reason[$name] = 'equal';

			return $name;
		}

		if ($type = $this->isUserType($name, $this->types)) {
			$this->type_reason[$name] = 'set by user in admin.fields';

			return $type;
		}

		if ($type = $this->isTypeSimilar($name, $this->types)) {
			$this->type_reason[$name] = 'similar to one in admin.fields';

			return $type;
		}

		$this->type_reason[$name] = 'default';

		return $this->types['default'][0];
	}

	protected function isRelation($name)
	{
		return in_array($name, $this->related_fields);
	}

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

	protected function withRelated()
	{
		return $this->addRelated();
	}

}