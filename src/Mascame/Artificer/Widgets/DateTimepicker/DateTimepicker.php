<?php namespace Mascame\Artificer\Widgets\DateTimepicker;

use Mascame\Artificer\Widgets\Widget;
use Lang;

class DateTimepicker extends Widget {

	public function output()
	{
		?>
		<script>
			$(function () {
				$('.datetimepicker').datetimepicker({
					pick12HourFormat: false,
					language: 'es'
				});
			});
		</script>
	<?php
	}

}