<?php namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\FieldOption;
use Mascame\Artificer\Options\ModelOption;

class FieldOptions
{

    protected $name;
    protected $options;
    protected $defaultOptions;
    public $model;

    /**
     * @param $name
     */
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->defaultOptions = (AdminOption::has('fields.types.' . $type)) ? AdminOption::get('fields.types.' . $type) : [];
        $this->model = $this->model();

        $this->options = array_merge($this->modelField(), $this->defaultOptions, $this->all() );
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return (isset($this->options[$key]));
    }


    /**
     * @param string $key
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->options[$key];
        }

        return $default;
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
        if (isset($this->options)) return $this->options;
//        if (isset($this->options)) {
//            return (array)$this->options = array_merge($this->options, FieldOption::field($this->name));
//        }

        return (array)$this->options = FieldOption::field($this->name);
    }


    /**
     * @return array|mixed
     */
    public function model()
    {
        if (isset($this->model)) return $this->model;

        $model = config('admin.models.' . Model::getCurrent());

        $defaultModel = ModelOption::getDefault();

        return $this->model = ( ! empty($model)) ? array_merge_recursive($model, $defaultModel) : ModelOption::getDefault();
    }

    public function modelField()
    {
        return (isset($this->model['fields'][$this->name])) ? $this->model['fields'][$this->name] : [];
    }
    /**
     * @param string $key
     * @param $value
     * @return array|mixed
     */
//    public function add($key, $value)
//    {
//        $this->set($key, $value);
//
//        // Refresh options
//        return $this->all();
//    }
}