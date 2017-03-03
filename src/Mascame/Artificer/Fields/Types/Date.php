<?php namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Fields\Field;

class Date extends Field
{
    public function input()
    {
        ?>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-calendar"></i>
			</div>
			<?php echo Form::text($this->name, $this->value, $this->attributes->all()); ?>
		</div>
	<?php

    }
}
