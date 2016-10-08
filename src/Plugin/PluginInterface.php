<?php

namespace Mascame\Artificer\Plugin;

interface PluginInterface
{
    /**
     * @return mixed
     */
    public function boot();

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @return \Closure
     */
    public function getRoutes();

    /**
     * @return array
     */
    public function getMenu();
}
