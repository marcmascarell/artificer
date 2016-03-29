<?php namespace Mascame\Artificer\Fields;

class FieldAttributes
{

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
        return $this->fieldOptions->get('attributes', []);
    }

    /**
     * @param $key
     * @return array
     */
    public function get($key)
    {
        return (isset($this->options[$key])) ? $this->options[$key] : [];
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return (isset($this->options[$key]));
    }

    /**
     * @param array $attributes
     */
    public function add($attributes = [])
    {
        $currentAttributes = $this->all();

        if (is_array($currentAttributes)) {
            $attributes = array_merge($currentAttributes, $attributes);
        }

        $this->fieldOptions->set('attributes', $attributes);
    }

}