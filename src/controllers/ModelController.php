<?php namespace Mascame\Artificer;

use Redirect;
use Input;
use View;
use Response;
use Event;
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
			'form_action_route' => 'admin.model.store',
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
		$data = $this->filterInputData();

		$validator = $this->validator($data);
		if ($validator->fails()) return $this->redirect($validator, 'admin.model.create');

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

		return Redirect::route('admin.model.all', array('slug' => $this->modelObject->getRouteName()));
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
	 * @return Response
	 */
	public function all($modelName, $data = null, $sort = null)
	{
		$sort = $this->getSort();

		$data = $this->model->with($this->modelObject->getRelations())->orderBy($sort['column'], $sort['direction'])->get();

		return parent::all($modelName, $data, $sort);
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
			'form_action_route' => 'admin.model.update',
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
		if ($validator->fails()) return $this->redirect($validator, 'admin.model.edit');

		$item->update(with($this->handleFiles($data)));

		if (Request::ajax()) {
			return Response::json($item->toArray());
		}

		return Redirect::route('admin.model.all', array('slug' => $this->modelObject->getRouteName()));
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

//		return Redirect::route('admin.model.all', array('slug' => $this->modelObject->getRouteName()));
	}

}