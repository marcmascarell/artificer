<?php

namespace Mascame\Artificer\Support;

class DataType
{
    /**
     * Casts JS FormData.
     *
     * @param $data
     * @param null|array $types
     * @return array
     */
    public static function cast($data, $types = null)
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

    /**
     * @param $path
     * @param $key
     */
    public static function mergeConfigFrom($path, $key)
    {
        if (is_dir($path)) {
            $files = \File::allFiles($path);

            /*
             * @var \Symfony\Component\Finder\SplFileInfo
             */
            foreach ($files as $file) {
                $fileName = $file->getBasename('.php');
                $filePath = str_replace('/', '.', $file->getRelativePath());

                $congifKey = str_finish($key.'.'.$filePath, '.').$fileName;

                self::mergeConfigFrom($file->getRealPath(), $congifKey);
            }

            return;
        }

        self::mergeConfig($key, require $path);
    }

    /**
     * @param $key
     * @param array $values
     */
    public static function mergeConfig($key, $values = [])
    {
        $config = config($key, []);

        config([
            $key => array_replace_recursive($values, $config),
        ]);
    }
}
