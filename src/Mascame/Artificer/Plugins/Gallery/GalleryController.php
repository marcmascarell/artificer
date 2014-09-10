<?php namespace Mascame\Artificer\Plugins\Gallery;

use Mascame\Artificer\Artificer;
use View;

class GalleryController extends Artificer {


	public function configuration() {
		$layouts = $this->plugins['mascame/gallery']->getThumbnailLayouts();

		return View::make('admin::plugins.gallery.home')->with('layouts', $layouts);
	}

}