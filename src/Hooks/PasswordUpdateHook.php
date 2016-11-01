<?php

namespace Mascame\Artificer\Hooks;

use Mascame\Artificer\Fields\Field;
use Mascame\Hooky\HookContract;

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
            if ($field->getType() == 'password') {
                if (empty($field->getValue())) {
                    return false;
                }
            }

            return true;
        });

        return $next($fields);
    }
}
