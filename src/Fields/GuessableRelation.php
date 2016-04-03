<?php namespace Mascame\Artificer\Fields;

use Illuminate\Support\Str;
use Mascame\Artificer\Artificer;

trait GuessableRelation
{
    protected function modelHasMethod($method) {
        return method_exists(Artificer::getModel()->model, $method);
    }

    public function guessRelatedMethod() {
        return null;
    }

    public function guessModel() {
        $method = $this->guessRelatedMethod();
        $modelName = Str::studly($method);

        if ($method && isset(Artificer::getModel()->models[$modelName])) {
            return $modelName;
        }

        return null;
    }
}