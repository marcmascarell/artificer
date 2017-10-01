<?php

namespace Mascame\Artificer\Controllers;

use View;
use Mascame\Artificer\Artificer;
use Illuminate\Support\Facades\Input;

class BaseModelController extends BaseController
{
    /**
     * The Eloquent model instance.
     * @var \Eloquent
     */
    protected $currentModel;

    /**
     * @var \Mascame\Artificer\Model\ModelSettings
     */
    protected $modelSettings;

    public function __construct()
    {
        parent::__construct();

        $this->whenSessionLoaded(function () {
            $model = $this->modelManager->current();

            $this->modelSettings = $model->settings();
            $this->currentModel = $model->model();

            View::share('model', $model);
        });
    }

    /**
     * @param $data
     */
    protected function handleData($data)
    {
//        $this->data = $data;
//        $fields = $this->modelManager->current()->toForm($data);

//        View::share('fields', $fields);
//
//        if (Artificer::getCurrentAction() === Artificer::ACTION_BROWSE) {
//            View::share('values', $this->toValues($this->modelManager->current()->toFields($data), $data));
//        } else {
//            View::share('values', $data);
//        }
    }

    /**
     * @return array
     */
    protected function getSort()
    {
        return [
            'sortBy' =>  Input::get('sortBy', 'id'),
            'sortByDirection' =>  Input::get('sortByDirection', 'asc'),
        ];
    }

    /**
     * @return null|array
     */
    protected function getFilters()
    {
        $filters = Input::get('filters');

        if (! $filters) {
            return;
        }

        return collect($filters)->transform(function ($filter) {
            $filter = json_decode($filter);

            return [$filter->key => $filter->value];
        })->collapse()->toArray();
    }
}
