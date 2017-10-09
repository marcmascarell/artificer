<?php

namespace Mascame\Artificer\Fields\Types\Relations;

class hasOne extends Relation
{
    protected $id;

    /**
     * @return array|bool|mixed|null
     */
    public function guessRelatedMethod()
    {
        // case 'model_id'

        $method = str_replace('_id', '', $this->name);

        if ($this->modelHasMethod($method)) {
            return $method;
        }

        // case 'my_current_model_id'
        $method = explode('_', $this->name);
        $method = isset(array_reverse($method)[1]) ? array_reverse($method)[1] : null;

        if ($this->modelHasMethod($method)) {
            return $method;
        }

        return false;
    }
}
