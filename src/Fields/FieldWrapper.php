<?php

namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Artificer;
use Mascame\Formality\Field\FieldInterface;
use Mascame\Formality\Field\TypeInterface;

class FieldWrapper
{
    use Filterable;

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
     * @var FieldInterface|TypeInterface
     */
    public $field;

    /**
     * Field constructor.
     * @param FieldInterface|TypeInterface $field
     * @param null $relation
     */
    public function __construct(FieldInterface $field)
    {
        $this->field = $field;

        $this->widgets = $this->getInstalledWidgets();
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
        $widgets = $this->field->getOption('widgets', []);

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
    public function show($value = null)
    {
        $value = ($value) ? $this->field->setValue($value) : $this->field->getOption('default');

        if ($show = $this->field->getOption('show')) {
            if (is_callable($show)) {
                return $show($value);
            }
        }

        return $this->field->show();
    }

    /**
     * @return bool|mixed|null|string
     */
    public function output()
    {
        if ($this->isHidden()) {
            return;
        }

        $field = $this;

        if ($this->withWidgets) {
            $field = $this->applyWidgets();
        }

        return $field->field->output();
    }

    public function protectGuarded()
    {
        if (! $this->isFillable()) {
            $this->field->setOptions([
                'attributes' => array_merge($this->field->getAttributes(), ['disabled' => 'disabled']),
            ]);
        }

        return $this;
    }

    public function withWidgets()
    {
        $this->withWidgets = true;

        return $this;
    }

    protected function applyWidgets()
    {
        $field = $this;

        foreach ($this->widgets as $widget) {
            $widget = Artificer::widgetManager()->get($widget);
            $widget->assets(Artificer::assetManager());

            $field = $widget->field($field);
        }

        return $field;
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

        $listOptions = Artificer::modelManager()->current()->getOption($action);

        if (! $listOptions || ! isset($listOptions[$visibility])) {
            return false;
        }

        $list = $listOptions[$visibility];

        if ($this->isAll($list)) {
            return true;
        }

        return $this->isInArray($this->field->getName(), $list);
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

    public static function get($name)
    {
        return array_get(\View::getShared(), 'fields')[$name];
    }

    public function __get($name)
    {
        $accessor = 'get'.studly_case($name);

        return $this->useFieldMethod($accessor);
    }

    protected function useFieldMethod($method, $args = [])
    {
        if (! method_exists($this->field, $method)) {
            return;
        }

        return (empty($args)) ? $this->field->$method() : $this->field->$method($args);
    }

    public function __call($method, $args)
    {
        return $this->useFieldMethod($method, $args);
    }

    public function isFillable()
    {
        $fillable = Artificer::modelManager()->current()->getFillable();

        return $this->isAll($fillable) || in_array($this->field->getName(), $fillable);
    }

    /**
     * @param array|string $classes
     */
    protected function mergeClassAttribute($class)
    {
        if (! is_array($class)) {
            $class = [$class];
        }

        $attributes = $this->field->getAttributes();
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

        $this->field->setOptions(['attributes' => [$attribute => $value]]);
    }
}
