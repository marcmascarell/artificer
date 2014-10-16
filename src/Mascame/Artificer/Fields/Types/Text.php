<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\AbstractField;
use Form;
//use Mascame\Artificer\Widgets\FocalPoint;


class Text extends AbstractField {

	public function boot()
	{
	}

	public function input()
	{
		return Form::text($this->name, $this->value, $this->attributes->all());
	}

	public function guarded()
	{
		return "<div>" . $this->value . "</div>";
	}
}