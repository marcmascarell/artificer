<?php namespace Mascame\Artificer\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

/**
 * Allows to make calls to tables that don't have Model
 *
 * Class FakeModel
 * @package Mascame\Artificer\Model
 */
class FakeModel
{
    /**
     * @param $modelName
     * @param array $attributes
     * @param string $primaryKey
     * @return \Eloquent
     */
    public static function make($modelName, array $attributes = [], $primaryKey = 'id')
    {
        return

            new class($attributes, self::getTableFromModelName($modelName), $primaryKey) extends \Eloquent {
                use Macroable {
                    __call as macroCall;
                    __callStatic as macroStaticCall;
                }

                protected static $fakeTable;

                protected static $fakePrimaryKey;

                protected $guarded = [];

                public function __construct(array $attributes = [], $table = null, $primaryKey = 'id')
                {
                    if ($table) {
                        self::$fakeTable = $table;
                    }

                    if ($primaryKey) {
                        self::$fakePrimaryKey = $primaryKey;
                    }

                    $this->table = self::$fakeTable;
                    $this->primaryKey = self::$fakePrimaryKey;

                    parent::__construct($attributes);
                }

                public function __call($method, $parameters)
                {
                    if (static::hasMacro($method)) {
                        return $this->macroCall($method, $parameters);
                    }

                    return parent::__call($method, $parameters);
                }


                public static function __callStatic($method, $parameters)
                {
                    if (static::hasMacro($method)) {
                        return static::macroStaticCall($method, $parameters);
                    }

                    return parent::__callStatic($method, $parameters);
                }

                public function hasGetMutator($key)
                {
                    $method = 'get'.Str::studly($key).'Attribute';

                    if (static::hasMacro($method)) return true;

                    return method_exists($this, $method);
                }

                public function hasSetMutator($key)
                {
                    $method = 'set'.Str::studly($key).'Attribute';

                    if (static::hasMacro($method)) return true;

                    return method_exists($this, $method);
                }

            };
    }

    protected static function getTableFromModelName($modelName) {
        // Check if it is already a table
        if ($modelName == Str::snake($modelName)) return $modelName;

        return str_replace('\\', '', Str::snake(Str::plural(class_basename($modelName))));
    }
}