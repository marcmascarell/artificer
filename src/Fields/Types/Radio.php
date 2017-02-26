<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class Radio extends Field
{
    protected function input()
    {
        print '<div>';

        foreach ($this->getOption('choices', []) as $choice) {
            $isChecked = ($this->value == $choice);

            print '<label class="radio-inline">';
            print Form::radio($this->name, $choice, $isChecked, $this->attributes);
            print "&emsp;" . $choice;
            print '</label>';
        }

        print '</div>';
    }
}
