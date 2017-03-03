<?php

namespace Mascame\Artificer\Fields;

use Event;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Localization;
use Illuminate\Database\Query\Builder;
use Mascame\Artificer\Widgets\AbstractWidget;

class Field implements FieldInterface
{
    public $type;
    public $title;
    public $name;
    public $modelName;
    public $configKey;
    public $configFieldKey;
    public $value;
    public $output;
    public static $widgets = [];
    /**
     * @var FieldOptions
     */
    public $options;
    public $lists = [];
    /**
     * @var FieldRelation
     */
    public $relation;
    /**
     * @var Localization
     */
    public $localization;
    public $locale;
    public $wiki;
    /**
     * @var FieldAttributes
     */
    public $attributes;

    /**
     * Sometimes ajax limits output, setting this to true will return all.
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
    public function __construct($name, $value, $modelName, $relation)
    {
        $this->name = $name;
        $this->value = $value;
        $this->modelName = $modelName;
        $this->type = $this->getType(get_called_class());

        $this->options = new FieldOptions($this->name, $this->type);

        $this->relation = new FieldRelation($relation, $this->options->getExistent('relationship'));
        $this->attributes = new FieldAttributes($this->options->getExistent('attributes'), $this->options);

        if (! $this->attributes->has('class')) {
            $this->attributes->add(['class' => 'form-control']);
        }

        $this->title = $this->getTitle($this->name);
        $this->wiki = $this->getWiki();

        $this->boot();
    }

    public function getWiki()
    {
        return $this->options->getExistent('wiki');
    }

    /**
     * @param string $type_class
     * @return string
     */
    public function getType($type_class)
    {
        $pieces = explode('\\', $type_class);

        return strtolower(end($pieces));
    }

    /**
     * @param $widget
     * @return bool
     */
    public function addWidget(AbstractWidget $widget)
    {
        if (! in_array($widget->name, self::$widgets)) {
            self::$widgets[$widget->name] = $widget;

            return true;
        }

        return false;
    }

    /**
     * Used to load custom assets, widgets, ...
     */
    public function boot()
    {
        if ($this->options->has('widgets')) {
            $widgets = $this->options->get('widgets');

            foreach ($widgets as $widget) {
                $this->addWidget($widget);
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
    public function display($value = null)
    {
        $this->value = $this->getValue($value);

        return $this->show();
    }

    /**
     * @param null $value
     * @return null
     */
    public function getValue($value = null)
    {
        $value = ($value) ? $value : $this->options->getExistent('default', null);

        if ($this->options->has('show')) {
            $show = $this->options->get('show');

            if (is_callable($show)) {
                return $show($value);
            }
        }

        return $value;
    }

    /**
     * @return bool
     */
    public function input()
    {
        return false;
    }

    /**
     * @param $input
     * @return mixed
     */
    public function userInput($input)
    {
        $input = str_replace('(:value)', $this->value, $input);
        $input = str_replace('(:name)', $this->name, $input);
        $input = str_replace('(:label)', $this->title, $input);

        return $input;
    }

    /**
     * @return bool|mixed|null|string
     */
    public function output()
    {
        Event::fire('artificer.field.'.$this->type.'.before.output', $this->value);

        if ($this->isHidden()) {
            return;
        } elseif ($this->isGuarded()) {
            return $this->guarded();
        }

        $this->value = $this->getValue($this->value);

        if ($this->options->has('input')) {
            return $this->userInput($this->options->get('input'));
        }

        return $this->input();
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
        return is_array($array) && isset($array[0]) && $array[0] == '*';
    }

    /**
     * @param string $list
     * @return bool
     */
    public function isListed($list = 'show')
    {
        if (! isset($this->options->model['list'][$list])) {
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
        return '(guarded) '.$this->show();
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getTitle($name)
    {
        if ($this->options->has('title')) {
            return $this->options->get('title');
        }

        return $name;
    }

    /**
     * @return bool
     */
    public function isGuarded()
    {
        if (! isset($this->options->model['guarded'])) {
            return false;
        }

        return $this->isInArray($this->name, $this->options->model['guarded']);
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        if (! isset($this->options->model['hidden'])) {
            return false;
        }

        return $this->isInArray($this->name, $this->options->model['hidden']);
    }

    /**
     * @return bool
     */
    public function isRelation()
    {
        return $this->relation->isRelation();
    }

    /**
     * @param $query Builder
     * @param $value
     * @return mixed
     */
    public function filter($query, $value)
    {
        return $query->where($query, $value);
    }

    /**
     * @return bool
     */
    public function displayFilter()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function hasFilter()
    {
        return ($this->displayFilter()) ? true : false;
    }

    public static function get($name)
    {
        return array_get(\View::getShared(), 'fields')[$name];
    }
}
