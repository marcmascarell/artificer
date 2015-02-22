<?php namespace Mascame\Artificer\Fields\Types;

use Carbon\Carbon;
use Mascame\Artificer\Fields\Field;
use Form;
use Mascame\ArtificerWidgets\DateTimepicker\DateTimepicker;

class DateTime extends Field {

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

	public function show() {
		$date = Carbon::parse($this->value);
		return $date->format('d-m-Y H:i:s');
	}

}