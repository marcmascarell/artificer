<?php

namespace Mascame\Artificer\Extension;


use Illuminate\Support\Str;

trait PublicVendorPaths
{

    /**
     * Typically your published files are going to live inside a "vendor" path
     *
     * For example: resources/views/vendor/...
     *
     * @return string
     */
    private function getPath()
    {
        return 'vendor/' . $this->getConfigShortPath();
    }

    /**
     * Your config, instead, will reside directly under the "admin" path
     *
     * For example: config/admin/extensions/your-package-name/...
     *
     * @return string
     */
    private function getConfigShortPath()
    {
        return 'admin/extensions/' . $this->slug;
    }

    final public function getDotNotationPath($path = null) {
        if (! $path) $path = $this->getPath();

        return str_replace('/', '.', $path);
    }

    public function getConfig($key = null, $default = null)
    {
        if ($key) $key = "." . $key;

        return config($this->getDotNotationPath($this->getConfigShortPath()) . $key, $default);
    }

    final public function getConfigPath($file = null)
    {
        if ($file) $file = '/' . $file;

        return config_path($this->getConfigShortPath() . $file);
    }

    final public function getConfigPathFile($file)
    {
        if (! Str::endsWith($file, '.php')) $file = $file . '.php';

        return config_path($this->getConfigShortPath() . '/' . $file);
    }

    final public function getTranslationsPath($file = null)
    {
        if ($file) $file = '/' . $file;

        return resource_path('lang/' . $this->getPath() . $file);
    }

    final public function getViewsPath($file = null)
    {
        if ($file) $file = '/' . $file;

        return resource_path('views/' . $this->getPath() . $file);
    }

    /**
     * Do not use public_path() because AssetManager does not understand it as local asset
     *
     * Javascript, CSS and images. Will reside in public directory
     *
     * @return string
     */
    final public function getAssetsPath($file = null)
    {
        if ($file) $file = '/' . $file;

        return $this->getPath() . $file;
    }

}