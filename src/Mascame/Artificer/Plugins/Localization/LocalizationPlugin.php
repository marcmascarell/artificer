<?php namespace Mascame\Artificer\Plugins\Localization;

use Mascame\Artificer\Localization;
use Mascame\Artificer\Model\Model;
use Mascame\Artificer\Plugin\AbstractPlugin;
use Event;
use App;

class LocalizationPlugin extends AbstractPlugin {

    /**
     * @var Localization;
     */
    public static $localization;
    public static $localized_group;
    public static $end_localized_group = false;

	public function meta()
	{
		$this->version = '1.0';
		$this->name = 'Localization';
		$this->description = 'Provides language functionality';
		$this->author = 'Marc Mascarell';
	}

	public function boot()
	{
        self::$localization = App::make('artificer-localization');
		$this->addHooks();
	}

	public function addHooks()
	{
        \Event::listen(array('artificer.before.edit.title'), function ($field) {

            if (self::isLocalized($field->name)) {
                $locale = self::getLocale($field->name);

                $group = str_replace('_' . $locale, '', $field->name);

                self::$localized_group = $group;

                $field->title = self::$localized_group . ' ('.self::$localization->getLocaleNative($locale).')';
            }
        });

//        \Event::listen(array('artificer.before.edit.output'), function ($field) {
//            if (self::isLocalized($field->name)) {
//
//            }
//        });
	}

    public static function isLocalized($name) {
        if (self::getLocale($name)) {
            return true;
        }

        return false;
    }

    public static function getLocale($name) {
        if ($lang = self::$localization->parseColumnLang($name)) {
            return $lang;
        }

        return false;
    }

}