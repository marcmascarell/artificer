<?php namespace Mascame\Artificer\Plugins\Pagination;

use Redirect;
use Mascame\Artificer\Artificer;
use Input;
use Mascame\Artificer\Plugins\Pagination\PaginationPlugin as Pagination;
use View;

class PaginationController extends Artificer {

	/**
	 * @param $modelName
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function paginate($modelName = null)
	{
		$pagination = Input::get('pagination');
		Pagination::setPagination($pagination, $modelName);

		return Redirect::route('admin.all', array('slug' => $this->modelObject->getRouteName()));
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

		$this->handleData($this->model->orderBy($sort['column'], $sort['direction'])->paginate(Pagination::$pagination));

		View::share('pagination', Pagination::$pagination);

		return View::make($this->getView('all'))
			->with('items', $this->data)
			->with('sort', $sort);
	}
}