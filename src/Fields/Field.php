<?php

namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Artificer;
use Mascame\Formality\Field\FieldInterface;

class Field extends \Mascame\Formality\Field\Field implements FieldInterface
{
    use Filterable,
        HasHooks;

    /**
     * @var array
     */
    protected $widgets = [];

    /**
     * Sometimes ajax limits output, setting this to true will return all.
     *
     * @var bool
     */
    public $showFullField = false;

    /**
     * @var bool
     */
    protected $withWidgets = false;

    /**
     * Field constructor.
     * @param $name
     * @param null $value
     * @param array $options
     */
    public function __construct($name, $value = null, $options = [])
    {
        parent::__construct($name, $value, $options);

        $this->widgets = $this->getInstalledWidgets();

        $this->attachHooks();
    }

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
     * @param null $value
     * @return null
     */
    public function show()
    {
        $value = $this->value ?: $this->getOption('default');

        if ($show = $this->getOption('show')) {
            if (is_callable($show)) {
                return $show($value);
            }
        }

        return $value;
    }

    /**
     * @return bool|mixed|null|string
     */
    public function output()
    {
        if ($this->isHidden()) {
            return;
        }

        if ($this->withWidgets) {
            $this->applyWidgets();
        }

        return parent::output();
    }

    /**
     * @return $this
     */
    public function protectGuarded()
    {
        if (! $this->isFillable()) {
            $this->setOptions([
                'attributes' => array_merge($this->getAttributes(), ['disabled' => 'disabled']),
            ]);
        }

        return $this;
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

    /**
     * @param $array
     * @return bool
     */
    protected function isAll($array)
    {
        return is_array($array) && isset($array[0]) && $array[0] == '*' || $array == '*';
    }

    /**
     * @param string $visibility [visible|hidden]
     * @return bool
     */
    protected function isListedAs($visibility, $action = null)
    {
        if (! $action) {
            $action = Artificer::getCurrentAction();
        }

        $listOptions = Artificer::modelManager()->current()->settings()->getOption($action);

        if (! $listOptions || ! isset($listOptions[$visibility])) {
            return false;
        }

        $list = $listOptions[$visibility];

        if ($this->isAll($list)) {
            return true;
        }

        return $this->isInArray($this->getName(), $list);
    }

    /**
     * Hidden fields have preference.
     *
     * @return bool
     */
    public function isVisible()
    {
        if ($this->isHidden()) {
            return false;
        }

        return $this->isListedAs('visible');
    }

    /**
     * @param $value
     * @param $array
     * @return bool
     */
    public function isInArray($value, $array)
    {
        return (is_array($array) && in_array($value, $array)) ? true : false;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->isListedAs('hidden');
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        return array_get(\View::getShared(), 'fields')[$name];
    }

    /**
     * @param $name
     */
    public function __get($name)
    {
        $accessor = 'get'.studly_case($name);

        return $this->useFieldMethod($accessor);
    }

    /**
     * @param $method
     * @param array $args
     */
    protected function useFieldMethod($method, $args = [])
    {
        if (! method_exists($this, $method)) {
            return;
        }

        return (empty($args)) ? $this->$method() : $this->$method($args);
    }

    public function isFillable()
    {
        $fillable = Artificer::modelManager()->current()->settings()->getFillable();

        return $this->isAll($fillable) || in_array($this->getName(), $fillable);
    }

    /**
     * @param array|string $classes
     */
    protected function mergeClassAttribute($class)
    {
        if (! is_array($class)) {
            $class = [$class];
        }

        $attributes = $this->getAttributes();
        $classes = isset($attributes['class']) ? explode(' ', $attributes['class']) : [];

        return implode(' ', array_merge($classes, $class));
    }

    /**
     * @param string $attribute
     * @param array|string $value
     */
    public function addAttribute($attribute, $value)
    {
        if ($attribute == 'class') {
            $value = $this->mergeClassAttribute($value);
        }

        $this->setOptions(['attributes' => [$attribute => $value]]);
    }
}
