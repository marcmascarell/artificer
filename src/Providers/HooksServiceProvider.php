<?php

namespace Mascame\Artificer\Providers;

use Illuminate\Support\ServiceProvider;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Hooks\Hook;
use Mascame\Artificer\Hooks\PasswordUpdateHook;

class HooksServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $hooks = [
        Hook::UPDATING => [
            PasswordUpdateHook::class,
        ],
        Hook::CREATING => [
            // Hooks
        ],
        Hook::UPDATED => [
            // Hooks
        ],
        Hook::CREATED => [
            // Hooks
        ],
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
