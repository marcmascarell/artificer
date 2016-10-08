<?php

namespace Mascame\Artificer\Assets;

interface AssetsManagerInterface
{
    /**
     * @param array $assets
     * @return $this
     */
    public function add($assets);

    /**
     * Outputs the collected css.
     *
     * @return mixed
     */
    public function css();

    /**
     * Outputs the collected js.
     *
     * @return mixed
     */
    public function js();
}
