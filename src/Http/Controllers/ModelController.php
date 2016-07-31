<?php namespace Mascame\Artificer\Http\Controllers;

use Event;
use Input;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Notify\Notify;
use Redirect;
use Request;
use Response;
use Session;
use URL;
use View;

class ModelController extends BaseModelController
{

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $this->handleData($this->modelObject->schema->getInstance());

        $form = array(
            'form_action_route' => 'admin.model.store',
            'form_method' => 'post'
        );

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

            foreach (\Request::all() as $name => $value) {
                if ($value != '' && isset($this->fields[$name])) {
                    $this->fields[$name]->filter($query, $value);
                }
            }

            return null;
        })
            ->with($this->modelObject->getRelations())
            ->orderBy($sort['column'], $sort['direction'])
            ->get();

        return parent::all($modelName, $data, $sort);
    }

    /**
     * Todo: rethink the way relations are made
     *
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

        $this->model->guard($this->modelObject->getGuarded());
        $this->model->fillable($this->modelObject->getOption('fillable', []));

        $item = $this->model->create(with($this->handleFiles($data)));

        if (Request::ajax()) {
            return $this->handleAjaxResponse($item);
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

        return View::make($this->getView('show'))->with('item', $this->data);
    }

    /**
     * Display the specified post.
     *
     * @return Response
     */
    public function all($modelName, $data = null, $sort = null)
    {
        $sort = $this->getSort();

        $data = $this->model->with($this->modelObject->getRelations())->orderBy($sort['column'],
            $sort['direction'])->get();

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
        $this->handleData(
            $this->model->with($this->modelObject->getRelations())->findOrFail($id)
        );

        $form = array(
            'form_action_route' => 'admin.model.update',
            'form_method' => 'put'
        );

        return View::make($this->getView('edit'))
            ->with('items', $this->data)
            ->with($form);
    }

    public function field($modelName, $id, $field)
    {
        $this->handleData($this->model->with($this->modelObject->getRelations())->findOrFail($id));

        $this->fields[$field]->showFullField = true;

        return \HTML::field($this->fields[$field], AdminOption::get('icons'));
    }

    protected function handleAjaxResponse($item)
    {
        return Response::json(array(
                'item' => $item->toArray(),
                'refresh' => URL::route('admin.model.field.edit', array(
                    'slug' => Input::get('_standalone_origin'),
                    'id' => Input::get('_standalone_origin_id'),
                    'field' => ':fieldName:'
                ))
            )
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

        return Redirect::route('admin.model.all', array('slug' => $this->modelObject->getRouteName()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $modelName
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy($modelName, $id)
    {
        $event_info = array(
            array(
                "model" => $modelName,
                "id" => $id
            )
        );

        if ($this->model->destroy($id)) {
            // Todo
//            Notify::success('<b>Success!</b> The record has been deleted!', true);
        } else {
            // Todo
//            Notify::danger('<b>Failed!</b> The record could not be deleted!');
        }

        if (Request::ajax()) {
            // todo
            return \Response::json(array());
        }

        return Redirect::back();
    }

}