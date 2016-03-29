<?php namespace Mascame\Artificer\Fields;

use App;
use Mascame\Artificer\Widgets\AbstractWidget;

class Field extends \Mascame\Formality\Field\Field
{
    use Filterable;

    /**
     * @var null
     */
    public $value;

    public static $widgets = array();
    /**
     * @var FieldOptions
     */
    public $options;

    /**
     * @var FieldRelation
     */
    public $relation = null;

    /**
     * Sometimes ajax limits output, setting this to true will return all
     *
     * @var bool
     */
    public $showFullField = false;

    /**
     * @param $name
     * @param null $value
     * @param $modelName
     * @param $relation
     */
    public function __construct($typeClass, $name, $value = null, $relation)
    {
        parent::__construct($typeClass, $name, $value, $options = []);

        if ($relation) {
            $this->relation = new FieldRelation($this->getOption('relationship'));
        }

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
        if ( ! $this->getOption('widgets')) {
            return null;
        }

        $widgets = $this->getOption('widgets');

        foreach ($widgets as $widget) {
            try {
                $this->addWidget(App::make($widget));
            } catch (\Exception $e) {
                throw new \Exception("Widget '{$widget}' was not found");
            }
        }
    }


    /**
     * @return null
     */
    public function show()
    {
        return $this->value;
    }

    /**
     * @param null $value
     * @return null
     */
//    public function display($value = null)
//    {
//        $this->value = $this->getValue($value);
//
//        return $this->show();
//    }


    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * @param null $value
     * @return null
     */
    public function getValue($value = null)
    {
        $value = ($value) ? $value : $this->options->get('default');

        if ($this->options->has('show')) {
            $show = $this->options->get('show');

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
        if ($this->isHidden()) return null;

        if ($this->isGuarded()) return $this->guarded();

        return parent::output();
    }


    /**
     * @return string
     */
    public function hidden()
    {
        return '<div class="label label-warning">Hidden data</div>';
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
     * @param string $list
     * @return bool
     */
    public function isListed($list = 'show')
    {
        if (!isset($this->options->model['list'][$list])) {
            return false;
        }

        $list = $this->options->model['list'][$list];

        if ($this->isAll($list)) {
            return true;
        }

        return $this->isInArray($this->name, $list);
    }


    /**
     * @return bool
     */
    public function isHiddenList()
    {
        return $this->isListed('hide');
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
        return '(guarded) ' . $this->show();
    }

    /**
     * @return bool
     */
    public function isGuarded()
    {
        if (!isset($this->options->model['guarded'])) {
            return false;
        }

        return $this->isInArray($this->name, $this->options->model['guarded']);
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        if (!isset($this->options->model['hidden'])) {
            return false;
        }

        return $this->isInArray($this->name, $this->options->model['hidden']);
    }

    /**
     * @return bool
     */
    public function isRelation()
    {
        return $this->relation;
    }

    public static function get($name)
    {
        return array_get(\View::getShared(), 'fields')[$name];
    }

    public function __call($method, $args) {
        if (! method_exists($this->type, $method)) {
            return null;
        }

        return $this->type->$method($args);
    }
}