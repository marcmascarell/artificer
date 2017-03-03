<?php

namespace Mascame\Artificer;

use Str;
use Mascame\Artificer\Options\AdminOption;

class Localization
{
    /**
     * @var \Closure
     */
    protected $lang_closure;

    /**
     * @var array
     */
    protected $locales = [];

    public function __construct()
    {
        $closure = AdminOption::get('localization.lang_detection');
        $this->locales = $this->getConfigLocales();

        if ($closure && Artificer::isClosure($closure)) {
            $this->lang_closure = $closure;
        }
    }

    public function getConfigLocales()
    {
        return (! empty($this->locales)) ? $this->locales : $this->locales = AdminOption::get('localization.locales');
    }

    /**
     * @return array
     */
    public function getLocales()
    {
        $this->locales = $this->getConfigLocales();

        if (is_array($this->locales)) {
            return array_keys($this->locales);
        }

        return [];
    }

    public function getLocaleNative($locale)
    {
        $this->locales = $this->getConfigLocales();

        if (is_array($this->locales[$locale])) {
            return $this->locales[$locale]['native'];
        }

        return [];
    }

    /**
     * @param $column
     * @return bool|int|string
     */
    public function parseColumnLang($column)
    {
        if ($this->lang_closure) {
            return $this->lang_closure($column);
        }

        return $this->detectColumnLang($column);
    }

    /**
     * @param $column
     * @return bool|int|string
     */
    protected function detectColumnLang($column)
    {
        foreach ($this->getLanguageEndings() as $locale => $ending) {
            if (Str::endsWith($column, $ending)) {
                return $locale;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    protected function getLanguageEndings()
    {
        $endings = [];

        foreach ($this->getLocales() as $locale) {
            $endings[$locale] = '_'.$locale;
        }

        return $endings;
    }
}
