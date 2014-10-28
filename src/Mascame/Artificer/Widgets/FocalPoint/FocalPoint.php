<?php namespace Mascame\Artificer\Widgets\FocalPoint;

use Mascame\Artificer\Widgets\AbstractWidget as Widget;

// This widget requires a column on database like "sort_id" to work

class FocalPoint extends AbstractWidget {

	public function output()
	{
		?>
		<!--		<link rel="stylesheet" href="--><? //= $this->package_assets ?><!--/widgets/image-focal-point/style.css">-->
		<!--		<script src="--><? //= $this->package_assets ?><!--/widgets/image-focal-point/jquery.focalpoint.js"></script>-->
	<?php
	}

}