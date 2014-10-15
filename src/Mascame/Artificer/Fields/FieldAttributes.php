<?php namespace Mascame\Artificer\Fields;

use Event;
use App;
use Mascame\Artificer\Localization;
use Mascame\Artificer\Options\ModelOption;
use Mascame\Artificer\Options\FieldOption;

class FieldAttributes {

    protected $options;
    protected $fieldOptions;
    
	public function __construct($options, FieldOptions $fieldOptions)
	{
		$this->options = $options;
        $this->fieldOptions = $fieldOptions;
	}

	/**
	 * @return array
	 */
	public function all()
	{
		return $this->fieldOptions->getExistent('attributes', array());
	}

	/**
	 * @param $key
	 * @return array
	 */
	public function get($key)
	{
		return (isset($this->options[$key])) ? $this->options[$key] : array();
	}

    public function add($attributes = array())
    {
        $this->fieldOptions->add('attributes', array_merge($this->all(), $attributes));

        return $this->fieldOptions->all();
    }
    
}