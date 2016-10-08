<?php

namespace Mascame\Artificer\Plugin;

use Mascame\Artificer\Artificer;
use Mascame\Artificer\Extension\Slugged;

class Manager extends \Mascame\Artificer\Extension\Manager
{
    use Slugged;

    protected $type = 'plugins';

    public function outputCoreRoutes()
    {
        return $this->outputRoutes($outputCore = true);
    }

    /**
     * @param bool $outputCore
     */
    public function outputRoutes($outputCore = false)
    {
        $installedPlugins = $this->installer()->getInstalled();

        foreach ($installedPlugins as $extension) {
            $extensionInstance = $this->get($extension);
            $isCoreExtension = Artificer::isCoreExtension($extension);

            if ($isCoreExtension && $outputCore) {
                $extensionInstance->getRoutes();
            }

            if (! $isCoreExtension && ! $outputCore) {
                \Route::group(['prefix' => $extensionInstance->getSlug()], function () use ($extensionInstance) {
                    $extensionInstance->getRoutes();
                });
            }
        }
    }
}
