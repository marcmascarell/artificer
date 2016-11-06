<?php

namespace Mascame\Artificer\Fields;

use Illuminate\Support\Str;
use Mascame\Artificer\Artificer;

trait GuessableRelation
{
    protected function modelHasMethod($method)
    {
        return method_exists(Artificer::modelManager()->current()->model, $method);
    }

    // Todo
    protected function guessRelatedMethod()
    {
    }

    public function guessModel()
    {
        $method = $this->guessRelatedMethod();
        $modelName = Str::studly($method);

        if ($method && Artificer::modelManager()->has($modelName)) {
            return $modelName;
        }
    }
}
