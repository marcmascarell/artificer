<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class Radio extends Field
{
    protected function input()
    {
        echo '<div>';

        foreach ($this->getOption('choices', []) as $choice) {
            $isChecked = ($this->value == $choice);

            echo '<label class="radio-inline">';
            echo Form::radio($this->name, $choice, $isChecked, $this->attributes);
            echo '&emsp;'.$choice;
            echo '</label>';
        }

        echo '</div>';
    }
}
