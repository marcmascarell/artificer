<?php namespace Mascame\Artificer\Fields;

use App;
use Mascame\Artificer\Artificer;
use Mascame\Artificer\Widget\AbstractWidget;
use Mascame\Formality\Field\FieldInterface;
use Mascame\Formality\Field\TypeInterface;

class FieldWrapper
{
    use Filterable;

    public static $widgets = [];

    /**
     * Sometimes ajax limits output, setting this to true will return all
     *
     * @var bool
     */
    public $showFullField = false;

    /**
     * @var FieldInterface|TypeInterface
     */
    protected $field;

    /**
     * Field constructor.
     * @param FieldInterface|TypeInterface $field
     * @param null $relation
     */
    public function __construct(FieldInterface $field)
    {
        $this->field = $field;

        $this->boot();
    }

    /**
     * @param $widget
     * @return bool
     */
    public function addWidget(AbstractWidget $widget)
    {
        if ( ! in_array($widget->name, self::$widgets)) {
            self::$widgets[$widget->name] = $widget;

            return true;
        }

        return false;
    }


    /**
     * Used to load custom assets, widgets, ...
     *
     */
    public function boot()
    {
        if ( ! $this->field->getOption('widgets')) {
            return null;
        }

        $widgets = $this->field->getOption('widgets');

        foreach ($widgets as $widget) {
            try {
                $this->addWidget(App::make($widget));
            } catch (\Exception $e) {
                throw new \Exception("Widget '{$widget}' was not found");
            }
        }
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
        if ($this->isHidden()) return null;

        if ($this->isGuarded()) return $this->guarded();

        return $this->field->output();
    }

    /**
     * @param $array
     * @return bool
     */
    protected function isAll($array)
    {
        return (is_array($array) && isset($array[0]) && $array[0] == '*');
    }

    /**
     * @param string $visibility [visible|hidden]
     * @return bool
     */
    protected function isListedAs($visibility, $action)
    {
        $listOptions = Artificer::getModel()->getOption($action);

        if (! $listOptions || ! isset($listOptions[$visibility])) return false;

        $list = $listOptions[$visibility];

        if ($this->isAll($list)) return true;

        return $this->isInArray($this->field->getName(), $list);
    }

    /**
     * list, edit, create
     *
     * Hidden fields have preference.
     *
     * @return bool
     */
    public function isListable($action = null)
    {
        if ( ! $action) $action = Artificer::getCurrentAction();

        if ($this->isListedAs('hidden', $action)) return false;

        return $this->isListedAs('visible', $action);
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
     * @return string
     */
    public function guarded()
    {
        return '[PROTECTED] ' . $this->show();
    }

    /**
     * @return bool
     */
    public function isGuarded()
    {
        $guarded = Artificer::getModel()->getOption('guarded', []);

        return $this->isInArray($this->field->getName(), $guarded);
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        $hidden = Artificer::getModel()->getOption('hidden', []);

        return $this->isInArray($this->field->getName(), $hidden);
    }

    public static function get($name)
    {
        return array_get(\View::getShared(), 'fields')[$name];
    }

    public function __get($name) {
        $accessor = 'get' . studly_case($name);

        if (! method_exists($this->field, $accessor)) {
            return null;
        }

        return $this->field->$accessor();
    }

    public function __call($method, $args) {
        if (! method_exists($this->field, $method)) {
            return null;
        }

        return $this->field->$method($args);
    }
}