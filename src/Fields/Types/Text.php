<?php

namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;

class Text extends Field
{
    public function filter($query, $value)
    {
        return $query->where($this->name, 'LIKE', '%'.$value.'%');
    }
}
