<?php

namespace Mascame\Artificer\Fields\Types;

use Form;
use Illuminate\Support\Collection;
use Mascame\Artificer\Fields\Field;

class Password extends Field
{
    public $filterable = false;

    protected function input()
    {
        return Form::password($this->name, $this->attributes);
    }

    public function show()
    {
        return 'hidden';
    }

    /**
     * Passwords are empty by default.
     * This prevents updating an empty password.
     *
     * @param $fields Collection
     * @param $next
     * @return mixed
     */
    public function updatingHook($data, $next)
    {
        list($field, $model) = $data;

        $model->remember_token = 'pollo';
//        dd('here!!', $data);
//        dd($fields);
//        $fields->filter(function (Field $field) use ($fields) {
//            if ($field->getType() == $this->getType() && empty($field->getValue())) {
//                return false;
//            }
//
//            return true;
//        });

        return $next(['cagunlaputa']);
    }
}
