<?php

namespace Mascame\Artificer;

use Illuminate\Foundation\AliasLoader as Loader;

/**
 * Class ServiceProviderLoader.
 */
trait ServiceProviderLoader
{
    /**
     * @param array $providers
     */
    protected function providers(array $providers)
    {
        $this->loadProviders($providers);
    }

    /**
     * @param array $aliases
     */
    protected function aliases(array $aliases)
    {
        $this->loadAliases($aliases);
    }

    /**
     * @param $providers
     */
    private function loadProviders($providers)
    {
        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * @param $aliases
     */
    private function loadAliases($aliases)
    {
        $loader = Loader::getInstance();

        foreach ($aliases as $alias => $class) {
            $loader->alias($alias, $class);
        }
    }
}
