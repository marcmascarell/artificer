<?php namespace Mascame\Artificer\Plugins\Plupload;

use Mascame\Artificer\Artificer;
use JildertMiedema\LaravelPlupload\Facades\Plupload;
use Mascame\Artificer\BaseModelController;
use Response;

class PluploadController extends BaseModelController {


	public function configuration()
	{

	}

	public function plupload($modelName, $id)
	{
		$path = public_path() . '/uploads/';

		$item = $this->model->find($id);

		if (!$item) {
			$response = Plupload::receive('file', function ($file) use ($path) {
				$this->options['uploaded']['name'] = time() . $file->getClientOriginalName();

				$file = $file->move($path, $this->options['uploaded']['name']);

				$this->options['uploaded']['instance'] = $file;
			});

			$item->image = $this->options['uploaded']['name'];
			$item->save();

			return Response::json(array_merge($response,
				array(
					'filename'      => $this->options['uploaded']['name'],
					'file_location' => $path . $this->options['uploaded']['name']
				)
			));
		}

		return Response::json(array('test' => 'here'));
	}

}