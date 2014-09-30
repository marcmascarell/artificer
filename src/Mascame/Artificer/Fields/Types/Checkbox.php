<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;

class Checkbox extends Field {

	public function input($value = null)
	{
		?>
		<div class="checkbox">
			<?php
			print Form::hidden($this->name, 0);
			print Form::checkbox($this->name, 1, $value, $this->getAttributes());
			?>
		</div>
		<?php
	}

}