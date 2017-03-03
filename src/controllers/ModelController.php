<?php

namespace Mascame\Artificer;

use URL;
use View;
use Event;
use Input;
use Request;
use Session;
use Redirect;
use Response;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Options\AdminOption;

class ModelController extends BaseModelController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->handleData(new $this->modelObject->class);

        $form = [
            'form_action_route' => 'admin.model.store',
            'form_method'       => 'post',
        ];

        return View::make($this->getView('edit'))->with('items', $this->data)->with($form);
    }

    /**
     * @param $modelName
     * @return $this
     */
    public function filter($modelName)
    {
        $this->handleData($this->model->firstOrFail());

        $sort = $this->getSort();

        $data = $this->model->where(function ($query) {
            foreach (Input::all() as $name => $value) {
                if ($value != '' && isset($this->fields[$name])) {
                    $this->fields[$name]->filter($query, $value);
                }
            }

            return $query;
        })->with($this->modelObject->getRelations())->orderBy($sort['column'], $sort['direction'])->paginate();

        return parent::all($modelName, $data, $sort);
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
        if ($validator->fails()) {
            return $this->redirect($validator, 'admin.model.create');
        }

        $this->handleData($data);

        $model = $this->modelObject->class;

        $this->model->guard($this->modelObject->options['guarded']);

        $item = $model::create(with($this->handleFiles($data)));

        $relation_on_create = '_set_relation_on_create';
        if (Input::has($relation_on_create)) {
            $relateds = [
                'id' => $item->id,
                'modelClass' => $this->modelObject->class,
                'foreign' => Input::get('_set_relation_on_create_foreign'),
            ];

            Session::push($relation_on_create.'_'.Input::get($relation_on_create), $relateds);
        }

        if (Session::has($relation_on_create.'_'.$this->modelObject->name)) {
            $relations = Session::get($relation_on_create.'_'.$this->modelObject->name);

            foreach ($relations as $relation) {
                $related_item = $relation['modelClass']::find($relation['id']);
                $related_item->$relation['foreign'] = $item->id;
                $related_item->save();
            }

            Session::forget($relation_on_create.'_'.$this->modelObject->name);
        }

        if (Request::ajax()) {
            return $this->handleAjaxResponse($item);
        }

        return Redirect::route('admin.model.all', ['slug' => $this->modelObject->getRouteName()]);
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

        $form = [
            'form_action_route' => 'admin.model.update',
            'form_method'       => 'put',
        ];

        return View::make($this->getView('edit'))->with('items', $this->data)->with($form);
    }

    public function field($modelName, $id, $field)
    {
        $this->handleData($this->model->with($this->modelObject->getRelations())->findOrFail($id));

        $this->fields[$field]->showFullField = true;

        return \HTML::field($this->fields[$field], AdminOption::get('icons'));
    }

    protected function handleAjaxResponse($item)
    {
        return Response::json([
                'item' => $item->toArray(),
                'refresh' => URL::route('admin.model.field.edit', [
                    'slug' => Input::get('_standalone_origin'),
                    'id' => Input::get('_standalone_origin_id'),
                    'field' => ':fieldName:', ]),
            ]
        );
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
        if ($validator->fails()) {
            return $this->redirect($validator, 'admin.model.edit', $id);
        }

        $item->update(with($this->handleFiles($data)));

        if (Request::ajax()) {
            return $this->handleAjaxResponse($item);
        }

        return Redirect::route('admin.model.all', ['slug' => $this->modelObject->getRouteName()]);
    }

    /**

     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($modelName, $id)
    {
        $event_info = [
            [
                'model' => $modelName,
                'id'    => $id,
            ],
        ];

        Event::fire('artificer.model.before.destroy', $event_info);

        if ($this->model->destroy($id)) {
            Notification::success('<b>Success!</b> The record has been deleted!', true);
            Event::fire('artificer.model.after.destroy', $event_info);
        } else {
            Notification::danger('<b>Failed!</b> The record could not be deleted!');
        }

        if (Request::ajax()) {
            // todo
            return Response::json([]);
        }

        return Redirect::back();

//		return Redirect::route('admin.model.all', array('slug' => $this->modelObject->getRouteName()));
    }
}
