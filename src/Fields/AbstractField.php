<?php

namespace Mascame\Artificer\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AbstractField implements FieldInterface
{
    /**
     * @var
     */
    protected $name;

    /**
     * @var
     */
    protected $value;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param null $value
     * @return null
     */
    public function getDefault()
    {
        return $this->getOption('default');
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->getOption('title', Str::title(str_replace('_', ' ', $this->name)));
    }

    /**
     * @return string
     */
    public function getWiki()
    {
        return $this->getOption('wiki');
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->getOption('attributes', []);
    }

    /**
     * @param $key
     * @param null $default
     * @return null
     */
    public function getOption($key, $default = null)
    {
        return Arr::get($this->options, $key, $default);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $options
     * @param bool $overwrite
     */
    public function setOptions($options, $overwrite = false)
    {
        $this->options = ($overwrite) ? $options : array_replace_recursive($this->options, $options);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
