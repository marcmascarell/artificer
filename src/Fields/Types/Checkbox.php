<?php

namespace Mascame\Artificer\Fields\Types;

use Illuminate\Support\Collection;
use Mascame\Artificer\Fields\Field;

class Checkbox extends Field
{
    /**
     * @param $fields Collection
     * @param $next
     * @return mixed
     */
    public function savingHook($fields, $next)
    {
        $fields->each(function (Field $field, $key) {
            if ($field->getType() == $this->getType() && empty($field->getDefault())) {
                $field->setDefault(0);
            }
        });

        return $next($fields);
    }
}
