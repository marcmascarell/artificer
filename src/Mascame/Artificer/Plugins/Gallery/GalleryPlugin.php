<?php namespace Mascame\Artificer\Plugins\Gallery;

use Mascame\Artificer\Artificer;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Plugin\Plugin;
use Config;
use View;
use Image;
use Event;

class GalleryPlugin extends Plugin {

	public function meta()
	{
		$this->version = '1.0';
		$this->name = 'Gallery';
		$this->description = 'Simple gallery';
		$this->author = 'Marc Mascarell';
		$this->routes = array(
			'configuration' => array(
				'title' => 'Config',
				'icon'  => '',
				'route' => route('artificer.plugin.gallery.configuration', $this->slug)
			)
		);
	}

	public function boot()
	{
		Event::listen(array('artificer.field.pluploadfield.output', 'artificer.field.image.output'), function ($image) {
//			$thumbnails = $this->getThumbnailLayouts();

//            foreach ($thumbnails as $thumbnail) {
//                if (!$thumbnail['function']($image)) {
//                    // here means thumb already exists
//                }
//            }

//			$thumb_layout = $this->getThumbnailLayout('ultra_custom');
//
//			if (!$thumb_layout['function']($image)) {
//				// here means thumb already exists
//			}

		});
	}

//	public function page($page) {
////		$test = array('ultra_custom' => array(
////			'title' => 'Ultra custom test',
////			'info' => array(
////				'does' => 'resize',
////				'constraints' => 'aspectRatio, upsize'
////			),
////			'function' => function ($image, $width = 300, $height = 200, $layout = 'custom_resize') {
////				$name_pieces = explode('/', $image);
////				$name = end($name_pieces);
////
////				$image = Image::make(public_path() . '/uploads/' . $image)->resize($width, $height, function ($constraint) {
////					$constraint->aspectRatio();
////					$constraint->upsize();
////				});
////
////				return \Mascame\Artificer\ArtificerImage::store($image, public_path() . '/uploads/'.$layout.'/'. $image);
////			}
////		));
////
////		$this->addThumbnailLayouts($test);
////
////		Config::set(Admin::$config_path . 'thumbnails', $this->getThumbnailLayouts());
//
//		switch ($page) {
//			case 'configuration':
//				$layouts = $this->getThumbnailLayouts();
//
//				return View::make('admin::plugins.gallery.home')->with('layouts', $layouts);
//			default:
//				return false;
//		}
//	}

	public function getThumbnailLayouts()
	{
		return AdminOption::get('thumbnails');
	}

	public function addThumbnailLayouts($array)
	{
		AdminOption::set('thumbnails', array_merge($this->getThumbnailLayouts(), $array));
	}

	public function getThumbnailLayout($layout)
	{
		return AdminOption::get('thumbnails.' . $layout);
	}

}