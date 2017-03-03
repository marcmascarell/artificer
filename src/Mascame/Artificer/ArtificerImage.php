<?php

namespace Mascame\Artificer;

use File;

class ArtificerImage
{
    public $image;

//	public function __construct(\Intervention\Image\Image $image) {
//		$this->image = $image;
//	}

    public static function store(\Intervention\Image\Image $image, $path = null, $quality = null)
    {
        if (! $path) {
            $pathinfo = pathinfo($path);
            $path = $pathinfo['dirname'];
        }

        if (! file_exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $filename = $path.$image->filename.'.'.$image->extension;

        if (! file_exists($filename)) {
            return $image->save($filename, $quality);
        }

        return false;
    }

    public static function get($image, $layout)
    {
        return 'uploads/'.$layout.'/'.$image;
    }

    public static function path($image, $layout)
    {
        return public_path().'uploads/'.$layout.'/'.$image;
    }
}
