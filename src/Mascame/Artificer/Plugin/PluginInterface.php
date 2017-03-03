<?php

namespace Mascame\Artificer\Plugin;

interface PluginInterface
{
    public function boot();

    public function meta();
}
