<?php

namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\FieldOption;
use Mascame\Artificer\Options\ModelOption;

class FieldOptions
{
    protected $name;
    protected $options;
    protected $default_options;
    public $model;

    /**
     * @param $name
     */
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->default_options = (AdminOption::get('types.'.$type)) ? AdminOption::get('types.'.$type) : [];
        $this->options = array_merge($this->all(), $this->default_options);

        $this->model = $this->model();
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->options[$key]);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->options[$key];
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
        if (isset($this->options)) {
            return (array) $this->options = array_merge($this->options, FieldOption::field($this->name));
        }

        return (array) $this->options = FieldOption::field($this->name);
    }

    /**
     * @return array|mixed
     */
    public function model()
    {
        if (isset($this->model)) {
            return $this->model;
        }

        $model = ModelOption::all();
        $default_model = ModelOption::getDefault();

        return $this->model = (! empty($model)) ? array_merge_recursive($model, $default_model) : ModelOption::getDefault();
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
    public function getExistent($key, $default = [])
    {
        if ($this->has($key)) {
            return $this->get($key);
        }

        return $default;
    }
}
