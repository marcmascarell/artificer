<?php namespace Mascame\Artificer\Plugins\Pagination;

use Mascame\Artificer\BaseModelController;
use Redirect;
use Mascame\Artificer\Artificer;
use Input;
use Mascame\Artificer\Plugins\Pagination\PaginationPlugin as Pagination;
use View;

class PaginationController extends BaseModelController {

	/**
	 * @param null $modelName
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function paginate($modelName = null)
	{
		$pagination = Input::get('pagination');
		Pagination::setPagination($pagination, $modelName);

		return Redirect::route('admin.model.all', array('slug' => $this->modelObject->getRouteName()));
	}

    /**
     * @param $modelName
     * @return $this
     */
    public function filter($modelName) {
        $this->handleData($this->model->firstOrFail());

        $sort = $this->getSort();

        $data = $this->model->where(function($query) {

            foreach (Input::all() as $name => $value) {
                if ($value != '' && isset($this->fields[$name])) {
                    $this->fields[$name]->filter($query, $value);
                }
            }

            return $query;
        })->with($this->modelObject->getRelations())->orderBy($sort['column'], $sort['direction'])->paginate(Pagination::$pagination);

        return parent::all($modelName, $data, $sort);
    }

	/**
	 * @param $modelName
	 * @return $this
	 */
	public function all($modelName, $data = null, $sort = null)
	{
		$sort = $this->getSort();

		$data = $this->model->with($this->modelObject->getRelations())->orderBy($sort['column'], $sort['direction'])->paginate(Pagination::$pagination);

		return parent::all($modelName, $data, $sort);
	}
}