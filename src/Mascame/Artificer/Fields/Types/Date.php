<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;
use Mascame\ArtificerWidgets\Datepicker\Datepicker;

class Date extends Field {

	public function input()
	{
		?>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-calendar"></i>
			</div>
			<?php print Form::text($this->name, $this->value, $this->attributes->all()); ?>
		</div>
	<?php
	}

}