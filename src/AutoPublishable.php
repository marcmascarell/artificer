<?php namespace Mascame\Artificer;

use Illuminate\Support\Facades\App;

/**
 * How it works: Simply we wait until app is ready to publish whatever is in the vendor's publishable files
 *
 * Class AutoPublishable
 * @package Mascame\Artificer
 */
trait AutoPublishable
{
    /**
     * @var App
     */
    protected $app;

    /**
     * Should it run only in development mode?
     *
     * @var bool
     */
    protected $onlyDevelopment = true;

    /**
     * Determines if we are missing one or more publishable directories
     *
     * @var bool
     */
    private $needsPublish = false;

    /**
     * @param null $fileToCheck
     * @return mixed
     */
    protected function isPublished($fileToCheck = null) {
        if (! $fileToCheck) $fileToCheck = config_path($this->name);

        return \File::exists($fileToCheck);
    }

    /**
     * Add files to publishable array & autopublish them in case directory does not exist
     *
     * @param array $paths
     * @param null $group
     */
    protected function publishes(array $paths, $group = null) {
        parent::publishes($paths, $group);

        if ($this->onlyDevelopment && App::environment() != 'local') return;

        if ($this->needsPublish) return;

        foreach ($paths as $path) {
            if (! $this->isPublished($path)) {
                $this->needsPublish = true;
                return;
            }
        }
    }

    protected function autoPublishes(\Closure $closure) {
        $closure();

        if ($this->needsPublish) {
            $this->autoPublish();
        }
    }

    /**
     * Publish vendor files when app is ready
     */
    protected function autoPublish() {
        \Artisan::call('vendor:publish', ['--provider' => self::class]);

        /**
         * Little "hack" because we are not in a Controller so we can not use Redirect.
         *
         * We have to refresh (we won't have files ready for this request)
         */
        sleep(1);

        header('Location: '. \URL::current());
        die();
    }
    
}