<?php

namespace Mascame\Artificer\Fields;

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
        return $this->fieldOptions->getExistent('attributes', []);
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
        return isset($this->options[$key]);
    }

    /**
     * @param array $attributes
     * @return array|mixed
     */
    public function add($attributes = [])
    {
        $current_attributes = $this->all();

        if (is_array($current_attributes)) {
            $this->fieldOptions->add('attributes', array_merge($current_attributes, $attributes));
        } else {
            $this->fieldOptions->add('attributes', $attributes);
        }

        return $this->fieldOptions->all();
    }
}
