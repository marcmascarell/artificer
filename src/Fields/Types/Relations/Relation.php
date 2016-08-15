<?php namespace Mascame\Artificer\Fields\Types\Relations;

use Mascame\Artificer\Artificer;
use Mascame\Artificer\Fields\GuessableRelation;
use Mascame\Artificer\Fields\Relationable;
use Mascame\Artificer\Model\ModelManager;
use Mascame\Formality\Field\Field;
use Route;
use URL;

class Relation extends Field
{
    use Relationable, GuessableRelation;

    /**
     * @var ModelManager;
     */
    public $modelObject;

    public $relation = true;

    /**
     * @var ModelManager;
     */
    public $model;
    public $fields;
    public $createURL;
    public $relatedModel;

    /**
     * Relation constructor.
     */
    public function __construct($name, $value = null, $options = [])
    {
        parent::__construct($name, $value, $options);

        $this->modelObject = Artificer::modelManager();
    }

    public function getRelatedInstance() {
        return $this->getRelatedModel()['instance'];
    }

    public function editURL($model_route, $id)
    {
        return URL::route('admin.model.edit', array('slug' => $model_route, 'id' => $id));
    }

    public function createURL($model_route)
    {
        return URL::route('admin.model.create', array('slug' => $model_route));
    }

    public function hasFilter()
    {
        return false;
    }

}