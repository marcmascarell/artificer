<?php namespace Mascame\Artificer\Fields\Types;

use Form;
use Mascame\Artificer\Widgets\FocalPoint\Focalpoint;

class Image extends File {

	public function boot()
	{
		$this->addWidget(new FocalPoint());
	}

	public function input()
	{
		if ($this->value != null) {
			?>
			<div data-box class="focal_box">
				<?= $this->show() ?>
				<div data-point class="focal_point"></div>
			</div>

			<div data-position class="focal_position"></div>
		<?php
		}

		print Form::file($this->name);
	}

	public function show($value = null)
	{
		$value = $this->getValue($value);

		if (!\Str::startsWith($value, array('https://', 'http://'))) {
			$value = '/uploads/' . $value;
		}

		return '<img style="display: block; margin: auto" src="/uploads/' . $value . '" height="100" />';
	}
}