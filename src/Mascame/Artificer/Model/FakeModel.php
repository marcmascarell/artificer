<?php namespace Mascame\Artificer\Model;

/**
 * Allows to make calls to tables that dont have Model
 *
 * Class FakeModel
 * @package Mascame\Artificer\Model
 */
class FakeModel extends \Eloquent {

	/**
	 * @var
	 */
	public static $fakeTable;

	/**
	 * @var
	 */
	public static $fakePrimaryKey;
//
//	/**
//	 * @param array $attributes
//	 */
	public function __construct(array $attributes = array())
	{
		if (self::$fakeTable) $this->table = self::$fakeTable;
		if (self::$fakePrimaryKey) $this->primaryKey = self::$fakePrimaryKey;

		parent::__construct($attributes);
	}

	protected $guarded = array();

	public $timestamps = false;

	/**
	 * @param $table
	 * @param null $primaryKey
	 * @return static
	 */
	public function setup($config) {
		if (isset($config['table'])) {
			$this->table = $config['table'];
		} else if (isset($config['model'])) {
			$this->table = str_plural(snake_case($config['model']));
		}

		if (isset($config['primaryKey'])) {
			$this->primaryKey = $config['primaryKey'];
		}

		self::$fakeTable = $this->table;
		self::$fakePrimaryKey = $this->primaryKey;

		return $this;
	}
}