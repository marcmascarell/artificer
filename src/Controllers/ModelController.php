<?php

namespace Mascame\Artificer\Controllers;

use View;
use Request;
use Response;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Requests\ArtificerFormRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ModelController extends BaseModelController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return array
     */
    public function create()
    {
        $data = $this->currentModel;

        return [
            'values' => $this->modelManager->current()->transformValues(collect([$data]))->first(),
            'fields' => $this->modelManager->current()->transformFields(),
        ];
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param $modelName
     * @param $id
     * @return array
     */
    public function edit($modelName, $id)
    {
        $values = $this->currentModel->with($this->modelSettings->getRelations())->findOrFail($id);

        return [
            'values' => $this->modelManager->current()->transformValues(collect([$values]))->first(),
            'fields' => $this->modelManager->current()->transformFields(),
        ];
    }

    /**
     * @param $modelName
     * @return @return \Illuminate\Contracts\View\View
     */
//    public function filter($modelName)
//    {
//        $this->handleData($this->currentModel->firstOrFail());
//
//        $sort = $this->getSort();
//
//        $data = $this->currentModel->where(function ($query) {
//            foreach (\Request::all() as $name => $value) {
//                if ($value != '' && isset($this->fields[$name])) {
//                    $this->fields[$name]->filter($query, $value);
//                }
//            }
//        })
//            ->with($this->modelSettings->getRelations())
//            ->orderBy($sort['column'], $sort['direction'])
//            ->get();
//
//        return parent::all($modelName, $data, $sort);
//    }

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
    public function all($modelName)
    {
        $queryBuilder = $this->currentModel->with($this->modelSettings->getRelations());

        if ($filters = $this->getFilters()) {
            $fields = $this->modelManager->current()->toFields();

            $queryBuilder->where(function ($query) use ($filters, $fields) {
                foreach ($filters as $name => $value) {
                    if (empty($value) || ! isset($fields[$name])) {
                        continue;
                    }

                    $fields[$name]->filter($query, $value);
                }
            });
        }

        if ($sort = $this->getSort()) {
            $queryBuilder->orderBy($sort['sortBy'], $sort['sortByDirection']);
        }

        /**
         * @var LengthAwarePaginator
         */
        $paginatedData = $queryBuilder->paginate((int) request()->get('perPage'));

        return [
            'values' => $this->modelManager->current()->transformValues(collect($paginatedData->items())),
            'fields' => $this->modelManager->current()->transformFields(),
            'pagination' => [
                'currentPage' => $paginatedData->currentPage(),
                'perPage' => $paginatedData->perPage(),
                'total' => $paginatedData->total(),
            ],
            'sortBy' => [
                'column' => $sort['sortBy'],
                'direction' => $sort['sortByDirection'],
            ],
        ];
    }

//    public function field($modelName, $id, $field)
//    {
//        $this->handleData($this->currentModel->with($this->modelSettings->getRelations())->findOrFail($id));
//
    ////        $this->fields[$field]->showFullField = true;
//
//        return \HTML::field($this->fields[$field], AdminOption::get('icons'));
//    }

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
            // Todo
        }

        // Todo
        //if (Request::ajax()) {
        //    return $this->handleAjaxResponse($model);
        //}

        return [
            'route' => route('admin.model.all', ['slug' => $this->modelManager->current()->route]),
        ];
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
        // Todo: improve responses
        if ($this->currentModel->destroy($id)) {
            return \Response::json([]);
        }

        return \App::abort(403);
    }
}
