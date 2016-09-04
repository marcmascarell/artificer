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
    public static function make($modelName, $options = [], array $attributes = [])
    {
        $options = array_merge([
            'table' => null,
            'primaryKey' => 'id',
            'connection' => null
        ], $options);

        if ($options['table'] == null) $options['table'] = self::getTableFromModelName($modelName);

        return

            new class($attributes, $options) extends \Eloquent {
                use Macroable {
                    __call as macroCall;
                    __callStatic as macroStaticCall;
                }

                protected static $fakeTable = null;
                protected static $fakePrimaryKey = 'id';
                protected static $fakeConnection = null;

                protected $guarded = [];

                public function __construct(array $attributes = [], $options = [])
                {
                    foreach ($options as $key => $value) {
                        if ($value) {
                            $propertyName = 'fake' . studly_case($key);
                            self::$$propertyName = $value;
                        }
                    }

                    $this->table = self::$fakeTable;
                    $this->connection = self::$fakeConnection;
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