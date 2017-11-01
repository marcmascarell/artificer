<?php

namespace Mascame\Artificer;

class Config
{
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
