<?php

namespace Mascame\Artificer;

use Illuminate\Support\Facades\App;
use Mascame\Artificer\Options\AdminOption;

/**
 * How it works: Simply we wait until app is ready to publish whatever is in the vendor's publishable files.
 *
 * Class AutoPublishable
 */
trait Themable
{
    /**
     * @var App
     */
    protected $app;

    protected static $themes;

    /**
     * @return mixed
     */
    public static function getThemes()
    {
        return self::$themes;
    }

    /**
     * @param $name
     * @param \Closure $closure Do whatever the theme needs to run
     */
    public static function registerTheme($name, \Closure $closure)
    {
        if (AdminOption::get('theme') == $name) {
            $closure();
        }

        self::$themes[] = $name;
    }
}
