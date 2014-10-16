<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\AbstractField;
use Form;

class Checkbox extends AbstractField {

	public function input()
	{
		?>
		<div class="checkbox">
			<?php
			print Form::hidden($this->name, 0);
			print Form::checkbox($this->name, 1, $this->value, $this->attributes->all());
			?>
		</div>
		<?php
	}

}