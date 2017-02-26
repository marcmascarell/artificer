<?php

namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Model\ModelManager;

trait Relationable
{
    /**
     * @var ModelManager;
     */
    public $modelManager;

    /**
     * @var null
     */
    private $relationOptions = null;

    /**
     * @return bool
     */
    public function getMethod()
    {
        return $this->getRelationAttribute('method');
    }

    /**
     * @return \Mascame\Artificer\Model\ModelSettings
     * @throws \Exception
     */
    public function getRelatedModel()
    {
        if ($this->relatedModel) {
            return $this->relatedModel;
        }

        $modelName = $this->getRelationAttribute('model');

        if ($this->modelManager->has($modelName)) {
            return $this->relatedModel = $this->modelManager->settings($modelName);
        }

        throw new \Exception("Couldn't find the related model for '{$this->getName()}''");
    }

    /**
     * @return bool|mixed
     */
    public function getType()
    {
        $type = $this->getRelationAttribute('type');

        if ($type) {
            return $type;
        }

        $pieces = explode('\\', get_called_class());

        return end($pieces);
    }

    /**
     * @return bool
     */
    public function getForeignKey()
    {
        return $this->getRelationAttribute('foreign');
    }

    /**
     * @return bool
     */
    public function getShow()
    {
        return $this->getRelationAttribute('show');
    }

    /**
     * @return null
     */
    private function getRelationOptions()
    {
        if ($this->relationOptions) {
            return $this->relationOptions;
        }

        return $this->relationOptions = $this->getOption('relationship');
    }

    /**
     * @param $attribute
     * @return bool
     */
    public function getRelationAttribute($attribute)
    {
        $options = $this->getRelationOptions();

        return isset($options[$attribute]) ? $options[$attribute] : false;
    }
}
