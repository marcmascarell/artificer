<?php

namespace Mascame\Artificer\Controllers;

use Input;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Requests\ArtificerFormRequest;
use Redirect;
use Request;
use Response;
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

        $form = [
            'form_action_route' => 'admin.model.store',
            'form_method' => 'post',
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
            foreach (\Request::all() as $name => $value) {
                if ($value != '' && isset($this->fields[$name])) {
                    $this->fields[$name]->filter($query, $value);
                }
            }
        })
            ->with($this->modelObject->getRelations())
            ->orderBy($sort['column'], $sort['direction'])
            ->get();

        return parent::all($modelName, $data, $sort);
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
     */
    public function edit($modelName, $id)
    {
        $this->handleData(
            $this->model->with($this->modelObject->getRelations())->findOrFail($id)
        );

        $form = [
            'form_action_route' => 'admin.model.update',
            'form_method' => 'put',
        ];

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
        return Response::json([
                'item' => $item->toArray(),
                'refresh' => URL::route('admin.model.field.edit', [
                    'slug' => Input::get('_standalone_origin'),
                    'id' => Input::get('_standalone_origin_id'),
                    'field' => ':fieldName:',
                ]),
            ]
        );
    }

    /**
     * Update or create the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function updateOrCreate(ArtificerFormRequest $request)
    {
        $request->persist();

        // Todo
        //if (Request::ajax()) {
        //    return $this->handleAjaxResponse($model);
        //}

        return Redirect::route('admin.model.all', ['slug' => $this->modelObject->getRouteName()]);
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
        if ($this->model->destroy($id)) {
            // Todo Notify::success('<b>Success!</b> The record has been deleted!', true);
        } else {
            // Todo Notify::danger('<b>Failed!</b> The record could not be deleted!');
        }

        return Request::ajax() ? \Response::json([]) : Redirect::back();
    }
}
