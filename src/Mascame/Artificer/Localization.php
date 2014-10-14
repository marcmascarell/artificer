<?php namespace Mascame\Artificer;

use LaravelLocalization;
use Str;
use Mascame\Artificer\Options\AdminOption;

class Localization  {

	protected $lang_closure;
	protected $locales;

	public function __construct() {
		$closure = AdminOption::get('localization.lang_detection');
		$this->locales = $this->getLocales();

		if ($closure && Artificer::is_closure($closure)) {
			$this->lang_closure = $closure;
		}
	}

	public function getLocales() {
		$locales = AdminOption::get('localization.locales');

		if (is_array($locales)) return array_keys($locales);

		return array();
	}

	public function parseColumnLang($column) {
		if ($this->lang_closure) {
			return $this->lang_closure($column);
		}

		return $this->detectColumnLang($column);
	}

	protected function detectColumnLang($column) {
		foreach ($this->getLanguageEndings() as $locale => $ending) {
			if (Str::endsWith($column, $ending)) {
				return $locale;
			}
		}

		return false;
	}

	protected function getLanguageEndings() {
		$endings = array();

		foreach ($this->locales as $locale) {
			$endings[$locale] = '_' . $locale;
		}

		return $endings;
	}
}