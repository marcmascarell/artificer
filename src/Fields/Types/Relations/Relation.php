<?php

namespace Mascame\Artificer\Fields\Types\Relations;

use URL;
use Mascame\Artificer\Artificer;
use Mascame\Formality\Field\Field;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Artificer\Fields\Relationable;
use Mascame\Artificer\Model\ModelSettings;
use Mascame\Artificer\Fields\GuessableRelation;

class Relation extends Field
{
    use Relationable, GuessableRelation;

    /**
     * @var ModelManager;
     */
    public $modelManager;

    /**
     * @var ModelSettings;
     */
    public $modelSettings;

    /**
     * @var bool
     */
    public $relation = true;

    /**
     * @var \Eloquent;
     */
    public $currentModel;

    /**
     * @var
     */
    public $fields;

    /**
     * @var
     */
    public $createRoute;

    /**
     * @var
     */
    public $relatedModel;

    /**
     * Relation constructor.
     * @param $name
     * @param null $value
     * @param array $options
     */
    public function __construct($name, $value = null, $options = [])
    {
        parent::__construct($name, $value, $options);

        $this->modelManager = Artificer::modelManager();
        $this->modelSettings = $this->modelManager->current();
        $this->currentModel = $this->modelSettings->model;
    }

    /**
     * @return mixed
     */
    public function getRelatedInstance()
    {
        return $this->getRelatedModel()->model;
    }

    /**
     * @param $modelSlug
     * @param $id
     * @return string
     */
    public function editRoute($modelSlug, $id)
    {
        return URL::route('admin.model.edit', ['slug' => $modelSlug, 'id' => $id]);
    }

    /**
     * @param $modelSlug
     * @return string
     */
    public function createRoute($modelSlug)
    {
        return URL::route('admin.model.create', ['slug' => $modelSlug]);
    }

    /**
     * @return bool
     */
    public function hasFilter()
    {
        return false;
    }
}
