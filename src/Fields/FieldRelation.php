<?php namespace Mascame\Artificer\Fields;

class FieldRelation
{
    protected $options = [];

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function getMethod()
    {
        return $this->getAttribute('method');
    }

    public function getRelatedModel()
    {
        return $this->getAttribute('model');
    }

    public function getType()
    {
        return $this->getAttribute('type');
    }

    public function getForeignKey()
    {
        return $this->getAttribute('foreign');
    }

    public function getShow()
    {
        return $this->getAttribute('show');
    }

    /**
     * @param string $attribute
     */
    public function getAttribute($attribute)
    {
        return isset($this->options[$attribute]) ? $this->options[$attribute] : false;
    }
}