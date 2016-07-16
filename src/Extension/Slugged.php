<?php namespace Mascame\Artificer\Extension;

trait Slugged
{

    /**
     * @var array
     */
    protected $extensionSlugs = [];

    /**
     * @param $slug
     * @param $namespace
     */
    public function setSlug($slug, $namespace) {
        $this->extensionSlugs[$slug] = $namespace;
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getFromSlug($slug) {
        return $this->get($this->extensionSlugs[$slug]);
    }
}