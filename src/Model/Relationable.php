<?php

namespace Mascame\Artificer\Model;

trait Relationable
{
    /**
     * @return array|mixed
     */
    public function getRelations()
    {
        $fields = $this->getOption('fields', []);

        if (empty($fields)) {
            return [];
        }

        return $this->getFieldsWithRelations($fields);
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
