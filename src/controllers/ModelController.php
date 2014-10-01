<?php namespace Mascame\Artificer;

use Redirect;
use Validator;
use Input;
use View;
use Response;
use Event;
use File;
use Str;
use Request;

class ModelController extends Artificer {

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
			'form_method'       => 'post'
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

		$validator = $this->validator($data);
		if ($validator->fails()) return $this->redirect($validator, 'admin.create');

		$this->handleData($data);

//		$relations = array();
//
//		foreach($this->fields as $field) {
//			if ($field->isRelation()) {
//
//				if (isset($data[$field->name])) {
//
//				}
//
//				if ($field->getRelationType() == 'belongsTo') {
////					dd('here ' . 'artificer.'.$field->getRelationedModel().'.has.belongsTo');
//
//					$relation[$field->name] = array(
//						'name' => $field->name,
//						'foreign' => $field->getRelationForeignKey(),
//						'related_model' => $field->getRelatedModel(),
//						'model' => $this->modelObject->class,
//						'id' => '' // necesitamos actualizar esto para poder recuperarlo
//					);
//				}
//
//				print $field->name . ' ' . $field->getRelationType();
//			}
//		}

		$model = $this->modelObject->class;

		$item = $model::create(with($this->handleFiles($data)));

		/**
		 * Pasamos a las relaciones la id del elemento creado para que puedan actualizarse
		 */
//		if (count($relations) > 1) {
//			foreach ($relations as $relation) {
//				\Session::set('artificer.'.$relation['related_model'].'.has.belongsTo',
//					array('foreign' => $relation['foreign'],
//						  'id' => $item->id)
//				);
//			}
//		}
//
//		/*
//		 * Actualizamos el model
//		 */
//		if (\Session::has('artificer.'.$this->modelObject->name.'.has.belongsTo')) {
//			$data = \Session::get('artificer.'.$this->modelObject->name.'.has.belongsTo');
//
//			$item->$data['foreign'] = $data['id'];
//			$item->save();
//		}

		if (Request::ajax()) {
			return Response::json($item->toArray());
		}

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

		$this->handleData($this->model->with($this->modelObject->getRelations())->orderBy($sort['column'], $sort['direction'])->get());

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
		$this->handleData($this->model->with($this->modelObject->getRelations())->findOrFail($id));

		$form = array(
			'form_action_route' => 'admin.update',
			'form_method'       => 'put'
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

		$validator = $this->validator($data);
		if ($validator->fails()) return $this->redirect($validator, 'admin.edit');

		$item->update(with($this->handleFiles($data)));

		if (Request::ajax()) {
			return Response::json($item->toArray());
		}

		return Redirect::route('admin.all', array('slug' => $this->modelObject->getRouteName()));
	}

	protected function redirect($validator, $route) {
		if (Input::has('_standalone')) {
			return Redirect::route($route, array('slug' => Input::get('_standalone')))
				->withErrors($validator)
				->withInput();
		}

		return Redirect::back()->withErrors($validator)->withInput();
	}

	protected function validator($data) {
		return Validator::make($data, $this->getRules());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function destroy($modelName, $id)
	{
		$event_info = array(
			array(
				"model" => $modelName,
				"id"    => $id
			)
		);

		Event::fire('artificer.before.destroy', $event_info);

		if ($this->model->destroy($id)) {
			Notification::success('<b>Success!</b> The record has been deleted!', true);
			Event::fire('artificer.after.destroy', $event_info);
		} else {
			Notification::danger('<b>Failed!</b> The record could not be deleted!');
		}

		return Redirect::back();

//		return Redirect::route('admin.all', array('slug' => $this->modelObject->getRouteName()));
	}

	/**
	 * @param $data
	 * @return array
	 */
	protected function handleFiles($data)
	{
		$new_data = array();

		foreach ($this->getFields($data) as $field) {

			if ($field->type == 'file' || $field->type == 'image') {

				if (Input::hasFile($field->name)) {
					$new_data[$field->name] = $this->uploadFile($field->name);
				} else {
					unset($data[$field->name]);
				}
			}
		}

		return array_merge($data, $new_data);
	}

	/**
	 * This is used for simple upload (no plugins)
	 *
	 * @param $fieldname
	 * @param null $path
	 * @return string
	 */
	protected function uploadFile($fieldname, $path = null)
	{
		if (!$path) {
			$path = public_path() . '/uploads/';
		}

		$file = Input::file($fieldname);

		if (!file_exists($path)) {
			File::makeDirectory($path);
		}

		$name = uniqid() . '-' . Str::slug($file->getClientOriginalName());

		$file->move($path, $name);

		return $name;
	}

	public function getRelatedFieldOutput($modelName, $id, $field)
	{
		$this->handleData($this->model->with($this->modelObject->getRelations())->findOrFail($id));

		return $this->fields[$field]->output();
	}

}