<?php namespace Mascame\Artificer\Plugins\Pagination;

use Illuminate\Pagination\Paginator;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Plugin\AbstractPlugin;
use Event;
use Input;
use Session;

class PaginationPlugin extends AbstractPlugin {

	public static $pagination;
	public static $per_page_key;

	public function meta()
	{
		$this->version = '1.0';
		$this->name = 'Pagination';
		$this->description = 'Provides Laravel pagination to models';
		$this->author = 'Marc Mascarell';
	}

	public function boot()
	{
		self::$per_page_key = $this->configKey .'.per_page';
		self::$pagination = $this->getPagination();
		\App::make('paginator')->setViewName($this->getOption('view'));

		\View::share('artificer_pagination', self::$pagination);

		$this->addHooks();
	}

	public function addHooks()
	{
		Event::listen(array('artificer.before.list', 'artificer.after.list'), function ($items) {
			print $items->appends(Input::except('page'))->links();
		});
	}

	/**
	 * @return mixed
	 */
	public function getPagination()
	{
		if (Session::has(self::$per_page_key)) {
			return Session::get(self::$per_page_key);
		}

		$items_per_page = $this->getOption('per_page');
		Session::set(self::$per_page_key, $items_per_page);

		return $items_per_page;
	}


	/**
	 * @param $number
	 */
	public static function setPagination($number, $modelName)
	{
		self::$pagination = $number;
		Session::set(self::$per_page_key . '.' . $modelName, $number);
	}

}