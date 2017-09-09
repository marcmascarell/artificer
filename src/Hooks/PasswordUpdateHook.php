<?php

namespace Mascame\Artificer\Hooks;

use Mascame\Hooky\HookContract;
use Mascame\Artificer\Fields\Field;

class PasswordUpdateHook implements HookContract
{
    /**
     * Passwords are empty by default.
     * This prevents updating an empty password.
     *
     * @param $fields
     * @param $next
     * @return mixed
     */
    public function handle($fields, $next)
    {
        $fields = array_filter($fields, function (Field $field) use ($fields) {
            if ($field->getType() == 'password' && empty($field->getValue())) {
                return false;
            }

            return true;
        });

        return $next($fields);
    }
}
