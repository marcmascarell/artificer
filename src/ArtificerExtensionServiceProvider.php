<?php namespace Mascame\Artificer;

use Illuminate\Support\ServiceProvider;

/**
 * This class restricts some methods from the service provider.
 *
 * Why?
 *
 * Because we don't want to mess the developers with unwanted modifications (config, added files, ...) until a concrete
 * extension is marked as "installed". So, extending this class will try to prevent that undesired behaviour.
 *
 *
 * Class ArtificerExtensionServiceProvider
 * @package Mascame\Artificer
 */
class ArtificerExtensionServiceProvider extends ServiceProvider {

    /**
     * @throws \Exception
     */
    protected function mergeConfigFrom($path, $key)
    {
        return self::restrictedMethod(__METHOD__);
    }

    /**
     * @throws \Exception
     */
    protected function loadViewsFrom($path, $namespace)
    {
        self::restrictedMethod(__METHOD__);
    }

    /**
     * @throws \Exception
     */
    protected function loadTranslationsFrom($path, $namespace)
    {
        self::restrictedMethod(__METHOD__);
    }

    /**
     * @throws \Exception
     */
    protected function loadMigrationsFrom($paths)
    {
        self::restrictedMethod(__METHOD__);
    }

    /**
     * @throws \Exception
     */
    protected function publishes(array $paths, $group = null)
    {
        self::restrictedMethod(__METHOD__);
    }

    /**
     * @throws \Exception
     */
    public static function pathsToPublish($provider = null, $group = null)
    {
        self::restrictedMethod(__METHOD__);
    }

    /**
     * @throws \Exception
     */
    public function commands($commands)
    {
        return self::restrictedMethod(__METHOD__);
    }

    /**
     * @param $method
     * @throws \Exception
     */
    private static function restrictedMethod($method) {
        throw new \Exception('Artificer extensions should use method "' . $method . '" on its own class.');
    }

}
