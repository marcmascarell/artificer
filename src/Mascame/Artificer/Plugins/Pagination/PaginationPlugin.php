<?php namespace Mascame\Artificer\Plugins\Pagination;

use Mascame\Artificer\Artificer;
use Mascame\Artificer\Plugin;
use Config;
use View;
use Event;
use Input;
use Paginator;
use Session;
use Mascame\Artificer\Options\Option;

class PaginationPlugin extends Plugin {

	public static $pagination;

	public function __construct($namespace, $model = null)
	{
		parent::__construct($namespace, __DIR__);

		$this->version = '1.0';
		$this->name = 'Pagination';
		$this->description = 'Provides Laravel pagination to models';
		$this->author = 'Marc Mascarell';
		$this->options = array();

		self::$pagination = $this->getPagination($model);
		$this->addHooks();
	}

	public function addHooks()
	{
		Event::listen(array('before-list', 'after-list'), function ($items) {
			print $items->appends(Input::except('page'))->links();
		});
	}

	/**
	 * @return mixed
	 */
	public function getPagination($config)
	{
		$key = $config . '.' . Option::$config_path . 'pagination.per_page';
		Paginator::setViewName(Config::get($this->configKey . '/pagination.view'));

		if (Session::has($key)) {
			return Session::get($key);
		}

		$items_per_page = Config::get($this->configKey . '/pagination.per_page');
		Session::set($key, $items_per_page);

		return $items_per_page;
	}


	/**
	 * @param $number
	 */
	public static function setPagination($number, $config = null)
	{
		$key = $config . '.' . Option::$config_path . 'pagination.per_page';
		self::$pagination = $number;
		Session::set($key, $number);
	}

}