<?php namespace Mascame\Artificer\Fields;

use \Illuminate\Support\Str as Str;

class FieldParser
{
    /**
     * @var array
     */
    protected $types = [];

    /**
     * @var array
     */
    public $typeReason = [];

    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param $field
     * @return bool|int|mixed|string
     */
    public function parse($field)
    {
        $type = $this->detectType($field);

        if (isset($this->types[$type]['onParse'])) {
            $this->types[$type]['onParse']($field, $type);
        }

        return $type;
    }

    /**
     * @param $name
     * @param $types
     * @return bool
     */
    public function isTypeEqual($name, $types)
    {
        if (in_array(snake_case($name), array_keys($types))
            || in_array(strtolower($name), array_keys($types))
        ) {
            $this->setTypeReason($name, 'equal');

            return true;
        }

        return false;
    }

    /**
     * @param $fields
     * @param $name
     * @param $type
     * @return int
     */
    public function getSimilarityPoints($fields, $name, $type)
    {
        $points = 0;

        if ($this->isSimilar($name, $type)) {
            // Gives more importance to similar TYPE than field
            $points = +2;
        }

        foreach ($fields as $field) {
            if ($this->isSimilar($name, $field)) {
                $points++;
            }
        }

        return $points;
    }

    /**
     * @param $name
     * @param $types
     * @return bool|mixed
     */
    public function isTypeSimilar($name, $types)
    {
        $points = array();

        foreach ($types as $type => $data) {
            if (!isset($data['autodetect'])) {
                continue;
            }

            $points[$type] = $this->getSimilarityPoints($data['autodetect'], $name, $type);
        }

        if (max($points) > 0) {
            $this->setTypeReason($name, 'similar to one in admin.fields');

            return array_search(max($points), $points);
        }

        return false;
    }

    /**
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public function isSimilar($haystack, $needle)
    {
        return Str::startsWith($haystack, $needle)
        || Str::endsWith($haystack, $needle)
        || Str::contains($haystack, $needle) ? true : false;
    }

    /**
     * @param $name
     * @param $types
     * @return bool|int|string
     */
    public function isUserType($name, $types)
    {
//        if (!isset($types['autodetect']) || empty($types['autodetect'])) return false;

        foreach ($types as $type => $data) {
            if (!isset($data['autodetect'])) {
                continue;
            }

            if (in_array($name, $data['autodetect'])) {
                $this->setTypeReason($name, 'set by user in admin.fields');

                return $type;
            }
        }

        return false;
    }

    /**
     * @param $name
     * @param $types
     * @return bool|int|string
     */
    public function matchesRegex($name, $types)
    {
        foreach ($types as $type => $data) {
            if (!isset($data['regex'])) {
                continue;
            }

            if (preg_match($data['regex'], $name, $matches)) {
                $this->setTypeReason($name, "matched regex '{$data['regex']}'");

                return $type;
            }
        }

        return false;
    }

    /**
     * @param $name
     * @param $types
     * @return bool|int|mixed|string
     */
    public function detectType($name)
    {
        if ($type = $this->matchesRegex($name, $this->types)) {
            return $type;
        }

        if ($this->isTypeEqual($name, $this->types)) {
            return $name;
        }

        if ($type = $this->isUserType($name, $this->types)) {
            return $type;
        }

        if ($type = $this->isTypeSimilar($name, $this->types)) {
            return $type;
        }

        $this->setTypeReason($name, 'default');

        return $this->types['default']['type'];
    }

    /**
     * @param $name
     * @param $value
     */
    protected function setTypeReason($name, $value)
    {
        $this->typeReason[$name] = $value;
    }

}