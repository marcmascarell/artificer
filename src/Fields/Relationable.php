<?php namespace Mascame\Artificer\Fields;

trait Relationable
{
    private $relationOptions = null;

    public function getMethod()
    {
        return $this->getRelationAttribute('method');
    }

    public function getRelatedModel()
    {
        if ($this->relatedModel) return $this->relatedModel;

        $modelName = $this->getRelationAttribute('model');

        if (isset($this->modelObject->schema->models[$modelName])) {
            return $this->relatedModel = $this->modelObject->schema->models[$modelName];
        }

        throw new \Exception("Couldn't find the related model for '{$this->getName()}''");
    }

    public function getType()
    {
        $type = $this->getRelationAttribute('type');

        if ($type) return $type;

        $pieces = explode('\\', get_called_class());

        return end($pieces);
    }

    public function getForeignKey()
    {
        return $this->getRelationAttribute('foreign');
    }

    public function getShow()
    {
        return $this->getRelationAttribute('show');
    }

    private function getRelationOptions() {
        if ($this->relationOptions) return $this->relationOptions;

        return $this->relationOptions = $this->getOption('relationship');
    }

    /**
     * @param string $attribute
     */
    public function getRelationAttribute($attribute)
    {
        $options = $this->getRelationOptions();

        return isset($options[$attribute]) ? $options[$attribute] : false;
    }
}