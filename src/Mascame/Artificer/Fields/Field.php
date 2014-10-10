<?php namespace Mascame\Artificer\Fields;

use Event;
use Route;
use Mascame\Artificer\Options\ModelOption;
use Mascame\Artificer\Options\FieldOption;

abstract class Field implements FieldInterface {

	public $type;
	public $title;
	public $name;
	public $modelName;
	public $configKey;
	public $configFieldKey;
	public $value;
	public $output;
	public static $widgets = array();
	public $options = array();
	public $fieldOptions = array();
	public $lists = array();
	public $relation = false;
	public $wiki;


	/**
	 * @param $name
	 * @param null $value
	 * @param $modelName
	 * @param $relation
	 */
	public function __construct($name, $value = null, $modelName, $relation)
	{
		$this->name = $name;
		$this->value = $value;
		$this->modelName = $modelName;

		$this->getOptions();
		$this->getFieldOptions();

		$this->relation = ($relation || $this->getRelationType()) ? true : false;

		$this->addAttributes(array('class' => 'form-control'));

		$this->title = $this->getTitle($this->name);
		$this->type = $this->getType(get_called_class());
		$this->wiki = $this->getWiki();

		$this->boot();
	}

	public function getWiki()
	{
		return (isset($this->fieldOptions['wiki'])) ? $this->fieldOptions['wiki'] : null;
	}


	/**
	 * @param $key
	 * @return bool
	 */
	public function fieldHas($key)
	{
		return FieldOption::has($key, $this->name);
	}


	/**
	 * @param $key
	 * @return mixed
	 */
	public function fieldOption($key)
	{
		return FieldOption::get($key, $this->name);
	}


	/**
	 * @param $key
	 * @param $value
	 */
	public function fieldSet($key, $value)
	{
		FieldOption::set($key, $value, $this->name);
	}


	/**
	 * @return array|mixed
	 */
	public function getFieldOptions()
	{
		$this->fieldOptions = FieldOption::field($this->name);

		return $this->fieldOptions;
	}


	/**
	 * @return array|mixed
	 */
	public function getOptions()
	{
		$this->options = ModelOption::all();

		return $this->options;
	}


	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return (isset($this->fieldOptions['attributes'])) ? $this->fieldOptions['attributes'] : array();
	}

	/**
	 * @param $key
	 * @return array
	 */
	public function getAttribute($key)
	{
		return (isset($this->fieldOptions['attributes'][$key])) ? $this->fieldOptions['attributes'][$key] : array();
	}


	/**
	 * @param array $attributes
	 * @return array
	 */
	public function addAttributes($attributes = array())
	{
		$this->addFieldOption('attributes', array_merge($this->getAttributes(), $attributes));

		// Refresh options
		$this->getFieldOptions();

		return $this->getAttributes();
	}


	/**
	 * @param $key
	 * @param $value
	 * @return array|mixed
	 */
	public function addFieldOption($key, $value)
	{
		$this->fieldSet($key, $value);

		// Refresh options
		return $this->getFieldOptions();
	}

	/**
	 * @param $type_class
	 * @return string
	 */
	public function getType($type_class)
	{
        $pieces = explode('\\', $type_class);

		return strtolower(end($pieces));
	}


	/**
	 * @param $widget
	 * @return bool
	 */
	public function addWidget($widget)
	{
		if (!in_array($widget->name, self::$widgets)) {
			self::$widgets[$widget->name] = $widget;

			return true;
		}

		return false;
	}


	/*
	 * Used to load custom assets, widgets, ...
	 */
	public function boot()
	{
		return false;
	}


    /**
     * @return null
     */
	public function show()
	{
		return $this->value;
	}

    /**
     * @param null $value
     * @return null
     */
    public function display($value = null)
    {
        $this->getValue($value);

        return $this->show($value);
    }


	/**
	 * @param null $value
	 * @return null
	 */
	public function getValue($value = null)
	{
        if (!$value) {
			$value = $this->fieldOption('default');
        }

		return $this->value = $value;
	}


	/**
	 * @return bool
	 */
	public function input()
	{
		return false;
	}


	/**
	 * @param $input
	 * @return mixed
	 */
	public function userInput($input)
	{
		$input = str_replace('(:value)', $this->value, $input);
		$input = str_replace('(:name)', $this->name, $input);
		$input = str_replace('(:label)', $this->title, $input);

		return $input;
	}


	/**
	 * @return bool|mixed|null|string
	 */
	public function output()
	{
		Event::fire('artificer.field.' . $this->type . '.output', $this->value);

		if ($this->isHidden()) {
			return null;
		} else if ($this->isGuarded()) {
			return $this->guarded();
		}

        $this->value = $this->getValue();

		if (isset($this->fieldOptions['input'])) {
			return $this->userInput($this->fieldOptions['input']);
		}

		return $this->input();
	}


	/**
	 * @return string
	 */
	public function hidden()
	{
		return '<div class="label label-warning">Hidden data</div>';
	}


	/**
	 * @return bool
	 */
	public function hasList($list = 'list')
	{
		if (isset($this->options[$list])) {
			return true;
		}

		return false;
	}


	/**
	 * @param string $list
	 * @return bool
	 */
	public function isListed($list = 'list')
	{
		$list = ($this->hasList($list)) ? $this->options[$list] : array();

		if ($list == '*' || is_array($list) && isset($list[0]) && $list[0] == '*') {
			return true;
		}

		return $this->isInArray($this->name, $list);
	}


	/**
	 * @return bool
	 */
	public function isHiddenList()
	{
		return $this->isListed('list-hide');
	}


	/**
	 * @param $value
	 * @param $array
	 * @return bool
	 */
	public function isInArray($value, $array)
	{
		return (is_array($array) && in_array($value, $array)) ? true : false;
	}


	/**
	 * @return string
	 */
	public function guarded()
	{
		return '(guarded) ' . $this->show();
	}


	/**
	 * @param $name
	 * @return mixed
	 */
	public function getTitle($name)
	{
		if ($this->fieldHas('title')) {
			return $this->fieldOption('title');
		}

		return $name;
	}


	/**
	 * @return bool
	 */
	public function isGuarded()
	{
		return $this->isInArray($this->name, ModelOption::get('guarded'));
	}


	/**
	 * @return bool
	 */
	public function isHidden()
	{
		return $this->isInArray($this->name, ModelOption::get('hidden'));
	}

	/**
	 * @return bool
	 */
	public function isRelation()
	{
		return $this->relation;
	}

	public function getRelationMethod()
	{
		return isset($this->fieldOptions['relationship']['method']) ? $this->fieldOptions['relationship']['method'] : false;
	}

	public function getRelatedModel()
	{
		return isset($this->fieldOptions['relationship']['model']) ? $this->fieldOptions['relationship']['model'] : false;
	}

	public function getRelationType()
	{
		return isset($this->fieldOptions['relationship']['type']) ? $this->fieldOptions['relationship']['type'] : false;
	}

	public function getRelationForeignKey()
	{
		return isset($this->fieldOptions['relationship']['foreign']) ? $this->fieldOptions['relationship']['foreign'] : false;
	}
}