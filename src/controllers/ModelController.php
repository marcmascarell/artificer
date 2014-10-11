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
use Session;

class ModelController extends BaseModelController {

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

	private function filterInputData() {
		if ($this->modelObject->isGuarded()) {
			return $this->except($this->modelObject->options['guarded'], Input::only($this->modelObject->columns));
		}

		return Input::except('id');
	}

	private function except($keys, $values)
	{
		$keys = is_array($keys) ? $keys : func_get_args();

		$results = $values;

		array_forget($results, $keys);

		return $results;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = $this->filterInputData();

		$validator = $this->validator($data);
		if ($validator->fails()) return $this->redirect($validator, 'admin.create');

		$this->handleData($data);

		$model = $this->modelObject->class;

		$this->model->guard($this->modelObject->options['guarded']);

		$item = $model::create(with($this->handleFiles($data)));

        $relation_on_create = '_set_relation_on_create';
        if (Input::has($relation_on_create)) {
            $relateds = array(
                'id' => $item->id,
                'modelClass' => $this->modelObject->class,
                'foreign' => Input::get('_set_relation_on_create_foreign')
            );

            Session::push($relation_on_create . '_' . Input::get($relation_on_create), $relateds);
        }

        if (Session::has($relation_on_create . '_' . $this->modelObject->name)) {
            $relations = Session::get($relation_on_create . '_' . $this->modelObject->name);

            foreach ($relations as $relation) {
                $related_item = $relation['modelClass']::find($relation['id']);
                $related_item->$relation['foreign'] = $item->id;
                $related_item->save();
            }

            Session::forget($relation_on_create . '_' . $this->modelObject->name);
        }

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

		$data = $this->filterInputData();

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
        if ($id != 0) {
            $this->handleData($this->model->with($this->modelObject->getRelations())->findOrFail($id));
        } else {
            if (Session::has('_set_relation_on_create_'.$this->modelObject->name)) {
                $relateds = Session::get('_set_relation_on_create_'.$this->modelObject->name);
                $related_ids = array();
                foreach ($relateds as $related) {
                    $related_ids[] = $related['id'];
                }

                $data = $relateds[0]['modelClass']::whereIn('id', $related_ids)->get();

                $this->handleData($data);
            } else {
                return null;
            }
        }

        return $this->fields[$field]->output();
	}

}