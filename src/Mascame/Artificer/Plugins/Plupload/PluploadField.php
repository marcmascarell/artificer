<?php namespace Mascame\Artificer\Plugins\Plupload;

use Form;
use Mascame\Artificer\Widgets\FocalPoint\FocalPoint;
use Mascame\Artificer\Plugins\Plupload\PluploadWidget;
use Mascame\Artificer\Fields\Types\Image;

class PluploadField extends Image {

	public function boot()
	{
		$this->addWidget(new FocalPoint());
		$this->addWidget(new PluploadWidget());
	}

	public function input()
	{
		if ($this->isGuarded()) {
			print "guarded";
		}
		?>
		<ul id="plupload-file-list" class="list-group"></ul>

		<div class="plupload-preview">

				<?= $this->show() ?>
		</div>

		<div id="container">
			<a id="browse" href="javascript:;" class="btn btn-primary">Browse</a>
			<!--				<a id="start-upload" href="javascript:;" class="btn btn-success">[Start Upload]</a>-->
		</div>

		<!--			<br />-->
		<!--			<pre id="console"></pre>-->
	<?php
	}


}