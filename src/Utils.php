<?php

namespace Mascame\Artificer;

class Utils
{
    /**
     * Casts JS FormData.
     *
     * @param $data
     * @param null|array $types
     * @return array
     */
    public static function castData($data, $types = null)
    {
        $fields = $data;
        $types = $types ?? (isset($fields['_types'])) ? json_decode($fields['_types'], true) : null;

        if ($types) {
            $fields = collect($fields)->transform(function ($value, $name) use ($types) {
                $type = $types[$name] ?? null;

                switch ($type) {
                    case 'undefined':
                    case 'null':
                        $castedValue = null;
                        break;
                    case 'number':
                        $castedValue = $value + 0; // Casts to int or float
                        break;
                    case 'boolean':
                        $castedValue = (bool) $value;
                        break;
                    case 'array':
                        $castedValue = (array) $value;
                        break;
                    case 'object':
                        $castedValue = (object) $value;
                        break;
                    case 'string':
                        $castedValue = (string) $value;
                        break;
                    default:
                        $castedValue = $value;
                        break;
                }

                return $castedValue;
            })->toArray();
        }

        return $fields;
    }
}
