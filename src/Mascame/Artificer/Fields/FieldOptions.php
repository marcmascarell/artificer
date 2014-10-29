<?php namespace Mascame\Artificer\Fields;

use Event;
use App;
use Mascame\Artificer\Localization;
use Mascame\Artificer\Options\ModelOption;
use Mascame\Artificer\Options\FieldOption;

class FieldOptions {

    protected $name;
    protected $options;
    public $model;

	public function __construct($name)
	{
        $this->name = $name;
        $this->options = $this->all();
        $this->model = $this->model();
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function has($key)
	{
		return FieldOption::has($key, $this->name);
	}


	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		return FieldOption::get($key, $this->name);
	}


	/**
	 * @param string $key
	 * @param $value
	 */
	public function set($key, $value)
	{
		FieldOption::set($key, $value, $this->name);

        // Refresh options
        return $this->all();
	}


	/**
	 * @return array|mixed
	 */
	public function all()
	{
		return (array) $this->options = FieldOption::field($this->name);
	}


	/**
	 * @return array|mixed
	 */
	public function model()
	{
		return $this->model = ModelOption::all();
	}

	/**
	 * @param string $key
	 * @param $value
	 * @return array|mixed
	 */
	public function add($key, $value)
	{
		$this->set($key, $value);

		// Refresh options
		return $this->all();
	}

    /**
     * @param $key
     * @param array $default
     * @return array|mixed
     */
    public function getExistent($key, $default = array()) {
        if ($this->has($key)) return $this->get($key);

        return $default;
    }
}