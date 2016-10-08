<?php

namespace Mascame\Artificer\Widget;

use Mascame\Artificer\Fields\FieldWrapper;

class FieldWidget extends AbstractWidget implements FieldWidgetInterface
{
    /**
     * @param FieldWrapper $field
     * @return FieldWrapper
     */
    public function field(FieldWrapper $field)
    {
        return $field;
    }
}
