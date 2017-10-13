<?php

namespace Mascame\Artificer\Extension;

use Illuminate\Support\Str;

trait PublicVendorPaths
{
    /**
     * Typically your published files are going to live inside a "vendor" path.
     *
     * For example: resources/views/vendor/...
     *
     * @return string
     */
    private function getPath()
    {
        return 'vendor/'.$this->getConfigShortPath();
    }

    /**
     * Your config will reside directly under the "admin" path.
     *
     * For example: config/admin/extensions/your-package-name/...
     *
     * @return string
     */
    private function getConfigShortPath()
    {
        return 'admin/extensions/'.$this->slug;
    }

    /**
     * @return mixed
     */
    public function getConfigKey()
    {
        return $this->getDotNotationPath($this->getConfigShortPath());
    }

    /**
     * @param null $path
     * @return mixed
     */
    final public function getDotNotationPath($path = null)
    {
        if (! $path) {
            $path = $this->getPath();
        }

        return str_replace('/', '.', $path);
    }

    /**
     * @param null $key
     * @param null $default
     * @return \Illuminate\Config\Repository|mixed
     */
    public function getConfig($key = null, $default = null)
    {
        if ($key) {
            $key = '.'.$key;
        }

        return config($this->getDotNotationPath($this->getConfigShortPath()).$key, $default);
    }

    /**
     * @param $file
     * @return string
     */
    final public function getConfigPathFile($file)
    {
        if (! Str::endsWith($file, '.php')) {
            $file = $file.'.php';
        }

        return config_path($this->getConfigShortPath().'/'.$file);
    }

    /**
     * Do not use public_path() because AssetManager does not understand it as local asset.
     *
     * Javascript, CSS and images. Will reside in public directory
     *
     * @return string
     */
    final public function getAssetsPath($file = null)
    {
        return $this->appendFile($this->getPath(), $file);
    }

    /**
     * @param null $file
     * @return string
     */
    final public function getConfigPath($file = null)
    {
        return $this->appendFile(config_path($this->getConfigShortPath()), $file);
    }

    /**
     * @param null $file
     * @return string
     */
    final public function getTranslationsPath($file = null)
    {
        return $this->appendFile(resource_path('lang/'.$this->getPath()), $file);
    }

    /**
     * @param null $file
     * @return string
     */
    final public function getViewsPath($file = null)
    {
        return $this->appendFile(resource_path('views/'.$this->getPath()), $file);
    }

    /**
     * @param $path
     * @param $file
     * @return string
     */
    private function appendFile($path, $file)
    {
        if ($file) {
            $file = '/'.$file;
        }

        return $path.$file;
    }
}
