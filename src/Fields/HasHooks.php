<?php

namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Hooks\ModelHook;

trait HasHooks
{
    /**
     * @var array
     */
    protected static $hooks = [
        ModelHook::CREATING  => 'creatingHook',
        ModelHook::CREATED   => 'createdHook',
        ModelHook::UPDATING  => 'updatingHook',
        ModelHook::UPDATED   => 'updatedHook',
        ModelHook::SAVING    => 'savingHook',
        ModelHook::SAVED     => 'savedHook',
        ModelHook::DELETING  => 'deletingHook',
        ModelHook::DELETED   => 'deletedHook',
        ModelHook::RESTORING => 'restoringHook',
        ModelHook::RESTORED  => 'restoredHook',
    ];

    protected function attachHooks()
    {
        foreach (self::$hooks as $hook => $handler) {
            if (! method_exists($this, $handler)) {
                continue;
            }

            app('events')->listen("eloquent.{$hook}: *", (function ($eventName, $data) use ($handler) {
                $model = $data[0];

                return call_user_func_array([$this, $handler], [$model]);
            })->bindTo($this));
        }
    }
}
