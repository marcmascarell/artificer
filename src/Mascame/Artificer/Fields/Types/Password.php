<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;

class Password extends Field {

	public function input()
	{
		return Form::password($this->name, $this->getAttributes());
	}

	public function show($value = null)
	{
		return $this->hidden();
	}

}