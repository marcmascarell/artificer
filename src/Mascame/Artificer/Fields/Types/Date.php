<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;
use Mascame\Artificer\Widgets\Datepicker\Datepicker;

class Date extends Field {

	public function boot()
	{
		$this->addWidget(new Datepicker());
		$this->addAttributes(array('class' => 'form-control datepicker'));
	}

	public function input()
	{
		return Form::text($this->name, $this->value, $this->getAttributes());
	}

}