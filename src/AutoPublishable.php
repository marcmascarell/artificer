<?php namespace Mascame\Artificer;

/**
 * How it works: Simply we wait until app is ready to publish whatever is in the vendor's publishable files
 *
 * Class AutoPublishable
 * @package Mascame\Artificer
 */
trait AutoPublishable
{

    protected function isPublished($fileToCheck = null) {
        if (! $fileToCheck) $fileToCheck = config_path($this->name);
        
        return \File::exists($fileToCheck);
    }

    protected function autoPublish() {
        $this->app->booted(function () {
            \Artisan::call('vendor:publish', ['--provider' => self::class]);

            /**
             * Little "hack" because we are not in a Controller so we can not use Redirect.
             *
             * If we don't redirect we won't have files ready for this request
             */
            header('Location: '. \URL::current());
            die();
        });
    }
    
}