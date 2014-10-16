<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\AbstractField;
use Form;

class Password extends AbstractField {

	public function input()
	{
		return Form::password($this->name, $this->attributes->all());
	}

	public function show()
	{
		return $this->hidden();
	}

}