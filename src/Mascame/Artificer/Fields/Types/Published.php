<?php namespace Mascame\Artificer\Fields\Types;

use Form;

class Published extends Checkbox {

	public function show()
	{
		if ($this->value) {
			?>
				<div class="text-center">
					<i class="glyphicon glyphicon-globe" title="Published"></i>
				</div>
			<?php
		}

	}

}