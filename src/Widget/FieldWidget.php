<?php

namespace Mascame\Artificer\Widget;

use Mascame\Artificer\Fields\Field;

class FieldWidget extends AbstractWidget implements FieldWidgetInterface
{
    /**
     * @param Field $field
     * @return Field
     */
    public function field(Field $field)
    {
        return $field;
    }
}
