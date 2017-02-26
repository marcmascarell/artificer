<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Carbon\Carbon;
use Mascame\Artificer\Fields\Field;

class Datetime extends Field
{
    public function input()
    {
        if ($this->value) {
            $this->value = Carbon::parse($this->value)->format('Y-m-d H:i:s');
        }

        return Form::text($this->name, $this->value, $this->attributes);
    }

    public function show()
    {
        $date = Carbon::parse($this->value);

        $format = $this->getOption('format');

        return $date->format(($format) ? $format : 'Y-m-d H:i:s');
    }
}
