<?php namespace Mascame\Artificer\Extension;

trait Slugged
{

    /**
     * @var array
     */
    protected $extensionSlugs = [];

    /**
     * @param $slug
     * @param $name
     */
    public function setSlug($slug, $name) {
        $this->extensionSlugs[$slug] = $name;
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getFromSlug($slug) {
        return $this->get($this->extensionSlugs[$slug]);
    }
}