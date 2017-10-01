<?php

namespace Mascame\Artificer\Fields;

interface FactoryInterface
{
    /**
     * @param $type
     * @param $name
     * @param $value
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    public function make($type, $name, $value, $options = []);

    /**
     * @param $data
     * @return mixed
     */
    public function makeFields();
}
