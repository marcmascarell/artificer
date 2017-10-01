<?php

namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Artificer;
use Mascame\Artificer\Model\ModelManager;

trait Relationable
{
    /**
     * @var ModelManager;
     */
    protected $modelManager;

    /**
     * @return bool
     */
    public function getMethod()
    {
        return $this->getRelationAttribute('method');
    }

    /**
     * @return \Mascame\Artificer\Model\Model
     * @throws \Exception
     */
    public function getRelatedModel()
    {
        if ($this->relatedModel) {
            return $this->relatedModel;
        }

        $modelName = $this->getRelationAttribute('model');

        if (Artificer::modelManager()->has($modelName)) {
            return $this->relatedModel = Artificer::modelManager()->get($modelName);
        }

        throw new \Exception("Couldn't find the related model for '{$this->getName()}''");
    }

    /**
     * @return bool|mixed
     */
//    public function getType()
//    {
//        $type = $this->getRelationAttribute('type');
//
//        if ($type) {
//            return $type;
//        }
//
//        $pieces = explode('\\', get_called_class());
//
//        return end($pieces);
//    }

    /**
     * @return bool
     */
    public function getShownProperty()
    {
        return $this->getRelationAttribute('show');
    }

    /**
     * The value column (usually 'id').
     *
     * @return bool
     */
    public function getKeyProperty()
    {
        return $this->getRelationAttribute('key', 'id');
    }

    /**
     * @param $attribute
     * @return bool
     */
    public function getRelationAttribute($attribute, $default = false)
    {
        $options = $this->getOption('relationship');

        return $options[$attribute] ?? $default;
    }
}
