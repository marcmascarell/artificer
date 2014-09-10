<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;

class Select extends Field {

	public function input()
	{
		return Form::select($this->name, $this->value, false, $this->getAttributes());
	}

	public function outputRange($start, $end)
	{
		return Form::selectRange($this->name, $start, $end);
	}

	public function outputMonth()
	{
		return Form::selectMonth($this->name);
	}

	public function outputYear()
	{
		return Form::selectYear($this->name);
	}
}