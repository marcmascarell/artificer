<?php namespace Mascame\Artificer\Assets;


interface AssetsManagerInterface {

    /**
     * @param array $assets
     * @return $this
     */
    public function add($assets);

    /**
     * Clears all assets
     *
     * @return $this
     */
    public function reset();

}
