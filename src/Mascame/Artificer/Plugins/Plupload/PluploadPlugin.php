<?php namespace Mascame\Artificer\Plugins\Plupload;

use Mascame\Artificer\Plugin\AbstractPlugin;
use Event;
use Route;


class PluploadPlugin extends AbstractPlugin {


	public function meta()
	{
		$this->version = '1.0';
		$this->name = 'Plupload';
		$this->description = 'Plupload widget and field for uploading images';
		$this->author = 'Marc Mascarell';
	}

	public function boot()
	{
		// Todo: find a way to do sth like this
		Event::listen('artificer.routes.model', function() {
			Route::post('{slug}/{id}/upload', array('as' => 'admin.model.upload', 'uses' => 'Mascame\Artificer\Plugins\Plupload\PluploadController@plupload'));
		});
	}

}