<?php

namespace Mascame\Artificer\Fields;

use Str;
use Mascame\Artificer\Options\AdminOption;
use Mascame\Artificer\Options\FieldOption;

class FieldParser
{
    public $types;
    public $type_reason;

    public function __construct()
    {
        $this->types = AdminOption::get('types');
    }

    /**
     * @param $field
     * @return bool|int|mixed|string
     */
    public function fieldType($field)
    {
        return $this->autodetectType($field);
    }

    /**
     * @param $name
     * @param $types
     * @return bool
     */
    public function isTypeEqual($name, $types)
    {
        if (in_array($name, array_keys($types))) {
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
        $points = [];

        foreach ($types as $type => $data) {
            if (! isset($data['autodetect'])) {
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
        if (! isset($types['autodetect'])) {
            return false;
        }

        foreach ($types as $type => $data) {
            if (! isset($data['autodetect'])) {
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
     * @return bool|mixed
     */
    public function isTypeInModelConfig($name)
    {
        if (FieldOption::has('type', $name) || FieldOption::has('relationship.type', $name)) {
            $this->setTypeReason($name, 'set by user in {model}.fields');

            return (FieldOption::has('type', $name)) ? FieldOption::get('type', $name) : FieldOption::get('relationship.type', $name);
        }

        return false;
    }

    /**
     * @param $name
     * @param $types
     * @return bool|int|mixed|string
     */
    public function autodetectType($name)
    {
        if ($type = $this->isTypeInModelConfig($name)) {
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
        $this->type_reason[$name] = $value;
    }
}
