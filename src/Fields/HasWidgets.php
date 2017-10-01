<?php

namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Artificer;

trait HasWidgets
{
    /**
     * @var array
     */
    protected $widgets = [];

    /**
     * @var bool
     */
    protected $withWidgets = false;

    /**
     * Only get widgets that are installed.
     *
     * @return array
     */
    protected function getInstalledWidgets()
    {
        $installedWidgets = [];
        $widgetManager = Artificer::widgetManager();
        $widgets = $this->getOption('widgets', []);

        foreach ($widgets as $widget) {
            if ($widgetManager->isInstalled($widget)) {
                $installedWidgets[] = $widget;
            }
        }

        return $installedWidgets;
    }

    /**
     * @return $this
     */
    public function withWidgets()
    {
        $this->withWidgets = true;

        return $this;
    }

    /**
     * @return $this
     */
    protected function applyWidgets()
    {
        foreach ($this->widgets as $widget) {
            $widget = Artificer::widgetManager()->get($widget);
            $widget->assets(Artificer::assetManager());

            return $widget->field($this);
        }

        return $this;
    }
}
