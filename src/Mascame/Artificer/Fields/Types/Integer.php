<?php namespace Mascame\Artificer\Fields\Types;

use Form;

class Integer extends Text {

	public function input()
	{
		return Form::number($this->name, $this->value);
	}

}