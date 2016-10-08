<?php

namespace Mascame\Artificer\Model;

use Mascame\Artificer\Artificer;

class ModelRelation
{
    /**
     * @var
     */
    public $relations;

    /**
     * @return array|mixed
     */
    public function get()
    {
        if (! empty($this->relations)) {
            return $this->relations;
        }

        $fields = Artificer::modelManager()->getOption('fields', []);

        if (empty($fields)) {
            return [];
        }

        return $this->relations = $this->getFieldsWithRelations($fields);
    }

    /**
     * @param $field
     * @return bool
     */
    private function hasRelation($field)
    {
        return isset($field['relationship']) && isset($field['relationship']['method']);
    }

    /**
     * @param $fields
     * @return array
     */
    private function getFieldsWithRelations($fields)
    {
        $relations = [];

        foreach ($fields as $field) {
            if ($this->hasRelation($field)) {
                $relations[] = $field['relationship']['method'];
            }
        }

        return $relations;
    }
}
