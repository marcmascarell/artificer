<?php

return array(

	'custom_resize' => array(
		'title'    => 'Resize',
		'info'     => array(
			'does'        => 'resize',
			'constraints' => 'aspectRatio, upsize'
		),
		'function' => function ($image, $width = 300, $height = 200, $layout = 'custom_resize') {
//			$name_pieces = explode('/', $image);
//			$name = end($name_pieces);

			$image = Image::make(public_path() . '/uploads/' . $image)->resize($width, $height, function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			});

			return \Mascame\Artificer\ArtificerImage::store($image, public_path() . '/uploads/' . $layout . '/');
		}
	),

	'ultra_custom'  => array(
		'title'    => 'Ultra custom test',
		'info'     => array(
			'does'        => 'resize',
			'constraints' => 'aspectRatio, upsize'
		),
		'function' => function ($image, $width = 300, $height = 200, $layout = 'layout_home2') {
//			$name_pieces = explode('/', $image);
//			$name = end($name_pieces);

			$image = Image::make(public_path() . '/uploads/' . $image)->resize($width, $height, function ($constraint) {
				$constraint->aspectRatio();
				$constraint->upsize();
			});

			return \Mascame\Artificer\ArtificerImage::store($image, public_path() . '/uploads/' . $layout . '/');
		}
	)
);