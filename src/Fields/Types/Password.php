<?php

namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;

class Password extends Field
{
    public $filterable = false;

    const PLACEHOLDER = 'PASSWORD_PLACEHOLDER';

    /**
     * @param $value
     * @return null|string
     */
    public function transformValue($value)
    {
        return $this->getDefault();
    }

    /**
     * @return null|string
     */
    public function getDefault()
    {
        $default = parent::getDefault();

        return $default ?? self::PLACEHOLDER;
    }

    /**
     * @param $model \Eloquent
     */
    public function updatingHook($model)
    {
        $name = $this->getName();
        $value = $model->{$name};

        if ($value === self::PLACEHOLDER) {
            $model->__unset($name);
        }
    }
}
