<?php namespace Mascame\Artificer\Fields\Types;

use Mascame\Artificer\Fields\Field;
use Form;
//use Mascame\Artificer\Widgets\FocalPoint;
use Plugin\Sortable\SortableWidget;


class Text extends Field {

	public function boot()
	{
	}

	public function input()
	{
		return Form::text($this->name, $this->value, $this->getAttributes());
	}

	public function guarded()
	{
		return "<div>" . $this->value . "</div>";
	}
}