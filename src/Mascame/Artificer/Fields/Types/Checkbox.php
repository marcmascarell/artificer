<?php namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class Checkbox extends Field
{
    public function input()
    {
        ?>
		<div class="checkbox">
			<?php
            echo Form::hidden($this->name, 0);
        echo Form::checkbox($this->name, 1, $this->value, $this->attributes->all()); ?>
		</div>
		<?php

    }
}
