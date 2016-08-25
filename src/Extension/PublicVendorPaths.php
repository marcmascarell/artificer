<?php

namespace Mascame\Artificer\Extension;


trait PublicVendorPaths
{

    private function getPath()
    {
        return 'vendor/' . $this->getConfigShortPath();
    }

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

    final public function getConfigPath()
    {
        return config_path($this->getConfigShortPath());
    }

    final public function getTranslationsPath()
    {
        return resource_path('lang/' . $this->getPath());
    }

    final public function getViewsPath()
    {
        return resource_path('views/' . $this->getPath());
    }

    final public function getAssetsPath()
    {
        return public_path('views/' . $this->getPath());
    }

}