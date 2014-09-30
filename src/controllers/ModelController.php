<?php namespace Mascame\Artificer;

use Redirect;
use Validator;
use Input;
use View;
use Response;
use Event;
use File;
use Str;

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
		$validator = Validator::make($data, $this->getRules());

		if ($validator->fails()) {
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$this->handleData($data);

		$relations = array();

		foreach($this->fields as $field) {
			if ($field->isRelation()) {

				if (isset($data[$field->name])) {

				}

				if ($field->getRelationType() == 'belongsTo') {
//					dd('here ' . 'artificer.'.$field->getRelationedModel().'.has.belongsTo');

					$relation[$field->name] = array(
						'name' => $field->name,
						'foreign' => $field->getRelationForeignKey(),
						'related_model' => $field->getRelatedModel(),
						'model' => ''
					);
				}

				print $field->name . ' ' . $field->getRelationType();
			}
		}

		$model = $this->modelObject->class;

		$item = $model::create(with($this->handleFiles($data)));

		if (count($relations) > 1) {
			foreach ($relations as $relation) {
				\Session::set('artificer.'.$relation['related_model'].'.has.belongsTo',
					array('foreign' => $relation['foreign'],
						  'id' => $item->id)
				);
			}
		}

		if (\Session::has('artificer.'.$this->modelObject->name.'.has.belongsTo')) {
			$data = \Session::has('artificer.'.$this->modelObject->name.'.has.belongsTo');

			$item->$data['foreign'] = $data['id'];
			$item->save();
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

		$validator = Validator::make($data, $this->getRules());

		if ($validator->fails()) return Redirect::back()->withErrors($validator)->withInput();

		$item->update(with($this->handleFiles($data)));

		return Redirect::route('admin.all', array('slug' => $this->modelObject->getRouteName()));
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

		return Redirect::route('admin.all', array('slug' => $this->modelObject->getRouteName()));
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

}