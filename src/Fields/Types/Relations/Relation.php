<?php

namespace Mascame\Artificer\Fields\Types\Relations;

use Mascame\Artificer\Artificer;
use Illuminate\Support\Collection;
use Mascame\Artificer\Fields\Field;
use Mascame\Artificer\Fields\Relationable;
use Mascame\Artificer\Model\ModelSettings;
use Mascame\Artificer\Fields\GuessableRelation;

class Relation extends Field
{
    use Relationable,
        GuessableRelation;

    /**
     * @var ModelSettings;
     */
    protected $modelSettings;

    /**
     * @var \Eloquent;
     */
    protected $currentModel;

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
    public function __construct($type, $name, $options = [])
    {
        parent::__construct($type, $name, $options);

        $this->modelSettings = Artificer::modelManager()->current()->settings();
        $this->currentModel = Artificer::modelManager()->current()->model();
        $this->relatedModel = $this->getRelatedModel();
    }

    /**
     * @return mixed
     */
    public function getRelatedInstance()
    {
        return $this->getRelatedModel()->model;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getRelationOptions()
    {
        $relatedModel = $this->getRelatedModel();

        if (! $relatedModel) {
            throw new \Exception('Missing relation in config for the current model.');
        }

        return $this->transformToVisibleProperties(
            $relatedModel->model->get([$this->getKeyProperty(), $this->getShownProperty()])
        );
    }

    /**
     * @param $collection Collection|\Illuminate\Database\Eloquent\Collection
     * @return Collection|\Illuminate\Database\Eloquent\Collection
     */
    public function transformToVisibleProperties($collection)
    {
        $showColumn = $this->getShownProperty();
        $valueColumn = $this->getKeyProperty();

        return $collection->transform(function ($item) use ($valueColumn, $showColumn) {
            return [
                'label' => $item[$showColumn],
                'value' => $item[$valueColumn],
            ];
        });
    }

    /**
     * @return bool
     */
    public function hasFilter()
    {
        return false;
    }
}
