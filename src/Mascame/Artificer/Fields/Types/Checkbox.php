<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;

class Checkbox extends Field {

	public function input()
	{
		?>
		<div class="checkbox">

			<?php
			// default
			print Form::hidden($this->name, 0);
			print Form::checkbox($this->name, $this->value, false, $this->getAttributes());
			?>
		</div>
		<?php
	}

}