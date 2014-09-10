<?php namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Model;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Options\FieldOption;
use Str;
use Mascame\Artificer\Options\AdminOption;

class Factory {

	public $fieldClass;
	public $types;
	public $type_reason;
	public $fields;
	public $modelObject;
	public $data;

	public static $loadedAssets = array();

	public $namespace = '\Mascame\Artificer\Fields\Types\\';

	public $classMap = array();

	/**
	 * @param Model $model
	 * @param $data
	 */
	public function __construct(Model $model, $data)
	{
		$this->data = $data;
		$this->types = AdminOption::get('types');

		$this->classMap = AdminOption::get('classmap');
		$this->modelObject = $model;

		$this->makeFields();
	}


	public function makeFields()
	{
		foreach ($this->modelObject->columns as $columnName) {
			$type = $this->autodetectType($columnName, $this->types);
			$this->fieldClass = $this->getFieldTypeClass($type);

			$value = (isset($this->data->$columnName)) ? $this->data->$columnName : null;
//            $columnOptions = $this->options[$this->modelObject->modelName];
//            $attributes = (isset($columnOptions['attributes'])) ? $columnOptions['attributes'] : array();

			$this->fields[$columnName] = new $this->fieldClass($columnName, $value, $this->modelObject->name);
		}
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
	public function autodetectType($name, $types)
	{
		if (FieldOption::has('type', $name)) {
			$this->type_reason[$name] = 'set by user in model fields';

			return FieldOption::get('type', $name);
		}

		if ($this->isTypeEqual($name, $types)) {
			$this->type_reason[$name] = 'equal';

			return $name;
		}

		if ($type = $this->isUserType($name, $types)) {
			$this->type_reason[$name] = 'set by user general fields';

			return $type;
		}

		if ($type = $this->isTypeSimilar($name, $types)) {
			$this->type_reason[$name] = 'similar';

			return $type;
		}

		$this->type_reason[$name] = 'default';

		return $types['default'][0];
	}

}