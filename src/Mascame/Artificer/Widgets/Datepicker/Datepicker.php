<?php namespace Mascame\Artificer\Widgets\Datepicker;

use Mascame\Artificer\Widgets\AbstractWidget;

class Datepicker extends AbstractWidget {

	public function output()
	{
		?>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

		<script>
			$(function () {
				$(".datepicker").datepicker();
			});
		</script>
	<?php
	}

}