<?php

namespace Mascame\Artificer\Fields;

use Illuminate\Support\Str;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Hooks\ModelHook;

trait HasHooks
{
    /**
     * @var array
     */
    protected static $hooks = [
        ModelHook::CREATED => 'createdHook',
        ModelHook::CREATED => 'createdHook',
        ModelHook::UPDATING => 'updatingHook',
        ModelHook::UPDATED => 'updatedHook',
        ModelHook::SAVING => 'savingHook',
        ModelHook::SAVED => 'savedHook',
        ModelHook::DELETING => 'deletingHook',
        ModelHook::DELETED => 'deletedHook',
        ModelHook::RESTORING => 'restoringHook',
        ModelHook::RESTORED => 'restoredHook',
    ];

    /**
     * Indicates if hooks were already attached.
     *
     * @var bool
     */
    private $hooksAttached = false;

    protected function attachHooks()
    {
        if ($this->hooksAttached) {
            return;
        }

        foreach (self::$hooks as $hook => $handler) {
            Artificer::hook()->to($hook, function ($data, $next) use ($handler) {
                return $this->{$handler}($data, $next);
            });

            app('events')->listen("eloquent.{$hook}: *", (function (\Eloquent $model) use ($hook) {
                Artificer::hook()->fire($hook, [$this, $model]);
            })->bindTo($this));
        }

        $this->hooksAttached = true;
    }

    /**
     * @return $this
     */
    public function withHooks()
    {
        $this->attachHooks();

        return $this;
    }

    /**
     * @param $data
     * @param $next
     * @return mixed
     */
    private function defaultHook($data, $next)
    {
        return $next($data);
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (! Str::endsWith($method, 'Hook')) {
            // Do the usual PHP behaviour
            trigger_error('Call to undefined method '.__CLASS__.'::'.$method.'()', E_USER_ERROR);
        }

        if (! method_exists($this, $method)) {
            $method = 'defaultHook';
        }

        return call_user_func_array([$this, $method], $arguments);
    }
}
