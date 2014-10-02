<?php namespace Mascame\Artificer\Fields\Types;

use Carbon\Carbon;
use Mascame\Artificer\Fields\Field;
use Form;
use Mascame\Artificer\Widgets\DateTimepicker\DateTimepicker;

class DateTime extends Field {

	public function boot()
	{
		$this->addWidget(new DateTimepicker());
		$this->addAttributes(array('class' => 'form-control datetimepicker', 'data-date-format' => 'YYYY-MM-DD HH:mm:ss'));
	}

	public function input()
	{
		?>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-calendar"></i>
			</div>
			<?php print Form::text($this->name, $this->value, $this->getAttributes()); ?>
		</div>
	<?php
	}

	public function show($value = null) {
		$date = Carbon::parse($this->getValue($value));
		return $date->format('d-m-Y H:i:s');
	}

}