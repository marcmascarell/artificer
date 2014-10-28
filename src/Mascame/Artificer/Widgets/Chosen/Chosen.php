<?php namespace Mascame\Artificer\Widgets\Chosen;

use Mascame\Artificer\Widgets\AbstractWidget;

class Chosen extends AbstractWidget {

	public function output()
	{
		?>
		<link rel="stylesheet" href="<?= $this->package_assets ?>/chosen/chosen.min.css">
		<link rel="stylesheet" href="<?= $this->package_assets ?>/chosen/chosen.jquery.min.js">
		<script>
			(function ($) {
				$("select").chosen();
			})(jQuery);
		</script>
	<?php
	}

}