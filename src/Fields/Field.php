<?php

namespace Mascame\Artificer\Fields;

use Mascame\Artificer\Artificer;

class Field extends AbstractField implements FieldInterface
{
    use Filterable,
        HasHooks,
        HasWidgets;

    /**
     * Field constructor.
     * @param $type
     * @param $name
     * @param array $options
     */
    public function __construct($type, $name, $options = [])
    {
        $this->type = $type;
        $this->name = $name;
        $this->options = $options;

        $this->widgets = $this->getInstalledWidgets();

        $this->attachHooks();
    }

    /**
     * @return bool
     */
    public function isRelation()
    {
        return (bool) $this->getOption('relationship', false);
    }

    /**
     * @param $array
     * @return bool
     */
    protected function isAll($array)
    {
        return is_array($array)
                && isset($array[0])
                && ($array[0] == '*' || $array == '*');
    }

    /**
     * @param string $visibility [visible|hidden]
     * @return bool
     */
    protected function hasVisibility($visibility)
    {
        $visibilityOptions = Artificer::modelManager()->current()->settings()->getOption(
            Artificer::getCurrentAction()
        );

        if (! $visibilityOptions || ! isset($visibilityOptions[$visibility])) {
            return false;
        }

        return $this->isAll($visibilityOptions[$visibility])
               || $this->isInArray($this->getName(), $visibilityOptions[$visibility]);
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

        return $this->hasVisibility('visible');
    }

    /**
     * @param $value
     * @param $array
     * @return bool
     */
    private function isInArray($value, $array)
    {
        return is_array($array) && in_array($value, $array);
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->hasVisibility('hidden');
    }

    /**
     * @return bool
     */
    public function isFillable()
    {
        $fillable = Artificer::modelManager()->current()->settings()->getFillable();

        return $this->isAll($fillable) || in_array($this->getName(), $fillable);
    }

    /**
     * @param array|string $classes
     */
    protected function mergeAttribute($attribute, $value)
    {
        if (! is_array($value)) {
            $value = [$value];
        }

        $attributes = $this->getAttributes()[$attribute] ?? [];

        return array_merge($attributes, $value);
    }

    /**
     * @param string $attribute
     * @param array|string $value
     */
    public function addAttribute($attribute, $value)
    {
        $value = $this->mergeAttribute($attribute, $value);

        $this->setOptions(['attributes' => [$attribute => $value]]);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function transformValue($value)
    {
        return $value;
    }
}
