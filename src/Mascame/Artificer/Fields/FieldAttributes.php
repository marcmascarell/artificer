<?php namespace Mascame\Artificer\Fields;

use Event;
use App;
use Mascame\Artificer\Localization;
use Mascame\Artificer\Options\ModelOption;
use Mascame\Artificer\Options\FieldOption;

class FieldAttributes {

    protected $options;
    protected $fieldOption;
    
	public function __construct($options, $fieldOption)
	{
		$this->options;
        $this->fieldOption = $fieldOption;
	}

	/**
	 * @return array
	 */
	public function all()
	{
		return $this->fieldOption->getExistent('attributes', array());
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
        $this->fieldOption->add('attributes', array_merge($this->all(), $attributes));

        return $this->fieldOption->all();
    }
    
}