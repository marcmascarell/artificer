<?php namespace Mascame\Artificer;

use Redirect;
use Validator;
use Input;
use View;
use Response;
use JildertMiedema\LaravelPlupload\Facades\Plupload;
use Mascame\Artificer\Option\AdminOption;

class ModelController extends Artificer {

	public function __construct(Model $model) {
		parent::__construct($model);

		$this->modelObject = $model;
		$this->model = $model->model;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->handleData(new $this->modelObject->class);

		$form = array(
			'form_action_route' => 'admin.store',
			'form_method' => 'post'
		);

		return View::make($this->getView('edit'))->with('items', $this->data)->with($form);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::except('id');
		$validator = Validator::make($data, $this->getRules());

		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$model = $this->modelObject->class;

		$model::create($data);

		return Redirect::route('admin.all', array('slug' => $this->modelObject->getRouteName()));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function show($modelName, $id)
	{
		$this->handleData($this->model->findOrFail($id));

		return View::make($this->getView('show'))->with('items', $this->data);
	}

	/**
	 * Display the specified post.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function all($modelName)
	{
		$sort = $this->getSort();

		$this->handleData($this->model->orderBy($sort['column'], $sort['direction'])->get());

		return View::make($this->getView('all'))
			->with('items', $this->data)
			->with('sort', $sort);
	}

	/**
	 * Show the form for editing the specified post.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function edit($modelName, $id)
	{
		$this->handleData($this->model->findOrFail($id));

		$form = array(
			'form_action_route' => 'admin.update',
			'form_method' => 'put'
		);

		return View::make($this->getView('edit'))->with('items', $this->data)->with($form);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 * @return Response
	 */

	public function update($modelName, $id)
	{
		$item = $this->model->findOrFail($id);

		$data = Input::all();
		$validator = Validator::make($data, $this->getRules());

		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$item->update(with($this->handleFiles($data)));

		return Redirect::route('admin.all', array('slug' => $this->modelObject->getRouteName()));
	}

	public function plupload($modelName, $id)
	{
		$path = public_path() . '/uploads/';

		$item = $this->model->findOrFail($id);

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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function destroy($modelName, $id)
	{
		if ($this->model->destroy($id)) {
			Notification::success('<b>Success!</b> The record has been deleted!', true);
		} else {
			Notification::danger('<b>Failed!</b> The record could not be deleted!');
		}

		return Redirect::route('admin.all', array('slug' => $this->modelObject->getRouteName()));
	}



}