<?php

namespace Mascame\Artificer\Fields;

use Illuminate\Support\Str;
use Mascame\Artificer\Artificer;

trait GuessableRelation
{
    protected function modelHasMethod($method)
    {
        return method_exists(Artificer::modelManager()->model, $method);
    }

    public function guessRelatedMethod()
    {
    }

    public function guessModel()
    {
        $method = $this->guessRelatedMethod();
        $modelName = Str::studly($method);

        if ($method && isset(Artificer::modelManager()->models[$modelName])) {
            return $modelName;
        }
    }
}
