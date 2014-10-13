<?php namespace Mascame\Artificer\Plugins\Gallery;

use Mascame\Artificer\Artificer;
use Mascame\Artificer\BaseModelController;
use View;

class GalleryController extends BaseModelController {


	public function configuration()
	{
		$layouts = $this->plugins['mascame/gallery']->getThumbnailLayouts();

		return View::make('admin::plugins.gallery.home')->with('layouts', $layouts);
	}

}