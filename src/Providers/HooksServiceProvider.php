<?php

namespace Mascame\Artificer\Providers;

use Mascame\Artificer\Artificer;
use Illuminate\Support\ServiceProvider;

class HooksServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $hooks = [
//        ModelHook::UPDATING => [
//            FooHandler::class,
//        ],
    ];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        foreach ($this->hooks as $hook => $handlers) {
            if (empty($handlers)) {
                continue;
            }

            Artificer::hook()->to($hook, $handlers);
        }
    }
}
