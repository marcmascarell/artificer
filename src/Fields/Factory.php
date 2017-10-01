<?php

namespace Mascame\Artificer\Fields;

use Illuminate\Support\Str as Str;
use Mascame\Formality\ParserInterface;
use Mascame\Artificer\Fields\Types\Relations\Relation;

class Factory
{
    /**
     * @var array
     */
    protected $types;

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * @var string
     */
    protected $namespace = '\Mascame\Artificer\Fields\Types\\';

    /**
     * @var array
     */
    protected $classMap = [];

    /**
     * Factory constructor.
     * @param ParserInterface $parser
     * @param array $types
     * @param array $classMap
     */
    public function __construct(ParserInterface $parser, $types = [], $classMap = [])
    {
        $this->parser = $parser;
        $this->types = $types;
        $this->classMap = $classMap;
    }

    /**
     * @param $type
     * @param $name
     * @param array $options
     * @return mixed
     */
    public function make($type, $name, $options = [])
    {
        $typeClass = $this->getFieldTypeClass($type);

        return new $typeClass($type, $name, $options);
    }

    /**
     * @param $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param $type
     * @throws \Exception
     */
    protected function getFieldTypeClass($type)
    {
        if (isset($this->classMap[$type])) {
            return $this->classMap[$type];
        }

        $typeClass = $this->namespace.Str::studly($type);

        return class_exists($typeClass) ? $typeClass : Field::class;
    }

    /**
     * @param $type
     * @return array
     */
    public function getTypeOptions($type)
    {
        return $this->types[$type] ?? [];
    }

    /**
     * @param $fields
     * @return \Illuminate\Support\Collection
     */
    public function makeFields($fields)
    {
        foreach ($fields as $name => $options) {
            $options['type'] = $options['type'] ?? $this->parser->parse($name);

            $fields[$name] = $this->make(
                $options['type'],
                $name,
                array_merge(
                    $this->getTypeOptions('default'),
                    $this->getTypeOptions($options['type']),
                    $options
                )
            );
        }

        foreach ($fields as $key => $field) {
            /**
             * @var Relation
             */
            if ($field->isRelation()) {
                $field = $this->completeRelation($field);
            }

            $fields[$key] = $field;
        }

        return collect($fields);
    }

    /**
     * @param $field Relation
     * @return mixed
     */
    public function completeRelation($field)
    {
        $relationship = $field->getOption('relationship', []);

        $completedRelation = [];
//        $completedRelation = [
//            'method' => $field->guessRelatedMethod(),
//            'type' => $field->getType(),
//            'model' => $field->guessModel(),
//            'show' => function ($value) {
//                if (! is_array($value) && method_exists($value, 'toArray')) {
//
//                    // Avoids cryptic errors
//                    try {
//                        $value = $value->toArray();
//                    } catch (\Exception $e) {
//                        var_dump($e->getMessage());
//                    }
//                }
//
//                // Jump to next column avoiding 'id'
//                return array_values(array_slice($value, 1, 1))[0];
//            },
//        ];

        // user config takes preference
        $field->setOptions(['relationship' => array_merge($completedRelation, $relationship)]);

        return $field;
    }
}
