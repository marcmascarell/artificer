<?php namespace Mascame\Artificer;

use LaravelLocalization;
use Str;
use Mascame\Artificer\Options\AdminOption;

class Localization  {

	/**
	 * @var \Closure
	 */
	protected $lang_closure;

	/**
	 * @var array
	 */
	protected $locales;


	public function __construct() {
		$closure = AdminOption::get('localization.lang_detection');
		$this->locales = $this->getLocales();

		if ($closure && Artificer::is_closure($closure)) {
			$this->lang_closure = $closure;
		}
	}

	/**
	 * @return array
	 */
	public function getLocales() {
		$locales = AdminOption::get('localization.locales');

		if (is_array($locales)) return array_keys($locales);

		return array();
	}

	/**
	 * @param $column
	 * @return bool|int|string
	 */
	public function parseColumnLang($column) {
		if ($this->lang_closure) {
			return $this->lang_closure($column);
		}

		return $this->detectColumnLang($column);
	}

	/**
	 * @param $column
	 * @return bool|int|string
	 */
	protected function detectColumnLang($column) {
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
	protected function getLanguageEndings() {
		$endings = array();

		foreach ($this->locales as $locale) {
			$endings[$locale] = '_' . $locale;
		}

		return $endings;
	}
}