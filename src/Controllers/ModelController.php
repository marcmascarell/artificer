<?php

namespace Mascame\Artificer\Controllers;

use View;
use Request;
use Redirect;
use Response;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Requests\ArtificerFormRequest;

class ModelController extends BaseModelController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->handleData($this->currentModel);

        $form = [
            'formActionRoute' => 'admin.model.store',
            'formMethod' => 'post',
        ];

        return View::make($this->getView('edit'))->with('items', $this->data)->with($form);
    }

    /**
     * @param $modelName
     * @return @return \Illuminate\Contracts\View\View
     */
    public function filter($modelName)
    {
        $this->handleData($this->currentModel->firstOrFail());

        $sort = $this->getSort();

        $data = $this->currentModel->where(function ($query) {
            foreach (\Request::all() as $name => $value) {
                if ($value != '' && isset($this->fields[$name])) {
                    $this->fields[$name]->filter($query, $value);
                }
            }
        })
            ->with($this->modelSettings->getRelations())
            ->orderBy($sort['column'], $sort['direction'])
            ->get();

        return parent::all($modelName, $data, $sort);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return @return \Illuminate\Contracts\View\View
     */
    public function show($modelName, $id)
    {
        $this->handleData($this->currentModel->findOrFail($id));

        return View::make($this->getView('show'))->with('item', $this->data);
    }

    /**
     * Display the specified post.
     */
    public function all($modelName, $data = null, $sort = null)
    {
        $sort = $this->getSort();

        $data = $this->currentModel->with($this->modelSettings->getRelations())->orderBy(
            $sort['column'],
            $sort['direction']
        )->get();

        return parent::all($modelName, $data, $sort);
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param $modelName
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($modelName, $id)
    {
        $this->handleData(
            $this->currentModel->with($this->modelSettings->getRelations())->findOrFail($id)
        );

        $form = [
            'formActionRoute' => 'admin.model.update',
            'formMethod' => 'put',
        ];

        return View::make($this->getView('edit'))
            ->with('items', $this->data)
            ->with($form);
    }

    public function field($modelName, $id, $field)
    {
        $this->handleData($this->currentModel->with($this->modelSettings->getRelations())->findOrFail($id));

        $this->fields[$field]->showFullField = true;

        return \HTML::field($this->fields[$field], AdminOption::get('icons'));
    }

    /**
     * Update or create the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function updateOrCreate(ArtificerFormRequest $request)
    {
        $result = $request->persist();

        if (! $result) {
            flash()->success('Failed.');
        } elseif ($request->isUpdating()) {
            flash()->success('Updated.');
        } else {
            flash()->success('Created.');
        }

        // Todo
        //if (Request::ajax()) {
        //    return $this->handleAjaxResponse($model);
        //}

        return Redirect::route('admin.model.all', ['slug' => $this->modelManager->current()->route]);
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
        if ($this->currentModel->destroy($id)) {
            flash()->success('Deleted.');
        } else {
            flash()->error('Failed.');
        }

        return Request::ajax() ? \Response::json([]) : Redirect::back();
    }
}
