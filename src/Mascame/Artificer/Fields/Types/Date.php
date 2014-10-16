<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\AbstractField;
use Form;
use Mascame\Artificer\Widgets\Datepicker\Datepicker;

class Date extends AbstractField {

	public function boot()
	{
		$this->addWidget(new Datepicker());
		$this->attributes->add(array('class' => 'form-control datepicker'));
	}

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